<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        // Show all items (for browsing the catalog)
        $items = Item::all();
        return response()->json($items);
    }

    public function show(Item $item)
    {
        return response()->json($item);
    }

    public function indexWeb()
    {
        $items = Item::all();
        return view('items.index', compact('items'));
    }

    // Admin-only functions (create, update, delete) will be in AdminController
}
