<?php

declare(strict_types=1);

namespace App\Presentation\Web\Controller;

use App\SharedKernel\DeploymentFileParser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Yaml\Yaml;

class ApplicationController extends AbstractController
{
    public function statusAction(): JsonResponse
    {
        $deployInfoPath = sprintf('%s/info.json', $this->getParameter('kernel.project_dir'));
        $deployInfo = DeploymentFileParser::parse($deployInfoPath);

        return $this->json(array_merge(["status" => "operational", "version" => "1.0.0"], $deployInfo));
    }

    public function specificationAction(): JsonResponse
    {
        $rootDir = $this->getParameter('kernel.project_dir');
        $specificationPath = sprintf('%s/doc/specification.yml', $rootDir);

        return $this->json(Yaml::parseFile($specificationPath));
    }
}
