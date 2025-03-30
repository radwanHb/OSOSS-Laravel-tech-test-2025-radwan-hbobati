<?php

namespace App\Enums;

enum ProductPriceOrderEnum: string
{

    case Desc = 'lowest-to-highest';

    case Asc = 'highest-to-lowest';


}
