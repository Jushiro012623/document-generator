<?php

namespace App\Enums;

enum TemplateFormat: string
{
	case HTML = 'HTML';
	case DOCX = 'DOCX';
	case XLSX = 'XLSX';
}
