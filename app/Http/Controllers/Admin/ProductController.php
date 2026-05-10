<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'products' => Product::latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'sizes'       => 'array',
            'colors'      => 'array',
            'images'      => 'nullable|array',
            'images.*'    => 'image|max:2048',
            'stock'       => 'required|integer|min:0',
            'stock_por_talla' => 'nullable|array',
            'is_active'   => 'nullable|boolean',
            'categoria'   => 'nullable|string|max:100',
            'expires_at'  => 'nullable|date',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['stock_por_talla'] = $this->parseStockPorTalla($request);
        $data['images'] = $this->uploadImages($request);

        Product::create($data);

        return back();
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'sizes'          => 'array',
            'colors'         => 'array',
            'images'         => 'nullable|array',
            'images.*'       => 'image|max:2048',
            'kept_images'    => 'nullable|array',
            'kept_images.*'  => 'string',
            'stock'          => 'required|integer|min:0',
            'stock_por_talla'=> 'nullable|array',
            'is_active'      => 'nullable|boolean',
            'categoria'      => 'nullable|string|max:100',
            'expires_at'     => 'nullable|date',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['stock_por_talla'] = $this->parseStockPorTalla($request);

        $kept = $data['kept_images'] ?? [];
        $removed = array_diff($product->images ?? [], $kept);
        foreach ($removed as $path) {
            Storage::disk('public')->delete($path);
        }

        $newImages = $this->uploadImages($request);
        $data['images'] = array_merge($kept, $newImages);

        unset($data['kept_images']);
        $product->update($data);

        return back();
    }

    public function destroy(Product $product)
    {
        foreach ($product->images ?? [] as $path) {
            Storage::disk('public')->delete($path);
        }
        $product->delete();

        return back();
    }

    private function parseStockPorTalla(Request $request): array
    {
        $raw = $request->input('stock_por_talla', []);
        $result = [];
        foreach ($raw as $talla => $cantidad) {
            $result[$talla] = (int) $cantidad;
        }
        return $result;
    }

    private function uploadImages(Request $request): array
    {
        $paths = [];
        foreach ($request->file('images', []) as $file) {
            $paths[] = $file->store('products', 'public');
        }
        return $paths;
    }
}
