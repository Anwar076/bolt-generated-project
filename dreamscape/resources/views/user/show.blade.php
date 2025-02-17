@extends('layouts.app')

@section('content')
    <h1>{{ $user->username }}'s Profile</h1>

    <h2>Inventory</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Type</th>
                <th>Rarity</th>
            </tr>
        </thead>
        <tbody>
            @foreach($user->inventory as $inventoryItem)
                <tr>
                    <td>{{ $inventoryItem->item->name }}</td>
                    <td>{{ $inventoryItem->item->type }}</td>
                    <td>{{ $inventoryItem->item->rarity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
