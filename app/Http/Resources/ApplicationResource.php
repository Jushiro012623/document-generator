<?php

namespace App\Http\Resources;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Application */
class ApplicationResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'name' => $this->name,
			'slug' => $this->slug,
			'description' => $this->description,
			'status' => $this->status,
			'credentials' => ApplicationCredentialsResource::collection($this->whenLoaded('credentials')),
			'permissions' => ApplicationPermissionResource::collection($this->whenLoaded('permissions')),
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
		];
	}
}
