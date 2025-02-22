<?php
namespace App\Core\Model;

use App\Core\Interfaces\ProductInterface;

abstract class AbstractProduct extends Model implements ProductInterface {
    protected string $table = 'products';
}