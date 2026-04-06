import axios, { AxiosInstance } from 'axios';
import * as SecureStore from 'expo-secure-store';

const API_BASE_URL = 'https://api.culturekids.app/api/v1';

class SanctumAPI {
  private client: AxiosInstance;

  constructor() {
    this.client = axios.create({
      baseURL: API_BASE_URL,
      timeout: 30000,
    });

    this.setupInterceptors();
  }

  private async setupInterceptors() {
    this.client.interceptors.request.use(async (config) => {
      const token = await SecureStore.getItemAsync('auth_token');
      if (token) {
        config.headers.Authorization = `Bearer ${token}`;
      }
      return config;
    });

    this.client.interceptors.response.use(
      (response) => response,
      (error) => {
        if (error.response?.status === 401) {
          // Handle token refresh or logout
          SecureStore.deleteItemAsync('auth_token');
        }
        return Promise.reject(error);
      }
    );
  }

  // Auth
  login(email: string, password: string) {
    return this.client.post('/auth/login', { email, password });
  }

  register(name: string, email: string, password: string) {
    return this.client.post('/auth/register', { name, email, password, password_confirmation: password });
  }

  logout() {
    return this.client.post('/auth/logout');
  }

  getUser() {
    return this.client.get('/auth/user');
  }

  // Content
  getTribes() {
    return this.client.get('/tribes');
  }

  getTribeComics(tribeId: number) {
    return this.client.get(`/tribes/${tribeId}/comics`);
  }

  getAgeProfiles() {
    return this.client.get('/age-profiles');
  }

  getContentManifest() {
    return this.client.get('/content/manifest');
  }

  // Progress
  recordProgressEvent(childId: number, eventType: string, comicId?: number, payload?: any) {
    return this.client.post('/progress/events', {
      child_id: childId,
      event_type: eventType,
      comic_id: comicId,
      payload,
    });
  }

  getChildProgress(childId: number) {
    return this.client.get(`/progress/child/${childId}`);
  }

  // Sync
  syncOfflineEvents(events: any[]) {
    return this.client.post('/sync', { events });
  }

  // Child Profiles
  getChildProfiles() {
    return this.client.get('/child-profiles');
  }

  createChildProfile(name: string, dateOfBirth: string, preferredTribeIds?: number[]) {
    return this.client.post('/child-profiles', {
      name,
      date_of_birth: dateOfBirth,
      preferred_tribe_ids: preferredTribeIds,
    });
  }
}

export const sanctumAPI = new SanctumAPI();
