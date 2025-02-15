<?php

namespace App\Http\Resources\Admin;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'mobile' => $this->userDetail->mobile,
            'avatar' => $this->userDetail->avatar,
            'company' => $this->userDetail?->company?->name,
            'role' => $this->roles[0]->name,
            'is_active' => $this->userDetail->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
