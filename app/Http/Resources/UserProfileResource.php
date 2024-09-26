<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserProfileResource extends JsonResource
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
            'user_id' => $this->user_id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'gender' => $this->gender,
            'interested' => $this->interested,
            'profile' => $this->profile,
            'birthday' => $this->birthday,
            'age' => $this->calcAge($this->birthday),
            'country' => $this->country,
            'state' => $this->state,
            'city' => $this->city,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'style_life' => $this->style_life,
            'height' => $this->height,
            'physical_type' => $this->physical_type,
            'skin_tone' => $this->skin_tone,
            'eye_color' => $this->eye_color,
            'drink' => $this->drink,
            'smoke' => $this->smoke,
            'hair_color' => $this->hair_color,
            'hair_type' => $this->hair_type,
            'marital_status' => $this->marital_status,
            'beard_size' => $this->beard_size,
            'beard_color' => $this->beard_color,
            'children' => $this->children,
            'tattoo' => $this->tattoo,
            'academic_background' => $this->academic_background,
            'occupation' => $this->occupation,
            'monthly_income' => $this->monthly_income,
            'personal_patrimony' => $this->personal_patrimony,
            'hobbies' => $this->hobbies,
            'profile_photo_id' => $this->profile_photo_id,
            'profile_image' => !empty($this->profile_image) ? Storage::url($this->profile_image) : '',
            'first_impression' => $this->first_impression,
            'about' => $this->about,
            'status' => $this->status,
            'online' => $this->online,
            'current_step' => $this->current_step,
            'email_verified_at' => $this->user->email_verified_at,
            'photos' => UserPhotoResource::collection($this->photos),
            'subscription' => $this->subscriptions ? SubscriptionResource::collection($this->subscriptions) : null,
            'visits' => $this->whenCounted('views'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function calcAge($birthday){
        $today = new \DateTime();
        $nasc = new \DateTime($birthday);
        $age = $today->diff($nasc)->y;
        return $age;
    }
}
