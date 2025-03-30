<?php

namespace App\Models;

use App\Observers\PriceListObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([PriceListObserver::class])]
class PriceList extends Model
{

    protected $table = 'price_lists';

    protected $fillable = [
        'product_id', 'country_code', 'currency_code', 'price', 'start_date', 'end_date', 'priority'
    ];

    protected $hidden   = ['created_at', 'updated_at'];


    /////////////////////// Relations ///////////////////////

    /**
     * get price list related product
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * get price list related country
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_code', 'code');
    }

    /**
     * get price list related country
     * @return BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }


    //////////////////////// Scopes ////////////////////////

    /**
     *
     * Price List Scope that insures getting only one
     * applicable price list for given parameters,
     * checks all applicable cases and take one by priority
     *
     * @param $query
     * @param $countryCode
     * @param $currencyCode
     * @param $date
     * @return Builder
     */
    public function scopeMostApplicableForParameters($query, $countryCode, $currencyCode, $date): Builder
    {

        $countryCode = $countryCode ?? config('app.currency_code');
        $currencyCode = $currencyCode ?? config('app.currency_code');
        $date = $date ?? now();

        return $query
            ->where(function($query) use ($countryCode, $currencyCode, $date) {
                //  case 1: date ranges specified
                $query->where(function($query) use ($countryCode, $currencyCode, $date) {
                    $this->countryAndCurrencyConditions($query, $countryCode, $currencyCode)
                        ->whereDate('start_date', '<=', $date)
                        ->whereDate('end_date', '>=', $date);
                })
                //  case 2: start_date specified
                ->orWhere(function($query) use ($countryCode, $currencyCode, $date) {
                    $this->countryAndCurrencyConditions($query, $countryCode, $currencyCode)
                        ->whereDate('start_date', '<=', $date)
                        ->whereNull('end_date');
                })
                // case 3: Only end_date specified
                ->orWhere(function($query) use ($countryCode, $currencyCode, $date) {
                    $this->countryAndCurrencyConditions($query, $countryCode, $currencyCode)
                        ->whereNull('start_date')
                        ->whereDate('end_date', '>=', $date);
                })
                // case 4: No date restrictions
                ->orWhere(function($query) use ($countryCode, $currencyCode) {
                    $this->countryAndCurrencyConditions($query, $countryCode, $currencyCode)
                        ->whereNull('start_date')
                        ->whereNull('end_date');
                });
            })
            ->groupBy('product_id')
            ->orderBy('priority')
            ->havingRaw('MIN(priority)');

    }

    // Helper method for scopeFiltered code conditions
    protected function countryAndCurrencyConditions($query, $countryCode, $currencyCode)
    {
        return $query
                ->where(fn($query) =>
                     $query
                     ->where(function($query) use ($countryCode, $currencyCode) {
                        $query->where('currency_code', $currencyCode)
                            ->where('country_code', $countryCode);
                    })
                    ->orWhere(function($query) use ($currencyCode) {
                        $query->where('currency_code', $currencyCode)
                            ->whereNull('country_code');
                    })
                    ->orWhere(function($query) use ($countryCode) {
                        $query->whereNull('currency_code')
                            ->where('country_code', $countryCode);
                    })
                    ->orWhere(function($query) {
                        $query->whereNull('currency_code')
                            ->whereNull('country_code');
                    })
                );
    }
}
