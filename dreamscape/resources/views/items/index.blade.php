@extends('layouts.app')

@section('content')
<h1>Item Catalog</h1>
<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Type</th>
            <th>Rarity</th>
            <th>Power</th>
            <th>Speed</th>
            <th>Durability</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->description }}</td>
                <td>{{ $item->type }}</td>
                <td>{{ $item->rarity }}</td>
                <td>{{ $item->power }}</td>
                <td>{{ $item->speed }}</td>
                <td>{{ $item->durability }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
