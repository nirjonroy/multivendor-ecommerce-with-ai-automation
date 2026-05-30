@php($pendingProductRequests = $globalPendingVendorProductRequests ?? collect())

@if($pendingProductRequests->isNotEmpty())
    <div class="modal fade" id="vendorProductRequestModal" tabindex="-1" role="dialog" aria-labelledby="vendorProductRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="vendorProductRequestModalLabel">Vendor Product Approval Requests</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        New vendor products are waiting for admin review. Approved products will become visible on the frontend.
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordernone">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Vendor</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingProductRequests as $pendingProduct)
                                    <tr>
                                        <td>{{ $pendingProduct->name }}<br><small>{{ $pendingProduct->sku ?: 'No SKU' }}</small></td>
                                        <td>{{ $pendingProduct->vendor?->shop_name ?: $pendingProduct->vendor?->name }}</td>
                                        <td>{{ $pendingProduct->category?->name ?: 'Not set' }}<br><small>{{ $pendingProduct->brand?->name ?: 'No brand' }}</small></td>
                                        <td>{{ \App\Support\Currency::format($pendingProduct->offer_price ?: $pendingProduct->price, $globalSiteInfo) }}</td>
                                        <td class="text-right">
                                            <form class="d-inline" method="POST" action="{{ route('admin.products.approve', $pendingProduct) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button class="btn btn-sm btn-success" type="submit">Approve</button>
                                            </form>
                                            <button class="btn btn-sm btn-danger" type="button" data-toggle="collapse" data-target="#global-reject-product-{{ $pendingProduct->id }}">Reject</button>
                                            <form id="global-reject-product-{{ $pendingProduct->id }}" class="collapse mt-2" method="POST" action="{{ route('admin.products.reject', $pendingProduct) }}">
                                                @csrf
                                                @method('PATCH')
                                                <textarea class="form-control mb-2" name="approval_rejection_reason" rows="2" placeholder="Reject reason" required></textarea>
                                                <button class="btn btn-sm btn-danger" type="submit">Confirm Reject</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary">Open Product List</a>
                </div>
            </div>
        </div>
    </div>
@endif
