<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\View\View;

class StaffController extends Controller
{
    /**
     * List all staff members
     */
    public function index(): View
    {
        $staff = Staff::where('role', '!=', 'user')
            ->orderByRaw("FIELD(role, 'owner', 'admin', 'moderator', 'trial')")
            ->get();

        // Get action counts for each staff member
        $staff->each(function ($member) {
            $member->bans_count = \App\Models\Ban::where('banned_by_discord_id', $member->discord_id)->count();
            $member->kicks_count = \App\Models\Kick::where('kicked_by_discord_id', $member->discord_id)->count();
            $member->warnings_count = \App\Models\Warning::where('warned_by_discord_id', $member->discord_id)->count();
        });

        return view('staff.index', compact('staff'));
    }
}
