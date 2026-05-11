<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Inertia\Inertia;

class StoreController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('Store/Home', ['products' => $products]);
    }

    public function sizeGuide()
    {
        return Inertia::render('Store/SizeGuide');
    }

    public function novedades()
    {
        $productQuery = Product::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->whereNotNull('categoria');

        $products = $productQuery->latest()
            ->paginate(12)
            ->withQueryString();

        $categorias = $productQuery->distinct()->pluck('categoria');

        return Inertia::render('Store/Novedades', [
            'products'   => $products,
            'categorias' => $categorias,
        ]);
    }
}
