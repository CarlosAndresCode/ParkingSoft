<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model implements ServiceableInterface
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = ['name', 'sku', 'description', 'price', 'stock'];

    public function transactionItems(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(TransactionItem::class, 'serviceable');
    }

    public function getServiceDescription(): string
    {
        return $this->name;
    }

    public function getServicePrice(): float
    {
        return (float) $this->price;
    }
}
