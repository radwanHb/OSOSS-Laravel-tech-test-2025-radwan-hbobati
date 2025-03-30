<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Currency extends Model
{
    protected $table = 'currencies';

    protected $fillable = ['name', 'code'];

    protected $hidden   = ['created_at', 'updated_at'];

    // Relations ///////////////////////

    /**
     * get currency related price lists
     * @return HasMany
     */
    public function priceLists(): HasMany
    {
        return $this->hasMany(PriceList::class, 'currency_code', 'code');
    }

}
