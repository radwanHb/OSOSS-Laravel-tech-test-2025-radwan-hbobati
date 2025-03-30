<?php

namespace App\Repositories;

use App\Enums\ProductPriceOrderEnum;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository
{
    protected $model = Product::class;
    public function getProducts(string $countryCode = null, string|null $currencyCode = null, string|null $date = null, $order = null, bool $pagination = true, $perPage = 10): LengthAwarePaginator
    {

        return $this->model::withApplicablePrice($countryCode, $currencyCode, $date)
                ->addSelect('products.id', 'products.name', 'products.description')
                ->when($order == ProductPriceOrderEnum::Asc->value, fn($query) => $query->orderBy('price'))
                ->when($order == ProductPriceOrderEnum::Desc->value, fn($query) => $query->orderByDesc('price'))
                ->when($pagination, fn($query) => $query->paginate($perPage), fn($query) => $query->get());
    }

    public function getProduct(Product|int $product, string $countryCode = null, string|null $currencyCode = null, string|null $date = null): Product
    {

        $product = $product instanceof Product ? $product : Product::findOrFail($product);

        return $this->model::withApplicablePrice($countryCode, $currencyCode, $date)
                ->addSelect('products.id', 'products.name', 'products.description')
                ->findOrFail($product->id);
    }
}
