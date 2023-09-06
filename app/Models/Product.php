<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'category_uuid',
        'title',
        'price',
        'description',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_uuid', 'uuid');
    }

    public function getBrandAttribute(): ?string
    {
        return $this->metadata['brand'] ?? null;
    }

    public function setBrandAttribute(string $value): void
    {
        $this->metadata = array_merge($this->metadata ?? [], ['brand' => $value]);
    }

    public function getImageAttribute(): ?string
    {
        return $this->metadata['image'] ?? null;
    }

    public function setImageAttribute(string $value): void
    {
        $this->metadata = array_merge($this->metadata ?? [], ['image' => $value]);
    }
}
