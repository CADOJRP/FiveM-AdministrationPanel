<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    /**
     * Show settings page
     */
    public function index(): View
    {
        $settings = DB::table('config')->pluck('value', 'key')->toArray();

        return view('settings.index', compact('settings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'community_name' => 'required|string|max:255',
            'discord_webhook' => 'nullable|url|max:500',
            'trust_score_base' => 'required|integer|min:0|max:100',
            'trust_score_warn_penalty' => 'required|integer|min:0|max:50',
            'trust_score_kick_penalty' => 'required|integer|min:0|max:50',
            'trust_score_ban_penalty' => 'required|integer|min:0|max:50',
            'trust_score_commend_bonus' => 'required|integer|min:0|max:50',
        ]);

        foreach ($validated as $key => $value) {
            DB::table('config')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'created_at' => now(), 'updated_at' => now()]
            );
        }

        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}
