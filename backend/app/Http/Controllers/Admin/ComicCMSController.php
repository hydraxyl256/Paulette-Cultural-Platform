<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\ProcessComicPDF;
use App\Jobs\BuildOfflineBundle;
use App\Models\Comic;
use App\Models\ComicPanel;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;

class ComicCMSController
{
    /**
     * Show comics list
     */
    public function index(): View
    {
        $comics = Comic::with('organisation')->paginate(20);
        return view('admin.cms.comics.index', compact('comics'));
    }

    /**
     * Show create comic form
     */
    public function create(): View
    {
        return view('admin.cms.comics.create');
    }

    /**
     * Store comic (upload PDF) - Web form
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'tribe_id' => 'nullable|exists:tribes,id',
            'age_profile_id' => 'nullable|exists:age_profiles,id',
            'pdf_file' => 'required|file|mimes:pdf|max:50000', // 50MB
        ]);

        // Create comic record (status='draft')
        $comic = Comic::create([
            'org_id' => auth()->user()->org_id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'tribe_id' => $validated['tribe_id'],
            'age_profile_id' => $validated['age_profile_id'],
            'status' => 'draft',
        ]);

        // Store PDF temporarily
        /** @var UploadedFile $pdf */
        $pdf = $validated['pdf_file'];
        $pdfPath = $pdf->store('comics/uploads', 'private');

        // Dispatch PDF processing job
        ProcessComicPDF::dispatch($comic, $pdfPath);

        return redirect()
            ->route('admin.cms.comics.panels', $comic->id)
            ->with('success', 'Comic uploaded. Processing PDF...');
    }

    /**
     * Show comic edit form
     */
    public function edit(int $id): View
    {
        $comic = Comic::findOrFail($id);
        $this->authorize('update', $comic);
        return view('admin.cms.comics.edit', compact('comic'));
    }

    /**
     * Update comic metadata
     */
    public function update(int $id): RedirectResponse
    {
        $comic = Comic::findOrFail($id);
        $this->authorize('update', $comic);

        $validated = request()->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'tribe_id' => 'nullable|exists:tribes,id',
            'age_profile_id' => 'nullable|exists:age_profiles,id',
        ]);

        $comic->update($validated);

        return back()->with('success', 'Comic updated.');
    }

    /**
     * Manage panels for comic (edit, tag, order)
     */
    public function managePanels(int $id): View
    {
        $comic = Comic::findOrFail($id);
        $this->authorize('update', $comic);

        $panels = $comic->panels()->orderBy('panel_number')->get();

        return view('admin.cms.comics.panels', [
            'comic' => $comic,
            'panels' => $panels,
        ]);
    }

    /**
     * Update panel (tags, vocab, audio) - API endpoint
     */
    public function updatePanel(int $id): JsonResponse
    {
        $panel = ComicPanel::findOrFail($id);
        
        $validated = request()->validate([
            'vocab_tags' => 'nullable|json',
            'audio_path' => 'nullable|string',
            'transcript' => 'nullable|string|max:1000',
            'learning_objective' => 'nullable|string|max:500',
        ]);

        $panel->update($validated);

        return response()->json(['message' => 'Panel updated', 'panel' => $panel]);
    }

    /**
     * Publish comic (trigger bundle generation)
     */
    public function publish(int $id): RedirectResponse
    {
        $comic = Comic::findOrFail($id);
        $this->authorize('update', $comic);

        // Verify all panels are ready
        if ($comic->panels()->count() === 0) {
            return back()->with('error', 'Comic must have at least one panel.');
        }

        // Update status
        $comic->update(['status' => 'published']);

        // Dispatch bundle generation job
        BuildOfflineBundle::dispatch($comic);

        return back()->with('success', 'Comic published. Generating offline bundle...');
    }

    /**
     * Existing API method for PDF upload
     */
    public function upload(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'tribe_id' => 'required|integer|exists:tribes,id',
            'age_min' => 'required|integer|min:2|max:6',
            'age_max' => 'required|integer|min:2|max:6',
            'pdf_file' => 'required|file|mimes:pdf|max:50000',
        ]);

        // Store PDF
        $pdfPath = $request->file('pdf_file')->store('comics/raw', 's3');

        // Create comic record
        $comic = Comic::create([
            'org_id' => auth()->user()->org_id,
            'tribe_id' => $validated['tribe_id'],
            'title' => $validated['title'],
            'age_min' => $validated['age_min'],
            'age_max' => $validated['age_max'],
            'status' => 'draft',
            'cover_image_path' => null,
        ]);

        // Dispatch PDF processing job
        ProcessComicPDF::dispatch($pdfPath, $comic->id, auth()->user()->id);

        return response()->json([
            'message' => 'Comic uploaded. Processing started.',
            'comic' => $comic,
        ], 201);
    }

    /**
     * API method for publishing
     */
    public function publishApi(Comic $comic, Request $request): JsonResponse
    {
        $this->authorize('update', $comic);

        $validated = $request->validate([
            'cover_image_path' => 'nullable|string',
        ]);

        $comic->update([
            'status' => 'published',
            'cover_image_path' => $validated['cover_image_path'] ?? $comic->cover_image_path,
        ]);

        // Dispatch bundle builder job
        BuildOfflineBundle::dispatch($comic->id);

        return response()->json([
            'message' => 'Comic published. Bundle building started.',
            'comic' => $comic,
        ]);
    }
}
