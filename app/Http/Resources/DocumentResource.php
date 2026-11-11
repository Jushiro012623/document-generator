<?php

namespace App\Http\Resources;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Document */
class DocumentResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'reference' => $this->reference,
			'filename' => $this->filename,
			'format' => $this->format,
			'status' => $this->status,
			'storage_disk' => $this->storage_disk,
			'storage_path' => $this->storage_path,
			'mime_type' => $this->mime_type,
			'file_size' => $this->file_size,
			'error_message' => $this->error_message,
			'requested_at' => $this->requested_at,
			'started_at' => $this->started_at,
			'completed_at' => $this->completed_at,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,

//			'application_id' => $this->application_id,
//			'template_id' => $this->template_id,
//			'template_version_id' => $this->template_version_id,

			'application' => new ApplicationResource($this->whenLoaded('application')),
			'template' => new TemplateResource($this->whenLoaded('template')),
			'templateVersion' => new TemplateVersionResource($this->whenLoaded('templateVersion')),
		];
	}
}
