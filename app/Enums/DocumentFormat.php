<?php

namespace App\Enums;

enum DocumentFormat: string
{
	case PDF = 'PDF';
	case DOCX = 'DOCX';
	case XLSX = 'XLSX';
}
