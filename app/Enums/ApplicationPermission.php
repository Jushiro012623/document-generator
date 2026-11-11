<?php

namespace App\Enums;

enum ApplicationPermission: string
{
	case TEMPLATE_VIEW = 'templates.view';
	case TEMPLATE_CREATE = 'templates.create';
	case TEMPLATE_UPDATE = 'templates.update';
	case TEMPLATE_DELETE = 'templates.delete';
	case TEMPLATE_PUBLISH = 'templates.publish';

	case DOCUMENT_GENERATE = 'documents.generate';
	case DOCUMENT_VIEW = 'documents.view';
	case DOCUMENT_DOWNLOAD = 'documents.download';
}