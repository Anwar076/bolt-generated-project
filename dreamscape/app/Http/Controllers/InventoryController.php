<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index()
    {
        // Show the current user's inventory
        $user = Auth::user();
        $inventory = $user->inventory()->with('item')->get(); // Load item details
        return response()->json($inventory);
    }

    public function indexWeb()
    {
        $user = Auth::user();
        $inventory = $user->inventory()->with('item')->get();
        return view('inventory.index', compact('inventory'));
    }
}
