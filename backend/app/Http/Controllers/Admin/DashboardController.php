<?php

namespace App\Http\Controllers\Admin;

use App\Models\AgeProfile;
use App\Models\AuditLog;
use App\Models\Comic;
use App\Models\Organisation; 
use App\Models\ProgressEvent;
use App\Models\ThemeConfig;
use App\Models\User;
use App\Models\ChildProfile;
use Illuminate\View\View;

class DashboardController
{
    /**
     * Show admin dashboard
     */
    public function index(): View
    {
        $stats = [
            'organisations_count' => Organisation::count(),
            'users_count' => User::count(),
            'comics_count' => Comic::count(),
            'active_children' => ChildProfile::count(),
            'total_progress_events' => ProgressEvent::count(),
            'badges_earned_week' => ProgressEvent::where('event_type', 'badge_earned')
                ->where('created_at', '>=', now()->subDays(7))
                ->count(),
            'stories_published' => Comic::where('status', 'published')->count(),
        ];

        $organisations = Organisation::with(['users', 'childProfiles'])
            ->get()
            ->map(function ($org) {
                return [
                    'id' => $org->id,
                    'name' => $org->name,
                    'plan' => $org->plan,
                    'users_count' => $org->users->count(),
                    'children_count' => $org->childProfiles->count(),
                    'is_active' => $org->is_active,
                ];
            });

        $recentAuditLogs = AuditLog::latest()->take(20)->get();
        
        $topOrganisations = Organisation::withCount('childProfiles')
            ->orderByDesc('child_profiles_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'organisations' => $organisations,
            'recentAuditLogs' => $recentAuditLogs,
            'topOrganisations' => $topOrganisations,
        ])->layout('layouts.dashboard', [
            'header_title' => '⚡ Super Admin Dashboard',
            'header_subtitle' => 'Global system overview and controls',
        ]);
    }

    /**
     * Show age profiles management
     */
    public function ageProfiles(): View
    {
        $ageProfiles = AgeProfile::all();
        return view('admin.age-profiles', compact('ageProfiles'));
    }

    /**
     * Update age profile
     */
    public function updateAgeProfile(int $id): \Illuminate\Http\RedirectResponse
    {
        $ageProfile = AgeProfile::findOrFail($id);
        
        $validated = request()->validate([
            'ui_mode' => 'required|in:buttons,swipe,voice',
            'difficulty' => 'required|in:easy,medium,hard',
            'features_enabled' => 'required|json',
        ]);

        $ageProfile->update($validated);

        return back()->with('success', 'Age profile updated.');
    }

    /**
     * Show themes management
     */
    public function themes(): View
    {
        $themes = ThemeConfig::all();
        return view('admin.themes', compact('themes'));
    }

    /**
     * Update theme
     */
    public function updateTheme(int $id): \Illuminate\Http\RedirectResponse
    {
        $theme = ThemeConfig::findOrFail($id);

        $validated = request()->validate([
            'primary_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'secondary_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'logo_path' => 'nullable|string',
            'font_family' => 'nullable|string',
        ]);

        $theme->update($validated);

        return back()->with('success', 'Theme updated.');
    }

    /**
     * Show audit logs
     */
    public function auditLogs(): View
    {
        $logs = AuditLog::latest()->paginate(50);
        return view('admin.audit-logs', compact('logs'));
    }
}
