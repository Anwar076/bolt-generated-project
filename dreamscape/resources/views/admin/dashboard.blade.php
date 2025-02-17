@extends('layouts.app')

@section('content')
<h1>Admin Dashboard</h1>

<h2>Create User</h2>
<form method="POST" action="{{ route('admin.users.create') }}">
    @csrf
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
     <div class="mb-3">
        <label for="role" class="form-label">Role</label>
        <select class="form-select" id="role" name="role">
            <option value="Player">Player</option>
            <option value="Administrator">Administrator</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Create User</button>
</form>

<hr>

<h2>Manage Items</h2>
<a href="{{ route('admin.items.create') }}" class="btn btn-success mb-3">Create New Item</a>
<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->type }}</td>
                <td>
                    <a href="{{ route('admin.items.edit', $item->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('admin.items.destroy', $item->id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<hr>
<h2> Assign Item to User</h2>
<form action="{{route('admin.assign-item')}}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="user_id">User:</label>
        <select name="user_id" id="user_id" class="form-control">
            @foreach($users as $user)
                <option value="{{$user->id}}">{{$user->username}}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="item_id">Item:</label>
        <select name="item_id" id="item_id" class="form-control">
            @foreach($items as $item)
                <option value="{{$item->id}}">{{$item->name}}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Assign Item</button>
</form>

<hr>
<h2>Item Statistics</h2>
<form action="{{ route('admin.item-statistics') }}" method="GET">
    <div class="mb-3">
        <label for="item_type" class="form-label">Item Type:</label>
        <input type="text" class="form-control" id="item_type" name="type" required>
    </div>
    <button type="submit" class="btn btn-primary">Get Statistics</button>
</form>
@if(isset($itemType) && isset($itemCount))
    <p>Number of players owning item type "{{ $itemType }}": {{ $itemCount }}</p>
@endif

@endsection
