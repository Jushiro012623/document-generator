<?php

namespace App\Http\Resources;

use App\Models\DocumentGenerationAttempts;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin DocumentGenerationAttempts */
class DocumentGenerationAttemptsResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'attemp_number' => $this->attemp_number,
			'status' => $this->status,
			'error_message' => $this->error_message,
			'started_at' => $this->started_at,
			'completed_at' => $this->completed_at,
			'duration_ms' => $this->duration_ms,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,

			'document_id' => $this->document_id,

			'document' => new DocumentResource($this->whenLoaded('document')),
		];
	}
}
