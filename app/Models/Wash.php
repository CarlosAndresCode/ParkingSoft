<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wash extends Model implements ServiceableInterface
{
    /** @use HasFactory<\Database\Factories\WashFactory> */
    use HasFactory;

    protected $fillable = ['wash_type_id', 'plate', 'completed_at'];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
        ];
    }

    public function washType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(WashType::class);
    }

    public function transactionItems(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(TransactionItem::class, 'serviceable');
    }

    public function getServiceDescription(): string
    {
        return 'Servicio de Lavado: '.$this->washType->name.($this->plate ? ' ('.$this->plate.')' : '');
    }

    public function getServicePrice(): float
    {
        return (float) $this->washType->price;
    }
}
