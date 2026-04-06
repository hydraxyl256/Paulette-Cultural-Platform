<?php

namespace App\Jobs;

use App\Models\Comic;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class BuildOfflineBundle implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private int $comicId
    ) {}

    /**
     * Build .ckb offline bundle
     * Packages comic images, audio, and metadata into a signed ZIP file
     */
    public function handle(): void
    {
        $comic = Comic::load('tribe', 'panels')->findOrFail($this->comicId);

        try {
            $bundleDir = storage_path("app/bundles");
            if (!is_dir($bundleDir)) {
                mkdir($bundleDir, 0755, true);
            }

            $bundleFileName = "{$comic->id}_" . date('YmdHis') . '.ckb';
            $bundlePath = "{$bundleDir}/{$bundleFileName}";

            $zip = new ZipArchive();
            $zip->open($bundlePath, ZipArchive::CREATE);

            // Add metadata
            $metadata = [
                'id' => $comic->id,
                'title' => $comic->title,
                'tribe_id' => $comic->tribe_id,
                'tribe_name' => $comic->tribe->name,
                'age_min' => $comic->age_min,
                'age_max' => $comic->age_max,
                'panels_count' => $comic->panels->count(),
            ];

            $zip->addFromString('metadata.json', json_encode($metadata, JSON_PRETTY_PRINT));

            // Add panel images and audio
            foreach ($comic->panels as $panel) {
                if ($panel->image_path) {
                    $content = Storage::disk('s3')->get($panel->image_path);
                    $zip->addFromString("images/panel_{$panel->order_index}.jpg", $content);
                }

                if ($panel->audio_path) {
                    $content = Storage::disk('s3')->get($panel->audio_path);
                    $zip->addFromString("audio/panel_{$panel->order_index}.mp3", $content);
                }
            }

            $zip->close();

            // Upload bundle to S3
            $s3Path = "bundles/{$comic->org_id}/{$bundleFileName}";
            $bundleContent = file_get_contents($bundlePath);
            Storage::disk('s3')->put($s3Path, $bundleContent);

            // Calculate hash
            $bundleHash = hash_file('sha256', $bundlePath);

            // Update comic with bundle info
            $comic->update([
                'bundle_path' => $s3Path,
                'bundle_hash' => $bundleHash,
            ]);

            // Clean up local file
            unlink($bundlePath);

            Log::info("Bundle created for comic {$comic->id}: {$s3Path}");

        } catch (\Exception $e) {
            Log::error("Bundle creation failed for comic {$this->comicId}: {$e->getMessage()}");
            throw $e;
        }
    }
}
