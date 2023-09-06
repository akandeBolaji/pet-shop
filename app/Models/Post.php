<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'title',
        'slug',
        'content',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function getAuthorAttribute()
    {
        return $this->metadata['author'] ?? null;
    }

    public function getImageAttribute()
    {
        return $this->metadata['image'] ?? null;
    }

    public function setAuthorAttribute($value)
    {
        $this->attributes['metadata'] = json_encode(array_merge($this->metadata ?? [], ['author' => $value]));
    }

    public function setImageAttribute($value)
    {
        $this->attributes['metadata'] = json_encode(array_merge($this->metadata ?? [], ['image' => $value]));
    }
}
