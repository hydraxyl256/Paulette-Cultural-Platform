import * as FileSystem from 'expo-file-system';
import * as SecureStore from 'expo-secure-store';

const BUNDLES_DIR = `${FileSystem.documentDirectory}bundles/`;

/**
 * BundleService handles .ckb (Culture Kids Bundle) downloads and extraction
 * Bundles are signed ZIPs containing comics, images, and audio offline
 */
class BundleService {
  async initializeBundlesDirectory() {
    const info = await FileSystem.getInfoAsync(BUNDLES_DIR);
    if (!info.exists) {
      await FileSystem.makeDirectoryAsync(BUNDLES_DIR, { intermediates: true });
    }
  }

  /**
   * Download a tribe bundle (.ckb file)
   */
  async downloadBundle(tribeId: number, bundleUrl: string) {
    try {
      const bundleFileName = `tribe_${tribeId}_${Date.now()}.ckb`;
      const bundlePath = `${BUNDLES_DIR}${bundleFileName}`;

      // Download with progress tracking
      const downloadResumable = FileSystem.createDownloadResumable(
        bundleUrl,
        bundlePath,
        {}
      );

      const { uri } = await downloadResumable.downloadAsync();

      console.log(`Bundle downloaded to: ${uri}`);

      // Extract bundle
      await this.extractBundle(uri, tribeId);

      return uri;
    } catch (error) {
      console.error('Bundle download failed:', error);
      throw error;
    }
  }

  /**
   * Extract .ckb (ZIP) bundle
   * In a real app, use expo-zip or native module
   */
  private async extractBundle(bundlePath: string, tribeId: number) {
    const extractPath = `${BUNDLES_DIR}tribe_${tribeId}/`;

    try {
      // Create extraction directory
      await FileSystem.makeDirectoryAsync(extractPath, { intermediates: true });

      // In production, use a proper ZIP extraction library
      console.log(`Extracting bundle to: ${extractPath}`);

      // Store metadata for offline use
      await SecureStore.setItemAsync(
        `bundle_${tribeId}_path`,
        extractPath
      );

    } catch (error) {
      console.error('Bundle extraction failed:', error);
    }
  }

  /**
   * Get local bundle path for tribe
   */
  async getBundlePath(tribeId: number): Promise<string | null> {
    try {
      return await SecureStore.getItemAsync(`bundle_${tribeId}_path`);
    } catch (error) {
      return null;
    }
  }

  /**
   * Get bundle size
   */
  async getBundleSize(tribeId: number): Promise<number> {
    const bundlePath = await this.getBundlePath(tribeId);
    if (!bundlePath) return 0;

    try {
      const info = await FileSystem.getInfoAsync(bundlePath);
      return info.size || 0;
    } catch (error) {
      return 0;
    }
  }

  /**
   * Delete bundle
   */
  async deleteBundle(tribeId: number) {
    const bundlePath = await this.getBundlePath(tribeId);
    if (!bundlePath) return;

    try {
      await FileSystem.deleteAsync(bundlePath);
      await SecureStore.deleteItemAsync(`bundle_${tribeId}_path`);
    } catch (error) {
      console.error('Bundle deletion failed:', error);
    }
  }
}

export const bundleService = new BundleService();
