import React, { useEffect, useState } from 'react';
import { View, ScrollView, StyleSheet, Text, ActivityIndicator, TouchableOpacity, Dimensions, Image } from 'react-native';
import { useLocalSearchParams, useRouter } from 'expo-router';
import { SanctumAPI } from '@/services/SanctumAPI';
import { useAuthStore } from '@/store/authStore';
import { useSyncService } from '@/services/SyncService';

interface Panel {
  id: number;
  panel_number: number;
  image_path: string;
  transcript: string;
  vocab_tags: string[];
}

interface Comic {
  id: number;
  title: string;
  description: string;
  panels: Panel[];
}

export default function ComicViewerScreen() {
  const { id } = useLocalSearchParams();
  const router = useRouter();
  const { token } = useAuthStore();
  const { recordProgressEvent } = useSyncService();

  const [comic, setComic] = useState<Comic | null>(null);
  const [currentPanelIndex, setCurrentPanelIndex] = useState(0);
  const [loading, setLoading] = useState(true);
  const [isCompleted, setIsCompleted] = useState(false);
  const [startTime] = useState(Date.now());

  const screenWidth = Dimensions.get('window').width;

  useEffect(() => {
    loadComic();
  }, []);

  const loadComic = async () => {
    try {
      const api = new SanctumAPI(token);
      const data = await api.getComicPanels(Number(id));
      setComic(data);
    } catch (err) {
      console.error('Failed to load comic', err);
    } finally {
      setLoading(false);
    }
  };

  const goToNextPanel = () => {
    if (comic && currentPanelIndex < comic.panels.length - 1) {
      setCurrentPanelIndex(currentPanelIndex + 1);
    } else if (comic && currentPanelIndex === comic.panels.length - 1) {
      handleCompletion();
    }
  };

  const goToPreviousPanel = () => {
    if (currentPanelIndex > 0) {
      setCurrentPanelIndex(currentPanelIndex - 1);
    }
  };

  const handleCompletion = async () => {
    if (!comic) return;

    const durationSeconds = Math.floor((Date.now() - startTime) / 1000);

    try {
      // Record completion event
      await recordProgressEvent({
        event_type: 'story_completed',
        comic_id: comic.id,
        duration_seconds: durationSeconds,
        metadata: { panels_count: comic.panels.length },
      });

      setIsCompleted(true);

      // Show completion screen for 2 seconds then go back
      setTimeout(() => {
        router.back();
      }, 2000);
    } catch (err) {
      console.error('Failed to record completion', err);
    }
  };

  if (loading) {
    return (
      <View style={styles.container}>
        <ActivityIndicator size="large" color="#2196F3" />
      </View>
    );
  }

  if (!comic) {
    return (
      <View style={styles.container}>
        <Text style={styles.errorText}>Comic not found</Text>
      </View>
    );
  }

  if (isCompleted) {
    return (
      <View style={styles.completionScreen}>
        <Text style={styles.completionEmoji}>🎉</Text>
        <Text style={styles.completionText}>Story Complete!</Text>
        <Text style={styles.completionSubtext}>You've earned a badge!</Text>
      </View>
    );
  }

  const currentPanel = comic.panels[currentPanelIndex];
  const progress = ((currentPanelIndex + 1) / comic.panels.length) * 100;

  return (
    <View style={styles.container}>
      {/* Header */}
      <View style={styles.header}>
        <TouchableOpacity onPress={() => router.back()}>
          <Text style={styles.backButton}>← Back</Text>
        </TouchableOpacity>
        <Text style={styles.title}>{comic.title}</Text>
        <Text style={styles.pageIndicator}>{currentPanelIndex + 1}/{comic.panels.length}</Text>
      </View>

      {/* Progress Bar */}
      <View style={styles.progressBar}>
        <View style={[styles.progressFill, { width: `${progress}%` }]} />
      </View>

      {/* Panel Display */}
      <ScrollView
        style={styles.panelContainer}
        contentContainerStyle={styles.panelContent}
        scrollEnabled={true}
      >
        {currentPanel.image_path && (
          <Image
            source={{ uri: currentPanel.image_path }}
            style={[styles.panelImage, { width: screenWidth - 20 }]}
            resizeMode="contain"
          />
        )}

        {currentPanel.transcript && (
          <View style={styles.transcriptBox}>
            <Text style={styles.transcriptText}>{currentPanel.transcript}</Text>
          </View>
        )}

        {currentPanel.vocab_tags && currentPanel.vocab_tags.length > 0 && (
          <View style={styles.vocabBox}>
            <Text style={styles.vocabTitle}>📚 Vocabulary</Text>
            <View style={styles.vocabTags}>
              {currentPanel.vocab_tags.map((word, idx) => (
                <View key={idx} style={styles.vocabTag}>
                  <Text style={styles.vocabTagText}>{word}</Text>
                </View>
              ))}
            </View>
          </View>
        )}
      </ScrollView>

      {/* Navigation Buttons */}
      <View style={styles.navigationBar}>
        <TouchableOpacity
          style={[styles.navButton, currentPanelIndex === 0 && styles.navButtonDisabled]}
          onPress={goToPreviousPanel}
          disabled={currentPanelIndex === 0}
        >
          <Text style={styles.navButtonText}>← Previous</Text>
        </TouchableOpacity>

        <TouchableOpacity
          style={[styles.navButton, styles.navButtonPrimary]}
          onPress={goToNextPanel}
        >
          <Text style={[styles.navButtonText, styles.navButtonTextPrimary]}>
            {currentPanelIndex === comic.panels.length - 1 ? 'Complete! ✨' : 'Next →'}
          </Text>
        </TouchableOpacity>
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
  },
  header: {
    backgroundColor: '#2196F3',
    paddingHorizontal: 16,
    paddingVertical: 12,
    paddingTop: 20,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
  },
  backButton: {
    color: 'white',
    fontSize: 16,
    fontWeight: '600',
  },
  title: {
    color: 'white',
    fontSize: 18,
    fontWeight: '600',
    flex: 1,
    textAlign: 'center',
  },
  pageIndicator: {
    color: 'white',
    fontSize: 14,
  },
  progressBar: {
    height: 6,
    backgroundColor: '#e0e0e0',
  },
  progressFill: {
    height: 6,
    backgroundColor: '#4caf50',
  },
  panelContainer: {
    flex: 1,
  },
  panelContent: {
    padding: 10,
    alignItems: 'center',
  },
  panelImage: {
    height: 300,
    borderRadius: 12,
    marginVertical: 16,
  },
  transcriptBox: {
    backgroundColor: '#f5f5f5',
    borderRadius: 8,
    padding: 12,
    marginVertical: 12,
    width: '100%',
  },
  transcriptText: {
    fontSize: 16,
    lineHeight: 24,
    color: '#333',
  },
  vocabBox: {
    backgroundColor: '#e3f2fd',
    borderRadius: 8,
    padding: 12,
    marginVertical: 12,
    width: '100%',
  },
  vocabTitle: {
    fontSize: 14,
    fontWeight: '600',
    color: '#1565c0',
    marginBottom: 8,
  },
  vocabTags: {
    flexDirection: 'row',
    flexWrap: 'wrap',
  },
  vocabTag: {
    backgroundColor: '#1976d2',
    borderRadius: 20,
    paddingHorizontal: 12,
    paddingVertical: 6,
    marginRight: 8,
    marginBottom: 8,
  },
  vocabTagText: {
    color: 'white',
    fontSize: 12,
    fontWeight: '500',
  },
  navigationBar: {
    flexDirection: 'row',
    backgroundColor: 'white',
    borderTopWidth: 1,
    borderTopColor: '#eee',
    paddingHorizontal: 10,
    paddingVertical: 10,
    gap: 10,
  },
  navButton: {
    flex: 1,
    backgroundColor: '#f5f5f5',
    borderRadius: 8,
    paddingVertical: 12,
    alignItems: 'center',
  },
  navButtonDisabled: {
    opacity: 0.5,
  },
  navButtonPrimary: {
    backgroundColor: '#2196F3',
  },
  navButtonText: {
    fontSize: 14,
    fontWeight: '600',
    color: '#333',
  },
  navButtonTextPrimary: {
    color: 'white',
  },
  completionScreen: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#fff',
  },
  completionEmoji: {
    fontSize: 80,
    marginBottom: 16,
  },
  completionText: {
    fontSize: 28,
    fontWeight: 'bold',
    color: '#333',
  },
  completionSubtext: {
    fontSize: 16,
    color: '#666',
    marginTop: 8,
  },
  errorText: {
    fontSize: 16,
    color: '#d32f2f',
  },
});
