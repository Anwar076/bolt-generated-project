@extends('layouts.app')

@section('content')
<h1>Trades</h1>

<h2>Outgoing Trade Requests</h2>
<table class="table">
    <thead>
        <tr>
            <th>To User</th>
            <th>Your Item</th>
            <th>Their Item</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sentTrades as $trade)
            <tr>
                <td>{{ $trade->receiver->username }}</td>
                <td>{{ $trade->senderItem->name }}</td>
                <td>{{ $trade->receiverItem ? $trade->receiverItem->name : 'N/A' }}</td>
                <td>{{ $trade->status }}</td>
                <td>
                    @if($trade->status == 'pending')
                        <form action="{{ route('trades.destroy', $trade->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Cancel</button>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<h2>Incoming Trade Requests</h2>
<table class="table">
    <thead>
        <tr>
            <th>From User</th>
            <th>Their Item</th>
            <th>Your Item</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($receivedTrades as $trade)
            <tr>
                <td>{{ $trade->sender->username }}</td>
                <td>{{ $trade->senderItem->name }}</td>
                <td>{{ $trade->receiverItem ? $trade->receiverItem->name : 'N/A' }}</td>
                <td>{{ $trade->status }}</td>
                <td>
                    @if($trade->status == 'pending')
                        <form action="{{ route('trades.update', $trade->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="accepted">
                            <button type="submit" class="btn btn-success">Accept</button>
                        </form>
                        <form action="{{ route('trades.update', $trade->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="declined">
                            <button type="submit" class="btn btn-danger">Decline</button>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<h2>Create Trade Request</h2>
<form method="POST" action="{{ route('trades.store') }}">
    @csrf
    <div class="mb-3">
        <label for="receiver_id" class="form-label">Recipient User:</label>
        <select class="form-select" id="receiver_id" name="receiver_id" required>
            <option value="">Select a user</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->username }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label for="sender_item_id" class="form-label">Your Item:</label>
        <select class="form-select" id="sender_item_id" name="sender_item_id" required>
            <option value="">Select an item</option>
            @foreach(Auth::user()->inventory as $invItem)
                <option value="{{ $invItem->item->id }}">{{ $invItem->item->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label for="receiver_item_id" class="form-label">Their Item (Optional):</label>
        <select class="form-select" id="receiver_item_id" name="receiver_item_id">
            <option value="">None</option>
            <!-- Options will be populated dynamically via JavaScript -->
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Send Trade Request</button>
</form>

<!-- JavaScript for dynamic dropdown -->
<script>
    const receiverSelect = document.getElementById('receiver_id');
    const receiverItemSelect = document.getElementById('receiver_item_id');

    receiverSelect.addEventListener('change', async () => {
        const receiverId = receiverSelect.value;
        receiverItemSelect.innerHTML = '<option value="">None</option>'; // Clear previous options

        if (receiverId) {
            try {
                const response = await fetch(`/api/user/${receiverId}`);
                const user = await response.json();

                if (user &amp;&amp; user.inventory) {
                    user.inventory.forEach(invItem => {
                        const option = document.createElement('option');
                        option.value = invItem.item.id;
                        option.textContent = invItem.item.name;
                        receiverItemSelect.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error fetching user inventory:', error);
            }
        }
    });
</script>
@endsection
