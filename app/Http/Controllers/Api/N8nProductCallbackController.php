<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class N8nProductCallbackController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['nullable', 'integer'],
            'id' => ['nullable', 'integer'],
            'sku' => ['nullable', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'long_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'generated_description' => ['nullable', 'string'],
        ]);

        $product = $this->findProduct($data);

        if (! $product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found.',
            ], 404);
        }

        $updates = [];

        if (array_key_exists('short_description', $data)) {
            $updates['short_description'] = $data['short_description'];
        }

        $longDescription = $data['long_description']
            ?? $data['generated_description']
            ?? $data['description']
            ?? null;

        if ($longDescription !== null) {
            $updates['long_description'] = $longDescription;
        }

        if (! $updates) {
            return response()->json([
                'status' => 'error',
                'message' => 'No description fields were provided.',
            ], 422);
        }

        $product->update($updates);

        return response()->json([
            'status' => 'success',
            'message' => 'Product description updated.',
            'product_id' => $product->id,
        ]);
    }

    private function findProduct(array $data): ?Product
    {
        $id = $data['product_id'] ?? $data['id'] ?? null;

        if ($id) {
            return Product::find($id);
        }

        if (! empty($data['sku'])) {
            return Product::where('sku', $data['sku'])->first();
        }

        if (! empty($data['slug'])) {
            return Product::where('slug', $data['slug'])->first();
        }

        return null;
    }
}
