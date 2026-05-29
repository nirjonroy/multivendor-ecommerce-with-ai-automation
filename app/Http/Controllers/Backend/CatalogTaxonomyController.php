<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\SubCategory;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CatalogTaxonomyController extends Controller
{
    private array $resources = [
        'categories' => [
            'model' => Category::class,
            'title' => 'Categories',
            'singular' => 'Category',
            'image_field' => 'image_path',
            'upload_input' => 'image',
        ],
        'sub-categories' => [
            'model' => SubCategory::class,
            'title' => 'Sub Categories',
            'singular' => 'Sub Category',
            'image_field' => 'image_path',
            'upload_input' => 'image',
        ],
        'child-categories' => [
            'model' => ChildCategory::class,
            'title' => 'Child Categories',
            'singular' => 'Child Category',
            'image_field' => 'image_path',
            'upload_input' => 'image',
        ],
        'brands' => [
            'model' => Brand::class,
            'title' => 'Brands',
            'singular' => 'Brand',
            'image_field' => 'logo_path',
            'upload_input' => 'logo',
        ],
    ];

    public function index(string $resource)
    {
        $config = $this->config($resource);
        $query = $config['model']::query()->latest();

        if ($resource === 'sub-categories') {
            $query->with('category');
        }

        if ($resource === 'child-categories') {
            $query->with(['category', 'subCategory']);
        }

        $items = $query->paginate(15);

        return view('backend.catalog-taxonomy.index', [
            'resource' => $resource,
            'config' => $config,
            'items' => $items,
        ]);
    }

    public function create(string $resource)
    {
        return view('backend.catalog-taxonomy.form', $this->formData($resource));
    }

    public function store(Request $request, string $resource)
    {
        $config = $this->config($resource);
        $data = $this->validatedData($request, $resource);

        $item = new $config['model']();
        $this->fillAndSave($request, $item, $config, $data);

        return redirect()
            ->route('admin.catalog.index', $resource)
            ->with('status', $config['singular'] . ' created successfully.');
    }

    public function edit(string $resource, int $id)
    {
        $config = $this->config($resource);
        $item = $config['model']::query()->findOrFail($id);

        return view('backend.catalog-taxonomy.form', $this->formData($resource, $item));
    }

    public function update(Request $request, string $resource, int $id)
    {
        $config = $this->config($resource);
        $item = $config['model']::query()->findOrFail($id);
        $data = $this->validatedData($request, $resource, $item->id);

        $this->fillAndSave($request, $item, $config, $data);

        return redirect()
            ->route('admin.catalog.index', $resource)
            ->with('status', $config['singular'] . ' updated successfully.');
    }

    public function destroy(string $resource, int $id)
    {
        $config = $this->config($resource);
        $item = $config['model']::query()->findOrFail($id);

        if (! empty($item->{$config['image_field']})) {
            Storage::disk('public')->delete($item->{$config['image_field']});
        }

        $item->delete();

        return redirect()
            ->route('admin.catalog.index', $resource)
            ->with('status', $config['singular'] . ' deleted successfully.');
    }

    private function fillAndSave(Request $request, Model $item, array $config, array $data): void
    {
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['vendor_id'] = $data['owner_type'] === 'vendor' ? ($data['vendor_id'] ?? null) : null;
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile($config['upload_input'])) {
            if (! empty($item->{$config['image_field']})) {
                Storage::disk('public')->delete($item->{$config['image_field']});
            }

            $data[$config['image_field']] = $request->file($config['upload_input'])->store('catalog', 'public');
        }

        unset($data[$config['upload_input']]);

        $item->fill($data);
        $item->save();
    }

    private function validatedData(Request $request, string $resource, ?int $ignoreId = null): array
    {
        $config = $this->config($resource);
        $table = (new $config['model']())->getTable();
        $uploadInput = $config['upload_input'];

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique($table, 'slug')->ignore($ignoreId)],
            'description' => ['nullable', 'string'],
            'owner_type' => ['required', Rule::in(['admin', 'vendor'])],
            'vendor_id' => ['nullable', 'required_if:owner_type,vendor', 'exists:vendors,id'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            $uploadInput => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:2048'],
        ];

        if ($resource === 'sub-categories') {
            $rules['category_id'] = ['required', 'exists:categories,id'];
        }

        if ($resource === 'child-categories') {
            $rules['category_id'] = ['required', 'exists:categories,id'];
            $rules['sub_category_id'] = ['required', 'exists:sub_categories,id'];
        }

        return $request->validate($rules);
    }

    private function formData(string $resource, ?Model $item = null): array
    {
        return [
            'resource' => $resource,
            'config' => $this->config($resource),
            'item' => $item,
            'categories' => Category::query()->orderBy('name')->get(),
            'subCategories' => SubCategory::query()->orderBy('name')->get(),
            'vendors' => Vendor::query()->orderBy('name')->get(),
        ];
    }

    private function config(string $resource): array
    {
        abort_unless(isset($this->resources[$resource]), 404);

        return $this->resources[$resource];
    }
}
