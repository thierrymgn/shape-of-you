<?php

namespace App\Enum;

enum StockStatus: string
{
    case INSTOCK = 'In stock';
    case OUTOFSTOCK = 'Out of stock';
    case LIMITED = 'Limited';
}
