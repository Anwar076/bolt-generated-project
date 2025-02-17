@extends('layouts.app')

@section('content')
<h1>Edit Item</h1>
<form method="POST" action="{{ route('admin.items.update', $item->id) }}">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ $item->name }}" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" required>{{ $item->description }}</textarea>
    </div>
    <div class="mb-3">
        <label for="type" class="form-label">Type</label>
        <input type="text" class="form-control" id="type" name="type" value="{{ $item->type }}" required>
    </div>
    <div class="mb-3">
        <label for="rarity" class="form-label">Rarity (0-100)</label>
        <input type="number" class="form-control" id="rarity" name="rarity" min="0" max="100" value="{{ $item->rarity }}" required>
    </div>
    <div class="mb-3">
        <label for="power" class="form-label">Power (0-100)</label>
        <input type="number" class="form-control" id="power" name="power" min="0" max="100" value="{{ $item->power }}" required>
    </div>
    <div class="mb-3">
        <label for="speed" class="form-label">Speed (0-100)</label>
        <input type="number" class="form-control" id="speed" name="speed" min="0" max="100" value="{{ $item->speed }}" required>
    </div>
    <div class="mb-3">
        <label for="durability" class="form-label">Durability (0-100)</label>
        <input type="number" class="form-control" id="durability" name="durability" min="0" max="100" value="{{ $item->durability }}" required>
    </div>
    <div class="mb-3">
        <label for="magical_properties" class="form-label">Magical Properties (JSON)</label>
        <textarea class="form-control" id="magical_properties" name="magical_properties">{{ $item->magical_properties }}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">Update Item</button>
</form>
@endsection
