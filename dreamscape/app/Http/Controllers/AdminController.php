<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function createUser(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users',
            'password' => 'required|string|min:6',
            'role'     => 'sometimes|in:Player,Administrator'
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role'     => $request->role ? $request->role : 'Player',
        ]);

        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }

    public function createItem(Request $request)
    {
        $request->validate([
            'name'               => 'required|string',
            'description'        => 'required|string',
            'type'               => 'required|string',
            'rarity'             => 'required|integer|min:0|max:100',
            'power'              => 'required|integer|min:0|max:100',
            'speed'              => 'required|integer|min:0|max:100',
            'durability'         => 'required|integer|min:0|max:100',
            'magical_properties' => 'nullable|string',
        ]);

        $item = Item::create($request->all());
        return response()->json(['message' => 'Item created successfully', 'item' => $item], 201);
    }

    public function updateItem(Request $request, Item $item)
    {
        $request->validate([
            'name'               => 'sometimes|required|string',
            'description'        => 'sometimes|required|string',
            'type'               => 'sometimes|required|string',
            'rarity'             => 'sometimes|required|integer|min:0|max:100',
            'power'              => 'sometimes|required|integer|min:0|max:100',
            'speed'              => 'sometimes|required|integer|min:0|max:100',
            'durability'         => 'sometimes|required|integer|min:0|max:100',
            'magical_properties' => 'nullable|string',
        ]);

        $item->update($request->all());
        return response()->json(['message' => 'Item updated successfully', 'item' => $item]);
    }

    public function deleteItem(Item $item)
    {
        $item->delete();
        return response()->json(['message' => 'Item deleted successfully']);
    }

    public function assignItem(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'item_id' => 'required|exists:items,id',
        ]);

        // Check for duplicates
        $existing = Inventory::where('user_id', $request->user_id)->where('item_id', $request->item_id)->first();
        if ($existing) {
            return response()->json(['message' => 'User already has this item'], 409); // Conflict
        }

        $inventory = Inventory::create($request->only(['user_id', 'item_id']));
        return response()->json(['message' => 'Item assigned successfully', 'inventory' => $inventory], 201);
    }

    public function itemStatistics(Request $request)
    {
      $request->validate([
        'type' => 'required|string'
      ]);

      $count = Inventory::whereHas('item', function($query) use ($request) {
        $query->where('type', $request->type);
      })->count();

      return response()->json(['type' => $request->type, 'count' => $count]);
    }

    public function dashboard()
    {
        $items = Item::all();
        $users = User::all();
        return view('admin.dashboard', compact('items', 'users'));
    }

    public function createItemView()
    {
        return view('admin.items.create');
    }

    public function editItemView(Item $item)
    {
        return view('admin.items.edit', compact('item'));
    }
    public function itemStatisticsWeb(Request $request){
        $itemType = $request->input('type');
        $itemCount = null;

        if($itemType){
            $itemCount = Inventory::whereHas('item', function($query) use ($itemType){
                $query->where('type', $itemType);
            })->count();
        }
        return view('admin.dashboard', ['items' => Item::all(), 'users' => User::all(), 'itemType' => $itemType, 'itemCount' => $itemCount]);
    }
}
