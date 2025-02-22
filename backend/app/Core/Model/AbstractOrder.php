<?php
namespace App\Core\Model;

use App\Core\Interfaces\OrderInterface;

abstract class AbstractOrder extends Model implements OrderInterface {
    protected string $table = 'orders';
}