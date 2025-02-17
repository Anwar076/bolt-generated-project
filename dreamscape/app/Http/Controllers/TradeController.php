<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use App\Models\Inventory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TradeController extends Controller
{
    public function index()
    {
        // Show pending trades for the current user (both sent and received)
        $user = Auth::user();
        $sentTrades = $user->sentTrades()->with(['receiver', 'senderItem', 'receiverItem'])->where('status', 'pending')->get();
        $receivedTrades = $user->receivedTrades()->with(['sender', 'senderItem', 'receiverItem'])->where('status', 'pending')->get();

        return response()->json(['sent' => $sentTrades, 'received' => $receivedTrades]);
    }

    public function store(Request $request)
    {
        // Create a new trade request
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'sender_item_id' => 'required|exists:items,id',
            'receiver_item_id' => 'nullable|exists:items,id', // Receiver's item is optional
        ]);

        $sender = Auth::user();
        $receiver = \App\Models\User::findOrFail($request->receiver_id);

        // Check if sender owns the item
        $senderInventory = Inventory::where('user_id', $sender->id)->where('item_id', $request->sender_item_id)->firstOrFail();

        // Check if receiver owns the item (if provided)
        if ($request->receiver_item_id) {
            $receiverInventory = Inventory::where('user_id', $receiver->id)->where('item_id', $request->receiver_item_id)->firstOrFail();
        }


        $trade = Trade::create([
            'sender_id' => $sender->id,
            'receiver_id' => $request->receiver_id,
            'sender_item_id' => $request->sender_item_id,
            'receiver_item_id' => $request->receiver_item_id,
        ]);

        return response()->json(['message' => 'Trade request sent', 'trade' => $trade], 201);
    }
    public function update(Request $request, Trade $trade)
    {
        // Accept or decline a trade
        $request->validate([
            'status' => 'required|in:accepted,declined',
        ]);

        if ($trade->receiver_id != Auth::id() || $trade->status != 'pending') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $trade->status = $request->status;
        $trade->save();

        if ($trade->status == 'accepted') {
            // Transfer items
            Inventory::where('user_id', $trade->sender_id)->where('item_id', $trade->sender_item_id)->delete();
            Inventory::create(['user_id' => $trade->receiver_id, 'item_id' => $trade->sender_item_id]);

            if ($trade->receiver_item_id) {
                Inventory::where('user_id', $trade->receiver_id)->where('item_id', $trade->receiver_item_id)->delete();
                Inventory::create(['user_id' => $trade->sender_id, 'item_id' => $trade->receiver_item_id]);
            }
        }

        return response()->json(['message' => 'Trade ' . $trade->status, 'trade' => $trade]);
    }

    public function destroy(Trade $trade)
    {
        // Cancel trade (only sender and if still pending)

        if($trade->sender_id != Auth::id() || $trade->status != 'pending'){
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $trade->delete();
        return response()->json(['message' => 'Trade cancelled']);
    }

    public function indexWeb(Request $request)
    {
        $user = Auth::user();
        $sentTrades = $user->sentTrades()->with(['receiver', 'senderItem', 'receiverItem'])->where('status', 'pending')->get();
        $receivedTrades = $user->receivedTrades()->with(['sender', 'senderItem', 'receiverItem'])->where('status', 'pending')->get();
        $users = User::where('id', '!=', Auth::id())->get(); //for trade creation dropdown

        return view('trades.index', compact('sentTrades', 'receivedTrades', 'users'));
    }
}
