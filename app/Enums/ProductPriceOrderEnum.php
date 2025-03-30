<?php

namespace App\Enums;

enum ProductPriceOrderEnum: string
{

    case Desc = 'highest-to-lowest';

    case Asc = 'lowest-to-highest';


}
