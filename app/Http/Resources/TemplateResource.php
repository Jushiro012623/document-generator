<?php

namespace App\Http\Resources;

use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Template */
class TemplateResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'name' => $this->name,
			'slug' => $this->slug,
			'description' => $this->description,
			'status' => $this->status,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,

			'application' => ApplicationResource::make($this->whenLoaded('application')),
		];
	}
}
