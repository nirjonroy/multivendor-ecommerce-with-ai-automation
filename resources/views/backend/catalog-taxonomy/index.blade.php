@extends('backend.layouts.app')

@section('title', $config['title'] . ' - Admin')
@section('page_title', $config['title'])

@section('page_actions')
    <a href="{{ route('admin.catalog.create', $resource) }}" class="btn btn-primary">Create {{ $config['singular'] }}</a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordernone">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        @if ($resource !== 'categories' && $resource !== 'brands')
                            <th>Parent</th>
                        @endif
                        <th>Owner</th>
                        <th>Status</th>
                        <th>Sort</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td>
                                @php($path = $item->{$config['image_field']})
                                @if ($path)
                                    <img class="table-img" src="{{ asset('storage/' . $path) }}" alt="{{ $item->name }}">
                                @endif
                            </td>
                            <td>{{ $item->name }}<br><small>{{ $item->slug }}</small></td>
                            @if ($resource !== 'categories' && $resource !== 'brands')
                                <td>{{ $item->category?->name }} @if ($resource === 'child-categories') / {{ $item->subCategory?->name }} @endif</td>
                            @endif
                            <td>{{ ucfirst($item->owner_type) }} @if($item->vendor) / {{ $item->vendor->name }} @endif</td>
                            <td>{{ $item->is_active ? 'Active' : 'Inactive' }}</td>
                            <td>{{ $item->sort_order }}</td>
                            <td class="text-right">
                                <a href="{{ route('admin.catalog.edit', [$resource, $item->id]) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form method="POST" action="{{ route('admin.catalog.destroy', [$resource, $item->id]) }}" class="d-inline" onsubmit="return confirm('Delete this item?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">No records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $items->links() }}
        </div>
    </div>
@endsection
