<?php

namespace App\Observers;

use App\Models\PriceList;
use Illuminate\Support\Facades\Cache;

class PriceListObserver
{
    /**
     * Handle the PriceList "created" event.
     */
    public function created(PriceList $priceList): void
    {
        Cache::tags(['products'])->flush();
    }

    /**
     * Handle the PriceList "updated" event.
     */
    public function updated(PriceList $priceList): void
    {
        Cache::tags(['products'])->flush();
    }

    /**
     * Handle the PriceList "deleted" event.
     */
    public function deleted(PriceList $priceList): void
    {
        Cache::tags(['products'])->flush();
    }

    /**
     * Handle the PriceList "restored" event.
     */
    public function restored(PriceList $priceList): void
    {
        //
    }

    /**
     * Handle the PriceList "force deleted" event.
     */
    public function forceDeleted(PriceList $priceList): void
    {
        //
    }
}
