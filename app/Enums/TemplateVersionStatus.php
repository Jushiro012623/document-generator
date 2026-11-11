<?php

namespace App\Enums;

enum TemplateVersionStatus: string
{
	case DRAFT = 'DRAFT';
	case PUBLISHED = 'PUBLISHED';
	case ARCHIVED = 'ARCHIVED';
}
