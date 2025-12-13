<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ServerController extends Controller
{
    /**
     * List all servers
     */
    public function index(): View
    {
        $servers = Server::orderBy('name')->get();
        
        return view('servers.index', compact('servers'));
    }

    /**
     * Store a new server
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'connection' => 'nullable|string|max:255',
        ]);

        $server = Server::create([
            'name' => $validated['name'],
            'connection' => $validated['connection'] ?? null,
            'token' => Server::generateToken(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Server created successfully.',
            'server' => $server,
            'token' => $server->token, // Show token once on creation
        ]);
    }

    /**
     * Regenerate server token
     */
    public function regenerateToken(Server $server): JsonResponse
    {
        $server->update([
            'token' => Server::generateToken(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Token regenerated successfully.',
            'token' => $server->token,
        ]);
    }

    /**
     * Delete a server
     */
    public function destroy(Server $server): JsonResponse
    {
        $server->delete();

        return response()->json([
            'success' => true,
            'message' => 'Server deleted successfully.',
        ]);
    }
}
