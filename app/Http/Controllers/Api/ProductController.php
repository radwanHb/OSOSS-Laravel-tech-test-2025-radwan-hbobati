<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductGetRequest;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Services\ProductService;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    public function __construct(protected ProductService $productService){}

    public function index(ProductGetRequest $request){

        $countryCode = $request->get('country_code');
        $currencyCode = $request->get('currency_code');
        $date = $request->get('date');
        $order = $request->get('order');
        $perPage = $request->get('per_page', 10);

        return $this->productService->getProducts($countryCode, $currencyCode, $date, $order, perPage: $perPage);

    }


    public function show(Product $product, ProductGetRequest $request){

        $countryCode = $request->get('country_code');
        $currencyCode = $request->get('currency_code');
        $date = $request->get('date');

        return $this->productService->getProduct($product, $countryCode, $currencyCode, $date);

    }
}
