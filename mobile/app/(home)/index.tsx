import React, { useEffect, useState } from 'react';
import { View, ScrollView, StyleSheet, Text, ActivityIndicator, FlatList, TouchableOpacity, Image } from 'react-native';
import { useRouter } from 'expo-router';
import { useAuthStore } from '@/store/authStore';
import { SanctumAPI } from '@/services/SanctumAPI';
import { useOfflineStore } from '@/store/offlineStore';

interface Tribe {
  id: number;
  name: string;
  description: string;
  symbol: string;
  color: string;
  comics_count: number;
}

export default function HomeScreen() {
  const router = useRouter();
  const { user, token } = useAuthStore();
  const { isOnline } = useOfflineStore();

  const [tribes, setTribes] = useState<Tribe[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    loadTribes();
  }, []);

  const loadTribes = async () => {
    try {
      const api = new SanctumAPI(token);
      const data = await api.getTribes();
      setTribes(data);
    } catch (err: any) {
      setError(err.message || 'Failed to load tribes');
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return (
      <View style={styles.container}>
        <ActivityIndicator size="large" color="#2196F3" />
      </View>
    );
  }

  return (
    <View style={styles.container}>
      {/* Header */}
      <View style={styles.header}>
        <Text style={styles.greeting}>Welcome, {user?.name}! 👋</Text>
        <Text style={styles.subtitle}>Choose a tribe to explore</Text>
        {!isOnline && <Text style={styles.offlineIndicator}>📡 Offline Mode</Text>}
      </View>

      {/* Error Alert */}
      {error ? (
        <View style={styles.errorBox}>
          <Text style={styles.errorText}>{error}</Text>
        </View>
      ) : null}

      {/* Tribes Grid */}
      <FlatList
        data={tribes}
        keyExtractor={(item) => item.id.toString()}
        numColumns={2}
        scrollEnabled={true}
        contentContainerStyle={styles.tribesGrid}
        renderItem={({ item }) => (
          <TouchableOpacity
            style={[styles.tribeCard, { borderColor: item.color }]}
            onPress={() => router.push(`/(home)/tribes/${item.id}`)}
          >
            <View style={[styles.tribeSymbol, { backgroundColor: item.color + '20' }]}>
              <Text style={styles.symbol}>{item.symbol}</Text>
            </View>
            <Text style={styles.tribeName}>{item.name}</Text>
            <Text style={styles.comicsCount}>{item.comics_count} stories</Text>
          </TouchableOpacity>
        )}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f5f5f5',
  },
  header: {
    backgroundColor: '#2196F3',
    paddingTop: 40,
    paddingHorizontal: 20,
    paddingBottom: 20,
  },
  greeting: {
    fontSize: 24,
    fontWeight: 'bold',
    color: 'white',
  },
  subtitle: {
    fontSize: 16,
    color: '#e3f2fd',
    marginTop: 8,
  },
  offlineIndicator: {
    fontSize: 12,
    color: '#ffeb3b',
    marginTop: 8,
    fontWeight: '600',
  },
  errorBox: {
    backgroundColor: '#ffebee',
    margin: 10,
    padding: 12,
    borderRadius: 8,
    borderLeftColor: '#d32f2f',
    borderLeftWidth: 4,
  },
  errorText: {
    color: '#d32f2f',
    fontSize: 14,
  },
  tribesGrid: {
    padding: 10,
  },
  tribeCard: {
    flex: 0.5,
    margin: 10,
    backgroundColor: 'white',
    borderRadius: 12,
    padding: 16,
    alignItems: 'center',
    borderWidth: 2,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  tribeSymbol: {
    width: 60,
    height: 60,
    borderRadius: 30,
    alignItems: 'center',
    justifyContent: 'center',
    marginBottom: 12,
  },
  symbol: {
    fontSize: 32,
  },
  tribeName: {
    fontSize: 16,
    fontWeight: '600',
    color: '#333',
    textAlign: 'center',
  },
  comicsCount: {
    fontSize: 12,
    color: '#999',
    marginTop: 4,
  },
});
