<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProfileAvailableListResource extends JsonResource
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
            'gender' => $this->gender,
            'profile' => $this->profile,
            'birthday' => $this->birthday,
            'age' => $this->age(),
            'country' => $this->country,
            'state' => $this->state,
            'city' => $this->city,
            'is_favorited' => $this->favoritedme_count,
            'highlight' => $this->highlight,
            'profile_image' => !empty($this->profile_image) ? Storage::url($this->profile_image) : '',
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
