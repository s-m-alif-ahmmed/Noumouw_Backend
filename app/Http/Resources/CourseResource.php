<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'thumbnail' => $this->thumbnail,
            'tags' => $this->tags->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'title' => $tag->title,
                ];
            }),
            'contents' => $this->contents->map(function ($content) {
                switch ($content->type) {
                    case 'video':
                        return [
                            'id' => $content->contentable->id,
                            'title' => $content->contentable->title,
                            'type' => $content->type,
                            'duration' => $content->contentable->duration,
                            'file' => $content->contentable->privateVideo(),
                            'instructor' => [
                                'id' => $content->contentable->instructor->id ?? null,
                                'name' => $content->contentable->instructor->name ?? null,
                            ],
                            'course' => [
                                'id' => $this->id ?? null,
                                'name' => $this->name ?? null,
                            ],
                        ];
                    case 'podcast':
                        return [
                            'id' => $content->contentable->id,
                            'title' => $content->contentable->title,
                            'type' => $content->type,
                            'description' => $content->contentable->description,
                            'file' => url($content->contentable->file),
                            'instructor' => [
                                'id' => $content->contentable->instructor->id ?? null,
                                'name' => $content->contentable->instructor->name ?? null,
                            ],
                            'course' => [
                                'id' => $this->id ?? null,
                                'name' => $this->name ?? null,
                            ],
                        ];
                    case 'activity':
                        return [
                            'id' => $content->contentable->id,
                            'title' => $content->contentable->title,
                            'description' => $content->contentable->description,
                            'type' => $content->type,
                            'images' => $content->contentable->images,
                            'course' => [
                                'id' => $this->id ?? null,
                                'name' => $this->name ?? null,
                            ],
                        ];
                    case 'evaluation':
                        return [
                            'id' => $content->contentable->id,
                            'title' => $content->contentable->title,
                            'type' => $content->type,
                            'course' => [
                                'id' => $this->id ?? null,
                                'name' => $this->name ?? null,
                            ],
                        ];
                }
            }),
        ];

    }
}
