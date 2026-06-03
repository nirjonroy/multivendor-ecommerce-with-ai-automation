@extends('backend.layouts.app')

@section('title', 'Integration Settings')
@section('page_title', 'Integration Settings')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>n8n Cloud Webhooks</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.integrations.update') }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="n8n_product_webhook">Product Webhook URL</label>
                    <input
                        class="form-control"
                        id="n8n_product_webhook"
                        name="n8n_product_webhook"
                        type="url"
                        value="{{ old('n8n_product_webhook', $n8nProductWebhook) }}"
                        placeholder="https://your-workspace.app.n8n.cloud/webhook/product"
                    >
                    <small class="form-text text-muted">Called when an admin or vendor product is created. The callback URL is sent in the payload.</small>
                </div>

                <div class="form-group">
                    <label for="n8n_order_webhook">Order Webhook URL</label>
                    <input
                        class="form-control"
                        id="n8n_order_webhook"
                        name="n8n_order_webhook"
                        type="url"
                        value="{{ old('n8n_order_webhook', $n8nOrderWebhook) }}"
                        placeholder="https://your-workspace.app.n8n.cloud/webhook/order-check"
                    >
                    <small class="form-text text-muted">Called from the admin order details page when you click Check with n8n.</small>
                </div>

                <button class="btn btn-primary" type="submit">Save Integration Settings</button>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5>n8n Product Callback</h5>
        </div>
        <div class="card-body">
            <p class="mb-2">Use this callback URL in n8n after generating product descriptions:</p>
            <code>{{ url('/api/n8n/product-callback') }}</code>
            <hr>
            <p class="mb-0">Supported payload keys include <code>product_id</code>, <code>sku</code>, <code>slug</code>, <code>short_description</code>, <code>long_description</code>, and <code>description</code>.</p>
        </div>
    </div>
@endsection
