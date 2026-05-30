<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        $vendor = auth('vendor')->user();
        $products = Product::with(['category', 'brand'])
            ->where('owner_type', 'vendor')
            ->where('vendor_id', $vendor->id)
            ->latest()
            ->paginate(15);

        return view('vendor.products.index', compact('products', 'vendor'));
    }

    public function create()
    {
        if (! $this->canManageProducts()) {
            return redirect()->route('vendor.shop-settings.edit')
                ->withErrors('Your shop must be approved before adding products.');
        }

        return view('vendor.products.form', $this->formData());
    }

    public function store(Request $request)
    {
        if (! $this->canManageProducts()) {
            return redirect()->route('vendor.shop-settings.edit')
                ->withErrors('Your shop must be approved before adding products.');
        }

        $product = new Product();
        $this->fillAndSave($request, $product, $this->validated($request));

        return redirect()->route('vendor.products.index')->with('status', 'Product submitted for admin approval.');
    }

    public function edit(Product $product)
    {
        $this->authorizeVendorProduct($product);

        return view('vendor.products.form', $this->formData($product));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorizeVendorProduct($product);

        if (! $this->canManageProducts()) {
            return redirect()->route('vendor.shop-settings.edit')
                ->withErrors('Your shop must be approved before updating products.');
        }

        $this->fillAndSave($request, $product, $this->validated($request, $product->id));

        return redirect()->route('vendor.products.index')->with('status', 'Product updated and submitted for admin approval.');
    }

    public function destroy(Product $product)
    {
        $this->authorizeVendorProduct($product);

        if ($product->thumbnail_path) {
            Storage::disk('public')->delete($product->thumbnail_path);
        }

        foreach ($product->gallery_paths ?: [] as $path) {
            Storage::disk('public')->delete($path);
        }

        $product->delete();

        return redirect()->route('vendor.products.index')->with('status', 'Product deleted successfully.');
    }

    private function fillAndSave(Request $request, Product $product, array $data): void
    {
        $vendor = auth('vendor')->user();

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['owner_type'] = 'vendor';
        $data['vendor_id'] = $vendor->id;
        $data['approval_status'] = 'pending';
        $data['approval_rejection_reason'] = null;
        $data['approved_at'] = null;
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_new'] = $request->boolean('is_new');
        $data['is_on_sale'] = $request->boolean('is_on_sale');
        $data['sizes'] = collect($data['size_prices'] ?? [])->pluck('size')->filter()->values()->all() ?: null;
        $data['colors'] = collect($data['color_images'] ?? [])->pluck('color')->filter()->values()->all() ?: null;
        $data['has_variation_stock'] = $request->boolean('has_variation_stock');

        $colorImages = $data['color_images'] ?? [];
        foreach ($colorImages as $index => $colorRow) {
            $oldImage = $product->color_images[$index]['image_path'] ?? null;
            $colorImages[$index]['image_path'] = $oldImage;
            if ($request->hasFile("color_images.$index.image")) {
                if ($oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
                $colorImages[$index]['image_path'] = $request->file("color_images.$index.image")->store('products/colors', 'public');
            }
            unset($colorImages[$index]['image']);
        }
        $data['color_images'] = $colorImages ?: null;

        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail_path) {
                Storage::disk('public')->delete($product->thumbnail_path);
            }
            $data['thumbnail_path'] = $request->file('thumbnail')->store('products', 'public');
        }

        if ($request->hasFile('gallery')) {
            foreach ($product->gallery_paths ?: [] as $path) {
                Storage::disk('public')->delete($path);
            }
            $data['gallery_paths'] = collect($request->file('gallery'))
                ->map(fn ($file) => $file->store('products', 'public'))
                ->values()
                ->all();
        }

        unset($data['thumbnail'], $data['gallery']);
        $product->fill($data);
        $product->save();
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($ignoreId)],
            'sku' => ['nullable', 'string', 'max:100', Rule::unique('products', 'sku')->ignore($ignoreId)],
            'category_id' => ['required', 'exists:categories,id'],
            'sub_category_id' => ['nullable', 'exists:sub_categories,id'],
            'child_category_id' => ['nullable', 'exists:child_categories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:2048'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['image', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:2048'],
            'video_url' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'offer_price' => ['nullable', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'short_description' => ['nullable', 'string'],
            'long_description' => ['nullable', 'string'],
            'size_prices' => ['nullable', 'array'],
            'size_prices.*.size' => ['nullable', 'string', 'max:100'],
            'size_prices.*.price' => ['nullable', 'numeric', 'min:0'],
            'color_images' => ['nullable', 'array'],
            'color_images.*.color' => ['nullable', 'string', 'max:100'],
            'color_images.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:2048'],
            'variation_stocks' => ['nullable', 'array'],
            'variation_stocks.*.variation' => ['nullable', 'string', 'max:255'],
            'variation_stocks.*.quantity' => ['nullable', 'integer', 'min:0'],
            'has_variation_stock' => ['nullable', 'boolean'],
            'status' => ['required', Rule::in(['draft', 'published', 'inactive'])],
            'is_featured' => ['nullable', 'boolean'],
            'is_new' => ['nullable', 'boolean'],
            'is_on_sale' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
    }

    private function formData(?Product $product = null): array
    {
        $vendorId = auth('vendor')->id();
        $ownedOrGlobal = fn ($query) => $query
            ->where('owner_type', 'admin')
            ->orWhere(fn ($query) => $query->where('owner_type', 'vendor')->where('vendor_id', $vendorId));

        return [
            'product' => $product,
            'categories' => Category::query()->where($ownedOrGlobal)->where('is_active', true)->orderBy('name')->get(),
            'subCategories' => SubCategory::query()->where($ownedOrGlobal)->where('is_active', true)->orderBy('name')->get(),
            'childCategories' => ChildCategory::query()->where($ownedOrGlobal)->where('is_active', true)->orderBy('name')->get(),
            'brands' => Brand::query()->where($ownedOrGlobal)->where('is_active', true)->orderBy('name')->get(),
            'productSizes' => ProductSize::query()->where($ownedOrGlobal)->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get(),
            'productColors' => ProductColor::query()->where($ownedOrGlobal)->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get(),
        ];
    }

    private function authorizeVendorProduct(Product $product): void
    {
        abort_unless($product->owner_type === 'vendor' && $product->vendor_id === auth('vendor')->id(), 404);
    }

    private function canManageProducts(): bool
    {
        $vendor = auth('vendor')->user();

        return $vendor && $vendor->status === 'approved';
    }
}
