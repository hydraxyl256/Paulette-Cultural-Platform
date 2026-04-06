import * as SQLite from 'expo-sqlite';
import * as FileSystem from 'expo-file-system';

const DB_NAME = 'culturekids.db';

class SQLiteService {
  private db: SQLite.Database | null = null;

  async initialize() {
    try {
      this.db = await SQLite.openDatabaseAsync(DB_NAME);
      await this.createTables();
    } catch (error) {
      console.error('Failed to initialize SQLite:', error);
      throw error;
    }
  }

  private async createTables() {
    if (!this.db) return;

    const statements = [
      `CREATE TABLE IF NOT EXISTS content_manifest (
        id INTEGER PRIMARY KEY,
        comic_id INTEGER,
        tribe_id INTEGER,
        title TEXT,
        bundle_hash TEXT,
        downloaded INTEGER DEFAULT 0,
        bundle_path TEXT,
        updated_at TEXT
      );`,

      `CREATE TABLE IF NOT EXISTS sync_queue (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        event_type TEXT NOT NULL,
        payload TEXT NOT NULL,
        idempotency_key TEXT UNIQUE,
        synced INTEGER DEFAULT 0,
        created_at INTEGER
      );`,

      `CREATE TABLE IF NOT EXISTS child_progress_cache (
        child_id INTEGER,
        comic_id INTEGER,
        completed INTEGER DEFAULT 0,
        panels_seen TEXT,
        PRIMARY KEY (child_id, comic_id)
      );`,

      `CREATE TABLE IF NOT EXISTS age_profiles_cache (
        id INTEGER PRIMARY KEY,
        age_min INTEGER,
        age_max INTEGER,
        ui_mode TEXT,
        rules TEXT,
        cached_at INTEGER
      );`,

      `CREATE TABLE IF NOT EXISTS tribes_cache (
        id INTEGER PRIMARY KEY,
        name TEXT,
        slug TEXT UNIQUE,
        language TEXT,
        region TEXT,
        greeting TEXT,
        color_hex TEXT,
        emoji_symbol TEXT,
        cached_at INTEGER
      );`,
    ];

    for (const statement of statements) {
      try {
        await this.db.execAsync(statement);
      } catch (error) {
        console.error('SQL Error:', error);
      }
    }
  }

  // Sync Queue operations
  async addToSyncQueue(eventType: string, payload: any, idempotencyKey: string) {
    if (!this.db) return;

    await this.db.runAsync(
      `INSERT INTO sync_queue (event_type, payload, idempotency_key, created_at)
       VALUES (?, ?, ?, ?)`,
      [eventType, JSON.stringify(payload), idempotencyKey, Date.now()]
    );
  }

  async getPendingSyncEvents() {
    if (!this.db) return [];

    const result = await this.db.getAllAsync(
      `SELECT * FROM sync_queue WHERE synced = 0 ORDER BY created_at ASC LIMIT 100`
    );

    return result;
  }

  async markSyncEventAsProcessed(id: number) {
    if (!this.db) return;

    await this.db.runAsync(
      `UPDATE sync_queue SET synced = 1 WHERE id = ?`,
      [id]
    );
  }

  // Content manifest cache
  async saveContentManifest(comics: any[]) {
    if (!this.db) return;

    await this.db.execAsync('DELETE FROM content_manifest');

    for (const comic of comics) {
      await this.db.runAsync(
        `INSERT INTO content_manifest (comic_id, tribe_id, title, bundle_hash, updated_at)
         VALUES (?, ?, ?, ?, ?)`,
        [comic.id, comic.tribe_id, comic.title, comic.bundle_hash, new Date().toISOString()]
      );
    }
  }

  async getContentManifest() {
    if (!this.db) return [];

    return await this.db.getAllAsync(`SELECT * FROM content_manifest WHERE downloaded = 1`);
  }

  // Progress cache
  async cacheChildProgress(childId: number, comicId: number, panelsSeen: number[]) {
    if (!this.db) return;

    await this.db.runAsync(
      `INSERT OR REPLACE INTO child_progress_cache (child_id, comic_id, completed, panels_seen)
       VALUES (?, ?, ?, ?)`,
      [childId, comicId, 1, JSON.stringify(panelsSeen)]
    );
  }

  async getChildProgress(childId: number) {
    if (!this.db) return [];

    return await this.db.getAllAsync(
      `SELECT * FROM child_progress_cache WHERE child_id = ?`,
      [childId]
    );
  }

  // Age profiles cache
  async cacheAgeProfiles(profiles: any[]) {
    if (!this.db) return;

    await this.db.execAsync('DELETE FROM age_profiles_cache');

    for (const profile of profiles) {
      await this.db.runAsync(
        `INSERT INTO age_profiles_cache (id, age_min, age_max, ui_mode, rules, cached_at)
         VALUES (?, ?, ?, ?, ?, ?)`,
        [profile.id, profile.age_min, profile.age_max, profile.ui_mode, JSON.stringify(profile.rules), Date.now()]
      );
    }
  }

  async getAgeProfilesCache() {
    if (!this.db) return [];

    return await this.db.getAllAsync(`SELECT * FROM age_profiles_cache`);
  }

  // Close database
  async close() {
    if (this.db) {
      await this.db.closeAsync();
    }
  }
}

export const sqliteService = new SQLiteService();
