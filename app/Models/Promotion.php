<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'title',
        'content',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function getValidFromAttribute()
    {
        return $this->metadata['valid_from'] ?? null;
    }

    public function getValidToAttribute()
    {
        return $this->metadata['valid_to'] ?? null;
    }

    public function getImageAttribute()
    {
        return $this->metadata['image'] ?? null;
    }

    public function setValidFromAttribute($value)
    {
        $this->attributes['metadata'] = json_encode(array_merge($this->metadata ?? [], ['valid_from' => $value]));
    }

    public function setValidToAttribute($value)
    {
        $this->attributes['metadata'] = json_encode(array_merge($this->metadata ?? [], ['valid_to' => $value]));
    }

    public function setImageAttribute($value)
    {
        $this->attributes['metadata'] = json_encode(array_merge($this->metadata ?? [], ['image' => $value]));
    }
}
