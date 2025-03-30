<?php

namespace App\Models;

use App\Observers\ProductObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

#[ObservedBy([ProductObserver::class])]
class Product extends Model
{

    protected $table = 'products';

    protected $fillable = ['name', 'base_price', 'description'];

    protected $hidden   = ['created_at', 'updated_at'];


    ////////////////////// Relations ///////////////////////

    /**
     * get product related price lists
     * @return HasMany
     */
    public function priceLists(): HasMany
    {
        return $this->hasMany(PriceList::class);
    }

    ///////////////////// Scopes ///////////////////////


    /**
     *
     * scope that joins products with their applicable price lists
     * and adds applicable price as a column in database query level,
     * and can help to apply proper ordering, paginating on product applicable price
     *
     * @param $query
     * @param $countryCode
     * @param $currencyCode
     * @param $date
     * @return Builder
     */
    public function scopeWithApplicablePrice($query, $countryCode, $currencyCode, $date): Builder
    {
        return $query
            ->leftJoinSub(
                PriceList::MostApplicableForParameters($countryCode, $currencyCode, $date),
                'applicable_price_lists',
                'applicable_price_lists.product_id', '=', 'products.id'
            )
            ->addSelect(DB::raw('COALESCE(applicable_price_lists.price, products.base_price) as price'));
    }

}
