<?php

namespace App\Http\Resources;

use App\Models\TemplateAuditLog;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin TemplateAuditLog */
class TemplateAuditLogResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'actor_type' => $this->actor_type,
			'actor_id' => $this->actor_id,
			'action' => $this->action,
			'changes' => $this->changes,
			'metadata' => $this->metadata,
			'created_at' => $this->created_at,

			'template_id' => $this->template_id,
			'template_version_id' => $this->template_version_id,

			'template' => new TemplateResource($this->whenLoaded('template')),
			'templateVersion' => new TemplateVersionResource($this->whenLoaded('templateVersion')),
		];
	}
}
