<?php

declare(strict_types=1);

namespace App\Presentation\Web\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Yaml\Yaml;

class ApplicationController extends AbstractController
{
    public function statusAction(): JsonResponse
    {
        return $this->json(["status" => "operational", "version" => "1.0.0"]);
    }

    public function specificationAction(): JsonResponse
    {
        $rootDir = $this->getParameter('kernel.root_dir');
        $specificationPath = sprintf('%s/../doc/specification.yml', $rootDir);

        return $this->json(Yaml::parseFile($specificationPath));
    }
}
