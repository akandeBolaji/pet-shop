<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Product
 */
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->resource->uuid,
            'title' => $this->resource->title,
            'price' => $this->resource->price,
            'description' => $this->resource->description,
            'image' => new FileResource($this->resource->getImageFile()),
            'category' => new CategoryResource($this->resource->category),
            'brand' => new BrandResource($this->resource->getBrand()),
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}
