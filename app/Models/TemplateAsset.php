<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemplateAsset extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'template_version_id',
		'filename',
		'storage_disk',
		'storage_path',
		'mime_type',
		'file_size',
	];

	public function templateVersion(): BelongsTo
	{
		return $this->belongsTo(TemplateVersion::class);
	}
}
