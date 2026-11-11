<?php

namespace App\Http\Resources;

use App\Models\TemplateAsset;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin TemplateAsset */
class TemplateAssetResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'filename' => $this->filename,
			'storage_disk' => $this->storage_disk,
			'storage_path' => $this->storage_path,
			'mime_type' => $this->mime_type,
			'file_size' => $this->file_size,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,

			'template_version_id' => $this->template_version_id,

			'templateVersion' => new TemplateVersionResource($this->whenLoaded('templateVersion')),
		];
	}
}
