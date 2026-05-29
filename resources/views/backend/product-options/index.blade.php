@extends('backend.layouts.app')

@section('title', $config['title'])
@section('page_title', $config['title'])

@section('page_actions')
    <a class="btn btn-primary" href="{{ route('admin.product-options.create', $resource) }}">Create {{ $config['singular'] }}</a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        @if($resource === 'colors')
                            <th>Color</th>
                        @endif
                        <th>Owner</th>
                        <th>Status</th>
                        <th>Sort</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $item->name }}<br><small>{{ $item->slug }}</small></td>
                            @if($resource === 'colors')
                                <td><span style="display:inline-block;width:24px;height:24px;background:{{ $item->hex_code ?: '#eee' }};border:1px solid #ccc"></span> {{ $item->hex_code }}</td>
                            @endif
                            <td>{{ ucfirst($item->owner_type) }}</td>
                            <td>{{ $item->is_active ? 'Active' : 'Inactive' }}</td>
                            <td>{{ $item->sort_order }}</td>
                            <td class="text-right">
                                <a class="btn btn-sm btn-primary" href="{{ route('admin.product-options.edit', [$resource, $item->id]) }}">Edit</a>
                                <form class="d-inline" method="POST" action="{{ route('admin.product-options.destroy', [$resource, $item->id]) }}" onsubmit="return confirm('Delete this item?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="{{ $resource === 'colors' ? 6 : 5 }}" class="text-center">No {{ strtolower($config['title']) }} found.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $items->links() }}
        </div>
    </div>
@endsection
