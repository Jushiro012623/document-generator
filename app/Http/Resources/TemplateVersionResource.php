<?php

namespace App\Http\Resources;

use App\Models\TemplateVersion;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin TemplateVersion */
class TemplateVersionResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'version' => $this->version,
			'format' => $this->format,
			'status' => $this->status,
			'content' => $this->content,
			'styles' => $this->styles,
			'storage_path' => $this->storage_path,
			'data_schema' => $this->data_schema,
			'sample_data' => $this->sample_data,
			'page_settings' => $this->page_settings,
			'published_at' => $this->published_at,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,

			'template_id' => $this->template_id,

			'template' => new TemplateResource($this->whenLoaded('template')),
		];
	}
}
