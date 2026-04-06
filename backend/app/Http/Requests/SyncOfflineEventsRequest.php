<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SyncOfflineEventsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'events' => 'required|array|min:1|max:100',
            'events.*.child_id' => 'required|exists:child_profiles,id',
            'events.*.event_type' => 'required|in:story_started,story_completed,panel_viewed,vocab_learned,badge_earned,exercise_completed',
            'events.*.tribe_id' => 'nullable|exists:tribes,id',
            'events.*.comic_id' => 'nullable|exists:comics,id',
            'events.*.panel_number' => 'nullable|integer|min:1',
            'events.*.duration_seconds' => 'nullable|integer|min:0',
            'events.*.score' => 'nullable|integer|min:0|max:100',
            'events.*.metadata' => 'nullable|json',
            'events.*.recorded_at' => 'nullable|date_format:Y-m-d H:i:s',
            'events.*.idempotency_key' => 'nullable|string|max:255|unique:progress_events,idempotency_key',
        ];
    }

    public function messages(): array
    {
        return [
            'events.required' => 'Events array is required.',
            'events.max' => 'Maximum 100 events per request.',
            'events.*.child_id.required' => 'Child ID is required for each event.',
            'events.*.event_type.required' => 'Event type is required for each event.',
        ];
    }
}
