<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'slug'           => $this->slug,
            'body'           => $this->body,
            'excerpt'        => Str::limit($this->body, 150),
            'status'         => $this->whenLoaded('status', fn() => $this->status?->value),
            'is_published'   => $this->whenLoaded('status', fn() => $this->status?->value === 'published'),
            'view_count'     => $this->view_count?? 0,
            'author'         => [
                'id'   => $this->user->id,
                'name' => $this->user->name,
            ],
            'categories'     => $this->whenLoaded('categories', fn() =>
            $this->categories->map(fn($cat) => [
                'id'   => $cat->id,
                'name' => $cat->name,
                'slug' => $cat->slug,
            ])
            ),
            'comments_count' => $this->whenLoaded('comments', fn() => $this->comments->count()),
            'created_at'     => $this->created_at->toIso8601String(),
            'updated_at'     => $this->updated_at->toIso8601String(),
        ];
    }
}
