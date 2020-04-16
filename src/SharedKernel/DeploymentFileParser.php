<?php

declare(strict_types=1);

namespace App\SharedKernel;

final class DeploymentFileParser
{
    public static function parse(string $path): array
    {
        if (!file_exists($path)) {
            return [];
        }

        $content = file_get_contents($path);

        if (false === $content) {
            return [];
        }

        $data = json_decode($content, true);

        return is_array($data) ? $data : [];
    }
}
