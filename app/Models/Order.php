<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_status_id',
        'payment_id',
        'uuid',
        'products',
        'address',
        'delivery_fee',
        'amount',
        'shipped_at',
    ];

    protected $casts = [
        'products' => 'array',
        'address' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function getProductsAttribute($value): array
    {
        return json_decode($value, true) ?? [];
    }

    public function setProductsAttribute(array $value): void
    {
        $this->attributes['products'] = json_encode($value);
    }

    public function getAddressAttribute($value): array
    {
        return json_decode($value, true) ?? [];
    }

    public function setAddressAttribute(array $value): void
    {
        $this->attributes['address'] = json_encode($value);
    }

}
