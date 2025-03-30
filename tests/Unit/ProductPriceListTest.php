<?php

namespace Tests\Feature;

use App\Enums\ProductPriceOrderEnum;
use Tests\TestCase;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductPriceListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test getting product base price.
     */
    public function test_get_product_base_price(): void
    {
        $product = $this->prepareProduct([
            "name" => 'test-1',
            "base_price" => 10
        ]);

        $result = app()->make(ProductService::class)->getProduct($product->id);

        $this->assertEquals(10, $result->price);
    }

    public function test_get_product_applicable_price_list_case_just_price_filled(): void
    {
        $product = $this->prepareProduct([
            "name" => 'test-1',
            "base_price" => 10
        ]);

        $product->priceLists()->create([
            "price" => 20,
        ]);

        $result = app()->make(ProductService::class)->getProduct($product->id);

        $this->assertEquals(20, $result->price);
    }

    public function test_get_product_applicable_price_list_from_multiple_records_case_just_price_filled(): void
    {
        $product = $this->prepareProduct([
            "name" => 'test-1',
            "base_price" => 10
        ]);

        $product->priceLists()->create([
            "price" => 30,
            "priority" => 3
        ]);

        $product->priceLists()->create([
            "price" => 40,
            "priority" => 1
        ]);

        $product->priceLists()->create([
            "price" => 50,
            "priority" => 2
        ]);



        $result = app()->make(ProductService::class)->getProduct($product->id);

        $this->assertEquals(40, $result->price);
    }


    public function test_get_base_price_fallback_if_no_price_lists_applicable(): void
    {
        $product = $this->prepareProduct([
            "name" => 'test-1',
            "base_price" => 10
        ]);

        $product->priceLists()->create([
            "price" => 30,
            "priority" => 3,
            "currency_code" => "USD",
            "country_code" => "USA",
        ]);

        $product->priceLists()->create([
            "price" => 40,
            "priority" => 1,
            "currency_code" => "TRL",
            "country_code" => "TRY",
        ]);


        $result = app()->make(ProductService::class)->getProduct($product->id, countryCode: 'SYR');


        $this->assertEquals(10, $result->price);
    }

    public function test_get_right_applicable_price_base_on_date_range(): void
    {
        $product = $this->prepareProduct([
            "name" => 'test-1',
            "base_price" => 10
        ]);

        $product->priceLists()->create([
            "price" => 30,
            "currency_code" => "USD",
            "country_code" => "USA",
            "start_date" => '2025-01-01',
            "end_date" => '2025-05-01',
            "priority" => 1
        ]);

        $product->priceLists()->create([
            "price" => 50,
            "currency_code" => "USD",
            "country_code" => "USA",
            "start_date" => '2025-06-01',
            "end_date" => '2025-08-01',
            "priority" => 10
        ]);

        //assert fallback to base price
        $result = app()->make(ProductService::class)->getProduct($product->id, countryCode: 'SYR', currencyCode: 'USD', date: '2025-03-01');
        $this->assertEquals(10, $result->price);

        //assert get applicable price by date
        $result = app()->make(ProductService::class)->getProduct($product->id, countryCode: 'USA', currencyCode: 'USD', date: '2025-03-01');
        $this->assertEquals(30, $result->price);

    }


    public function test_get_right_applicable_price_in_products_list(): void
    {
        $product = $this->prepareProduct([
            "name" => 'test-1',
            "base_price" => 10
        ]);

        $product1 = $this->prepareProduct([
            "name" => 'test-2',
            "base_price" => 20
        ]);

        $product->priceLists()->create([
            "price" => 30,
            "currency_code" => "USD",
            "country_code" => "USA",
            "start_date" => '2025-01-01',
            "end_date" => '2025-05-01',
            "priority" => 1
        ]);

        $product->priceLists()->create([
            "price" => 50,
            "currency_code" => "USD",
            "country_code" => "USA",
            "start_date" => '2025-06-01',
            "end_date" => '2025-08-01',
            "priority" => 10
        ]);

        $product1->priceLists()->create([
            "price" => 70,
            "currency_code" => "USD",
            "country_code" => "USA",
            "start_date" => '2025-01-01',
            "end_date" => '2025-05-01',
            "priority" => 1
        ]);

        $product1->priceLists()->create([
            "price" => 80,
            "currency_code" => "USD",
            "country_code" => "USA",
            "start_date" => '2025-06-01',
            "end_date" => '2025-08-01',
            "priority" => 10
        ]);

        //assert fallback to base price
        $result = app()->make(ProductService::class)->getProducts(countryCode: 'SYR', currencyCode: 'USD', date: '2025-03-01');
        $this->assertEquals(10, $result[0]->price);
        $this->assertEquals(20, $result[1]->price);


        //assert get applicable price by date
        $result = app()->make(ProductService::class)->getProducts( countryCode: 'USA', currencyCode: 'USD', date: '2025-03-01');
        $this->assertEquals(30, $result[0]->price);
        $this->assertEquals(70, $result[1]->price);

    }


    public function test_get_right_applicable_price_in_products_list_with_order_higher_to_lower(): void
    {
        $product = $this->prepareProduct([
            "name" => 'test-1',
            "base_price" => 10
        ]);

        $product1 = $this->prepareProduct([
            "name" => 'test-2',
            "base_price" => 20
        ]);

        $product->priceLists()->create([
            "price" => 30,
            "currency_code" => "USD",
            "country_code" => "USA",
            "start_date" => '2025-01-01',
            "end_date" => '2025-05-01',
            "priority" => 1
        ]);

        $product->priceLists()->create([
            "price" => 50,
            "currency_code" => "USD",
            "country_code" => "USA",
            "start_date" => '2025-06-01',
            "end_date" => '2025-08-01',
            "priority" => 10
        ]);

        $product1->priceLists()->create([
            "price" => 70,
            "currency_code" => "USD",
            "country_code" => "USA",
            "start_date" => '2025-01-01',
            "end_date" => '2025-05-01',
            "priority" => 1
        ]);

        $product1->priceLists()->create([
            "price" => 80,
            "currency_code" => "USD",
            "country_code" => "USA",
            "start_date" => '2025-06-01',
            "end_date" => '2025-08-01',
            "priority" => 10
        ]);

        //assert fallback to base price
        $result = app()->make(ProductService::class)->getProducts(countryCode: 'SYR', currencyCode: 'USD', date: '2025-03-01', order: ProductPriceOrderEnum::Desc->value);
        $this->assertEquals(20, $result[0]->price);
        $this->assertEquals(10, $result[1]->price);


        //assert get applicable price by date
        $result = app()->make(ProductService::class)->getProducts( countryCode: 'USA', currencyCode: 'USD', order: ProductPriceOrderEnum::Desc->value);
        $this->assertEquals(70, $result[0]->price);
        $this->assertEquals(30, $result[1]->price);

    }

    public function test_get_right_applicable_price_in_products_list_with_order_lower_to_higher(): void
    {
        $product = $this->prepareProduct([
            "name" => 'test-1',
            "base_price" => 10
        ]);

        $product1 = $this->prepareProduct([
            "name" => 'test-2',
            "base_price" => 20
        ]);

        $product->priceLists()->create([
            "price" => 30,
            "currency_code" => "USD",
            "country_code" => "USA",
            "start_date" => '2025-01-01',
            "end_date" => '2025-05-01',
            "priority" => 1
        ]);

        $product->priceLists()->create([
            "price" => 50,
            "currency_code" => "USD",
            "country_code" => "USA",
            "start_date" => '2025-06-01',
            "end_date" => '2025-08-01',
            "priority" => 10
        ]);

        $product1->priceLists()->create([
            "price" => 70,
            "currency_code" => "USD",
            "country_code" => "USA",
            "start_date" => '2025-01-01',
            "end_date" => '2025-05-01',
            "priority" => 1
        ]);

        $product1->priceLists()->create([
            "price" => 80,
            "currency_code" => "USD",
            "country_code" => "USA",
            "start_date" => '2025-06-01',
            "end_date" => '2025-08-01',
            "priority" => 10
        ]);

        //assert fallback to base price
        $result = app()->make(ProductService::class)->getProducts(countryCode: 'SYR', currencyCode: 'USD', date: '2025-03-01', order: ProductPriceOrderEnum::Asc->value);
        $this->assertEquals(10, $result[0]->price);
        $this->assertEquals(20, $result[1]->price);


        //assert get applicable price by date
        $result = app()->make(ProductService::class)->getProducts( countryCode: 'USA', currencyCode: 'USD', order: ProductPriceOrderEnum::Asc->value);
        $this->assertEquals(30, $result[0]->price);
        $this->assertEquals(70, $result[1]->price);

    }
    private function prepareProduct(array $data): Product
    {
        return Product::create($data);
    }
}
