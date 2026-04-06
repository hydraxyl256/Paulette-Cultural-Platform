<?php

namespace App\Http\Controllers\Admin;

use App\Models\AuditLog;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SuperAdminController
{
    /**
     * GET /admin/dashboard
     * Global platform stats (all orgs)
     */
    public function dashboard(): JsonResponse
    {
        return response()->json([
            'active_children' => \App\Models\ChildProfile::count(),
            'organisations' => Organisation::count(),
            'published_comics' => \App\Models\Comic::where('status', 'published')->count(),
            'badges_earned' => \App\Models\ProgressEvent::where('event_type', 'badge_earned')->whereBetween('created_at', [now()->subDays(7), now()])->count(),
            'this_week_growth' => $this->getWeeklyGrowth(),
        ]);
    }

    /** 
     * GET /admin/organisations
     * List all organisations
     */
    public function organisations(): JsonResponse
    {
        $orgs = Organisation::with('users', 'childProfiles')
            ->get()
            ->map(fn($org) => [
                'id' => $org->id,
                'name' => $org->name,
                'slug' => $org->slug,
                'plan' => $org->plan,
                'users_count' => $org->users->count(),
                'children_count' => $org->childProfiles->count(),
                'is_active' => $org->is_active,
            ]);

        return response()->json($orgs);
    }

    /**
     * POST /admin/organisations
     * Create organisation
     */
    public function createOrganisation(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:100|unique:organisations,slug',
            'plan' => 'required|in:free,school,enterprise',
        ]);

        $org = Organisation::create($validated);

        return response()->json($org, 201);
    }

    /**
     * PUT /admin/organisations/{id}/modules
     * Toggle org modules
     */
    public function updateOrgModules(Organisation $org, Request $request): JsonResponse
    {
        $validated = $request->validate([
            'modules' => 'required|array',
        ]);

        $org->update(['modules' => $validated['modules']]);

        AuditLog::record(auth()->id(), 'update_org_modules', $validated, $org->id, 'Organisation');

        return response()->json($org);
    }

    /**
     * PUT /admin/age-profiles/{id}
     * Edit age profile rules
     */
    public function updateAgeProfile(\App\Models\AgeProfile $profile, Request $request): JsonResponse
    {
        $validated = $request->validate([
            'stage' => 'required|string|max:50',
            'ui_mode' => 'required|in:simple,guided,advanced,full',
            'difficulty_ceiling' => 'required|integer|min:1|max:10',
            'rules' => 'nullable|array',
        ]);

        $profile->update($validated);

        AuditLog::record(auth()->id(), 'update_age_profile', $validated, $profile->id, 'AgeProfile');

        return response()->json($profile);
    }

    /**
     * PUT /admin/themes/{org_id}
     * Update org theme config
     */
    public function updateTheme(Organisation $org, Request $request): JsonResponse
    {
        $validated = $request->validate([
            'colors' => 'nullable|array',
            'typography' => 'nullable|array',
            'logo_url' => 'nullable|string|url',
            'custom_properties' => 'nullable|array',
        ]);

        $theme = $org->themeConfig() ?? new \App\Models\ThemeConfig(['org_id' => $org->id]);
        $theme->fill($validated)->save();

        AuditLog::record(auth()->id(), 'update_theme', $validated, $org->id, 'Organisation');

        return response()->json($theme);
    }

    /**
     * POST /admin/users/{id}/impersonate
     * Issue impersonation token
     */
    public function impersonate(User $user): JsonResponse
    {
        $token = $user->createToken('impersonation', ['impersonated']);

        AuditLog::record(auth()->id(), 'impersonate', null, $user->id, 'User', auth()->id());

        return response()->json([
            'token' => $token->plainTextToken,
            'user' => $user,
        ]);
    }

    private function getWeeklyGrowth(): int
    {
        $startDate = now()->subDays(7);
        $oldCount = \App\Models\ChildProfile::where('created_at', '<', $startDate)->count();
        $newCount = \App\Models\ChildProfile::where('created_at', '>=', $startDate)->count();

        return $oldCount > 0 ? (int)(($newCount / $oldCount) * 100) : 0;
    }
}
