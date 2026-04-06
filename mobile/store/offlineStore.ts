import { create } from 'zustand';
import * as NetInfo from 'expo-network';

interface OfflineState {
  isOnline: boolean;
  checkConnectivity: () => Promise<void>;
  setupNetworkListener: (callback: (isOnline: boolean) => void) => () => void;
}

export const useOfflineStore = create<OfflineState>((set) => ({
  isOnline: true,

  checkConnectivity: async () => {
    try {
      const state = await NetInfo.getNetworkStateAsync();
      set({ isOnline: state.isInternetReachable ?? false });
    } catch (error) {
      console.error('Failed to check connectivity:', error);
    }
  },

  setupNetworkListener: (callback: (isOnline: boolean) => void) => {
    const unsubscribe = NetInfo.addEventListener((state) => {
      const isOnline = state.isInternetReachable ?? false;
      set({ isOnline });
      callback(isOnline);
    });

    return unsubscribe;
  },
}));
