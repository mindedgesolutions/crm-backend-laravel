<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'address' => $this->address,
            'location' => $this->location,
            'pincode' => $this->pincode,
            'city' => $this->city,
            'state' => $this->state,
            'email' => $this->email,
            'phone' => $this->phone,
            'whatsapp' => $this->whatsapp,
            'contact_person' => $this->contact_person,
            'slug' => $this->slug,
            'uuid' => $this->uuid,
            'is_active' => $this->is_active,
            'user_details' => $this->userDetail,
            'user_master' => $this->userDetail->user,
            'website' => $this->website,
            'enc_id' => $this->enc_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
