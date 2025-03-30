<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Resources\ProductListResource;
use App\Resources\ProductSingleResource;
use Illuminate\Support\Facades\Cache;


class ProductService
{
    protected string $indexResource = ProductListResource::class;
    protected string $singleResource = ProductSingleResource::class;

    public function __construct(protected ProductRepository $productRepository){}

    public function getProducts(string $countryCode = null, string|null $currencyCode = null, string|null $date = null, $order = null, bool $pagination = true, $perPage = 10)
    {


        $cacheKey = 'products_list_'.$countryCode.'_'.$currencyCode.'_'.$date.'_'.$order.'_'.$pagination.'_'.$perPage;
        $cacheTime = 60 * 60 * 24;

        $data = Cache::tags(['products'])->remember($cacheKey, $cacheTime, fn() =>
            $this->productRepository->getProducts($countryCode, $currencyCode, $date, $order, $pagination, $perPage)
        );
        return $this->indexResource::collection($data);
    }

    public function getProduct(Product|int $product ,string $countryCode = null, string|null $currencyCode = null, string|null $date = null)
    {
        $product = $product instanceof Product ? $product : Product::findOrFail($product);

        $cacheKey = 'product_single_'.$countryCode.'_'.$currencyCode.'_'.$date."{$product->id}";
        $cacheTime = 60 * 60 * 24;

        $data = Cache::tags(['products'])->remember($cacheKey, $cacheTime, fn() =>
            $this->productRepository->getProduct($product, $countryCode, $currencyCode, $date)
        );

        return new $this->singleResource($data);
    }
}
