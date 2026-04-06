<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecordProgressEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'child_id' => 'required|exists:child_profiles,id',
            'event_type' => 'required|in:story_started,story_completed,panel_viewed,vocab_learned,badge_earned,exercise_completed',
            'tribe_id' => 'nullable|exists:tribes,id',
            'comic_id' => 'nullable|exists:comics,id',
            'panel_number' => 'nullable|integer|min:1',
            'duration_seconds' => 'nullable|integer|min:0',
            'score' => 'nullable|integer|min:0|max:100',
            'metadata' => 'nullable|json',
            'recorded_at' => 'nullable|date_format:Y-m-d H:i:s',
        ];
    }

    public function messages(): array
    {
        return [
            'child_id.required' => 'Child is required.',
            'child_id.exists' => 'Selected child does not exist.',
            'event_type.required' => 'Event type is required.',
            'event_type.in' => 'Invalid event type.',
        ];
    }
}
