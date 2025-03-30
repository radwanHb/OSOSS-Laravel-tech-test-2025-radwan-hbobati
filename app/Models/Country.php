<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $table = 'countries';

    protected $fillable = ['name', 'code'];

    protected $hidden   = ['created_at', 'updated_at'];

    // Relations ///////////////////////

    /**
     * get country related price lists
     * @return HasMany
     */
    public function priceLists(): HasMany
    {
        return $this->hasMany(PriceList::class, 'country_code', 'code');
    }

}
