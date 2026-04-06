import { sanctumAPI } from './SanctumAPI';
import { sqliteService } from './SQLiteService';
import { useOfflineStore } from '../store/offlineStore';
import { useAuthStore } from '../store/authStore';

/**
 * SyncService handles offline-to-online synchronization
 * Manages sync queue draining and conflict resolution
 */
class SyncService {
  async initializeSync() {
    // Check connectivity on app start
    await useOfflineStore.getState().checkConnectivity();

    // Setup network listener
    useOfflineStore.getState().setupNetworkListener(async (isOnline) => {
      if (isOnline) {
        await this.drainSyncQueue();
      }
    });
  }

  /**
   * Drain pending sync events to server
   * Batches up to 100 events per request
   */
  private async drainSyncQueue() {
    try {
      const pendingEvents = await sqliteService.getPendingSyncEvents();

      if (pendingEvents.length === 0) {
        console.log('No pending sync events');
        return;
      }

      console.log(`Syncing ${pendingEvents.length} events to server...`);

      const events = pendingEvents.map((event: any) => ({
        event_type: event.event_type,
        child_id: event.child_id,
        payload: JSON.parse(event.payload),
        idempotency_key: event.idempotency_key,
      }));

      // Send to server
      const response = await sanctumAPI.syncOfflineEvents(events);

      // Mark as synced
      for (const event of pendingEvents) {
        await sqliteService.markSyncEventAsProcessed(event.id);
      }

      console.log('Sync completed successfully:', response.data);

      // Refresh content manifest after sync
      await this.refreshContentManifest();

    } catch (error) {
      console.error('Sync queue drain failed:', error);
    }
  }

  /**
   * Record a progress event (offline-first)
   */
  async recordProgressEvent(
    childId: number,
    eventType: string,
    comicId?: number,
    payload?: any
  ) {
    const isOnline = useOfflineStore.getState().isOnline;
    const idempotencyKey = `progress_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;

    if (isOnline) {
      // Send immediately if online
      try {
        await sanctumAPI.recordProgressEvent(childId, eventType, comicId, payload);
      } catch (error) {
        // Fallback to offline queue on error
        await sqliteService.addToSyncQueue(eventType, { child_id: childId, comic_id: comicId, ...payload }, idempotencyKey);
      }
    } else {
      // Queue offline
      await sqliteService.addToSyncQueue(eventType, { child_id: childId, comic_id: comicId, ...payload }, idempotencyKey);
    }
  }

  /**
   * Refresh content manifest from server
   */
  async refreshContentManifest() {
    try {
      const response = await sanctumAPI.getContentManifest();
      await sqliteService.saveContentManifest(response.data.comics);
    } catch (error) {
      console.error('Failed to refresh content manifest:', error);
    }
  }

  /**
   * Get comics for offline display
   */
  async getOfflineComics() {
    const manifest = await sqliteService.getContentManifest();
    return manifest;
  }
}

export const syncService = new SyncService();
