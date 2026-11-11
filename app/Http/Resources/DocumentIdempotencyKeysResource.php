<?php

namespace App\Http\Resources;

use App\Models\DocumentIdempotencyKeys;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin DocumentIdempotencyKeys */
class DocumentIdempotencyKeysResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'idempotency_key' => $this->idempotency_key,
			'request_hash' => $this->request_hash,
			'expires_at' => $this->expires_at,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,

			'application_id' => $this->application_id,
			'document_id' => $this->document_id,

			'application' => new ApplicationResource($this->whenLoaded('application')),
			'document' => new DocumentResource($this->whenLoaded('document')),
		];
	}
}
