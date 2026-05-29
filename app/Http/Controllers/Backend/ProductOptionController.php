<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductOptionController extends Controller
{
    private array $resources = [
        'sizes' => ['model' => ProductSize::class, 'title' => 'Sizes', 'singular' => 'Size'],
        'colors' => ['model' => ProductColor::class, 'title' => 'Colors', 'singular' => 'Color'],
    ];

    public function index(string $resource)
    {
        $config = $this->config($resource);
        $items = $config['model']::query()->latest()->paginate(15);

        return view('backend.product-options.index', compact('resource', 'config', 'items'));
    }

    public function create(string $resource)
    {
        return view('backend.product-options.form', [
            'resource' => $resource,
            'config' => $this->config($resource),
            'item' => null,
            'vendors' => Vendor::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request, string $resource)
    {
        $config = $this->config($resource);
        $item = new $config['model']();
        $this->fillAndSave($request, $resource, $item);

        return redirect()->route('admin.product-options.index', $resource)->with('status', $config['singular'] . ' created successfully.');
    }

    public function edit(string $resource, int $id)
    {
        $config = $this->config($resource);
        $item = $config['model']::findOrFail($id);

        return view('backend.product-options.form', [
            'resource' => $resource,
            'config' => $config,
            'item' => $item,
            'vendors' => Vendor::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, string $resource, int $id)
    {
        $config = $this->config($resource);
        $item = $config['model']::findOrFail($id);
        $this->fillAndSave($request, $resource, $item);

        return redirect()->route('admin.product-options.index', $resource)->with('status', $config['singular'] . ' updated successfully.');
    }

    public function destroy(string $resource, int $id)
    {
        $config = $this->config($resource);
        $config['model']::findOrFail($id)->delete();

        return redirect()->route('admin.product-options.index', $resource)->with('status', $config['singular'] . ' deleted successfully.');
    }

    private function fillAndSave(Request $request, string $resource, Model $item): void
    {
        $table = $item->getTable();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique($table, 'slug')->ignore($item->id)],
            'hex_code' => ['nullable', 'string', 'max:20'],
            'owner_type' => ['required', Rule::in(['admin', 'vendor'])],
            'vendor_id' => ['nullable', 'required_if:owner_type,vendor', 'exists:vendors,id'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($resource !== 'colors') {
            unset($data['hex_code']);
        }

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['vendor_id'] = $data['owner_type'] === 'vendor' ? ($data['vendor_id'] ?? null) : null;
        $data['is_active'] = $request->boolean('is_active');

        $item->fill($data);
        $item->save();
    }

    private function config(string $resource): array
    {
        abort_unless(isset($this->resources[$resource]), 404);
        return $this->resources[$resource];
    }
}
