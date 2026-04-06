<?php

namespace App\Livewire;

use App\Jobs\ProcessComicPDF;
use App\Models\Comic;
use Livewire\Component;
use Livewire\WithFileUploads;

class ComicUploader extends Component
{
    use WithFileUploads;

    public $title = '';
    public $description = '';
    public $tribe_id = null;
    public $age_profile_id = null;
    public $pdf_file;
    public $uploading = false;
    public $uploadProgress = 0;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'tribe_id' => 'nullable|exists:tribes,id',
        'age_profile_id' => 'nullable|exists:age_profiles,id',
        'pdf_file' => 'required|file|mimes:pdf|max:50000',
    ];

    public function updatingPdfFile()
    {
        $this->uploading = true;
    }

    public function updatedPdfFile()
    {
        $this->uploading = false;
    }

    public function submit()
    {
        $this->validate();

        // Store PDF
        $pdfPath = $this->pdf_file->store('comics/uploads', 'private');

        // Create comic
        $comic = Comic::create([
            'org_id' => auth()->user()->org_id,
            'title' => $this->title,
            'description' => $this->description,
            'tribe_id' => $this->tribe_id,
            'age_profile_id' => $this->age_profile_id,
            'status' => 'draft',
        ]);

        // Dispatch processing
        ProcessComicPDF::dispatch($comic, $pdfPath);

        // Reset form
        $this->reset();

        // Notify
        session()->flash('success', 'Comic uploaded! Processing started.');
        return redirect()->route('admin.cms.comics.panels', $comic->id);
    }

    public function render()
    {
        return view('livewire.comic-uploader');
    }
}
