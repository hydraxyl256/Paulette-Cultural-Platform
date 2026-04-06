<?php

namespace App\Jobs;

use App\Models\Comic;
use App\Models\ComicPanel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessComicPDF implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private string $pdfPath,
        private int $comicId,
        private int $userId
    ) {}

    /**
     * Process PDF into individual panels
     * Extracts pages from PDF and stores as images
     */
    public function handle(): void
    {
        $comic = Comic::findOrFail($this->comicId);

        try {
            // Get the PDF from S3
            $pdfContent = Storage::disk('s3')->get($this->pdfPath);
            $localPath = storage_path("app/temp/{$this->comicId}.pdf");

            // Write to temp file
            file_put_contents($localPath, $pdfContent);

            // Extract pages using Imagick
            $imagick = new \Imagick();
            $imagick->readImage($localPath . '[*]');
            $imagick->setImageFormat('jpg');

            $panels = [];

            foreach ($imagick as $index => $image) {
                // Resize image for consistency
                $image->resizeImage(800, 1200, \Imagick::FILTER_LANCZOS, 1);

                // Save panel image to S3
                $panelPath = "comics/panels/{$comic->id}/panel_{$index}.jpg";
                Storage::disk('s3')->put($panelPath, $image->getImageBlob());

                // Create ComicPanel record
                ComicPanel::create([
                    'comic_id' => $comic->id,
                    'order_index' => $index,
                    'image_path' => $panelPath,
                ]);

                $panels[] = $panelPath;
            }

            // Clean up
            unlink($localPath);
            $imagick->clear();

            Log::info("Comic {$comic->id} processed: {$index} panels extracted");

            $comic->update(['status' => 'review']);

        } catch (\Exception $e) {
            Log::error("PDF processing failed for comic {$this->comicId}: {$e->getMessage()}");
            $comic->update(['status' => 'draft']);
            throw $e;
        }
    }
}
