<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateJwtKeyCommand extends Command
{
	protected $signature = 'generate:jwt-key';

	protected $description = 'Command to generate a JWT secret and save it to the .env file.';

	public function handle(): void
	{
		$jwtSecret = Str::toBase64(Str::random(64));

		$envPath = base_path('.env');
		$envContent = File::get($envPath);

		$keyValue = "JWT_SECRET=$jwtSecret";

		if (str_contains($envContent, 'JWT_SECRET_KEY=')) {
			$envContent = preg_replace('/JWT_SECRET_KEY=.*/', $keyValue, $envContent);
		} else {
			$envContent .= PHP_EOL . $keyValue;
		}

		File::put($envPath, $envContent);

		$this->info('JWT Secret generated successfully');
		$this->line($keyValue);
	}

}
