<?php
namespace App\Core\Model;

use App\Core\Interfaces\CategoryInterface;

abstract class AbstractCategory extends Model implements CategoryInterface {
    protected string $table = 'categories';
}