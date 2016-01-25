<?php

namespace App\Http\Controllers;
use App\Models\Entities\Product;

class ProductController extends Controller
{
    public function getAll() {
		return Product::all();
	}

	public function getAllFeatured() {
		return Product::where('featured', 1)->get();
	}

	public function getAllBeer() {
		return;
	}
}
