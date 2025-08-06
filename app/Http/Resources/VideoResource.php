<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'duration' => $this->duration,
            'file' => $this->privateVideo(),
            'instructor' => [
                'id' => $this->instructor->id ?? null,
                'name' => $this->instructor->name ?? null,
                'avatar' => url($this->instructor->avatar) ?? null,
            ],
            'course' => $this->whenLoaded('content', function () {
                return [
                    'id' => $this->content->course->id ?? null,
                    'name' => $this->content->course->name ?? null,
                ];
            }),
            'tags' => $this->tags->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'title' => $tag->title,
                ];
            }),
        ];
    }
}
