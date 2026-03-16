<?php

namespace App\Models;

interface ServiceableInterface
{
    /**
     * Get the description of the service or product.
     */
    public function getServiceDescription(): string;

    /**
     * Get the current price of the service or product.
     */
    public function getServicePrice(): float;

    /**
     * Get the transaction items for the service.
     */
    public function transactionItems(): \Illuminate\Database\Eloquent\Relations\MorphMany;
}
