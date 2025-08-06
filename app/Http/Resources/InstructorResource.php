<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstructorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name ,
            'avatar' => url($this->avatar),
            'phone' => $this->phone,
            'email' => $this->email,
            'country' => $this->country,
            'address' => $this->address,
            'role' => $this->role,
            'designation' => $this->designation,
            'services' => $this->services,
            'bio' => $this->bio,
        ];
    }
}
