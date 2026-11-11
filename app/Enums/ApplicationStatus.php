<?php

namespace App\Enums;

enum ApplicationStatus: string
{
	case ACTIVE = 'ACTIVE';
	case INACTIVE = 'INACTIVE';
	case SUSPENDED = 'SUSPENDED';
}
