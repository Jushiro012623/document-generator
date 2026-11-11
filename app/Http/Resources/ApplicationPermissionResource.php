<?php

namespace App\Http\Resources;

use App\Models\ApplicationPermission;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ApplicationPermission */
class ApplicationPermissionResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'permission' => $this->permission,
			'description' => $this->description,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,

			'application_id' => $this->application_id,

			'application' => new ApplicationResource($this->whenLoaded('application')),
		];
	}
}
