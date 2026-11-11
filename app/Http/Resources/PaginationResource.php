<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaginationResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		$paginator = $this->resource;
		return [
			'meta' => [
				'current_page' => $paginator->currentPage(),
				'from' => $paginator->firstItem(),
				'last_page' => $paginator->lastPage(),
				'path' => $paginator->path(),
				'per_page' => $paginator->perPage(),
				'to' => $paginator->lastItem(),
				'total' => $paginator->total(),
				'count' => $paginator->count(),
			],
			'links' => [
				'first' => $paginator->url(1),
				'last' => $paginator->url($paginator->lastPage()),
				'prev' => $paginator->previousPageUrl(),
				'next' => $paginator->nextPageUrl(),
			],
		];
	}
}
