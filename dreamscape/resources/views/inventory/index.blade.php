@extends('layouts.app')

@section('content')
<h1>My Inventory</h1>
<table class="table">
    <thead>
        <tr>
            <th>Item Name</th>
            <th>Type</th>
            <th>Rarity</th>
            <th>Power</th>
            <th>Speed</th>
            <th>Durability</th>
            <th></th> <!---For actions like "use" or "trade"-->
        </tr>
    </thead>
    <tbody>
        @foreach($inventory as $invItem)
            <tr>
                <td>{{ $invItem->item->name }}</td>
                <td>{{ $invItem->item->type }}</td>
                <td>{{ $invItem->item->rarity }}</td>
                <td>{{ $invItem->item->power }}</td>
                <td>{{ $invItem->item->speed }}</td>
                <td>{{ $invItem->item->durability }}</td>
                <td>
                    <!---Add buttons/forms for actions here-->
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
