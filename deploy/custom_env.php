<?php

/**
 * Reads .env file and returns value for the given key
 *
 * @param  string  $name  The environment variable name
 * @param  mixed|null  $default  Default value if key doesn't exist
 */
function customEnv(string $name, mixed $default = null)
{
    $envPath = __DIR__.'/../.env';

    // Check if .env exists
    if (! file_exists($envPath)) {
        return $default;
    }

    $name = "DEPLOYER_$name";
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        // Skip comments and invalid lines
        if (str_starts_with(trim($line), '#') || ! str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);

        if ($key === $name) {
            $value = trim($value);

            // Remove surrounding quotes
            if (preg_match('/^["\'].*["\']$/', $value)) {
                $value = substr($value, 1, -1);
            }

            // Convert special values
            return match (strtolower($value)) {
                'true' => true,
                'false' => false,
                'null' => null,
                'empty' => '',
                default => $value,
            };
        }
    }

    return $default;
}
