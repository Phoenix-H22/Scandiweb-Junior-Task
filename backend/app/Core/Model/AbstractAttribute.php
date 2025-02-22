<?php
namespace App\Core\Model;

use App\Core\Interfaces\AttributeInterface;

abstract class AbstractAttribute extends Model implements AttributeInterface {
    protected string $table = 'attributes';
}