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
            ->get();

        return Inertia::render('Store/Home', ['products' => $products]);
    }

    public function sizeGuide()
    {
        return Inertia::render('Store/SizeGuide');
    }

    public function novedades()
    {
        $products = Product::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->whereNotNull('categoria')
            ->latest()
            ->get();

        $categorias = Product::where('is_active', true)
            ->whereNotNull('categoria')
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->distinct()
            ->pluck('categoria');

        return Inertia::render('Store/Novedades', [
            'products'   => $products,
            'categorias' => $categorias,
        ]);
    }
}
