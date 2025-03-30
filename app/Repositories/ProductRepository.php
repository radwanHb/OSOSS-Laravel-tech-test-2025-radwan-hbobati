<?php

namespace App\Repositories;

use App\Enums\ProductPriceOrderEnum;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ProductRepository
{
    protected string $model = Product::class;

    /**
     * fetch all products
     * @param string|null $countryCode
     * @param string|null $currencyCode
     * @param string|null $date
     * @param null $order
     * @param bool $pagination
     * @param int $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function getProducts(string $countryCode = null, string|null $currencyCode = null, string|null $date = null, $order = null, bool $pagination = true, $perPage = 10): LengthAwarePaginator|Collection
    {

        return $this->model::withApplicablePrice($countryCode, $currencyCode, $date)
                ->addSelect('products.id', 'products.name', 'products.description')
                ->when($order == ProductPriceOrderEnum::Asc->value, fn($query) => $query->orderBy('price'))
                ->when($order == ProductPriceOrderEnum::Desc->value, fn($query) => $query->orderByDesc('price'))
                ->when($pagination, fn($query) => $query->paginate($perPage), fn($query) => $query->get());
    }

    /**
     * fetch single product
     *
     * @param string|null $co
     * @param Product|int $product
     * @param string|null $countryCode
     * @param string|null $currencyCode
     * @param string|null $date
     * @return Product
     */
    public function getProduct(Product|int $product, string $countryCode = null, string|null $currencyCode = null, string|null $date = null): Product
    {

        $product = $product instanceof Product ? $product : Product::findOrFail($product);

        return $this->model::withApplicablePrice($countryCode, $currencyCode, $date)
                ->addSelect('products.id', 'products.name', 'products.description')
                ->findOrFail($product->id);
    }
}
