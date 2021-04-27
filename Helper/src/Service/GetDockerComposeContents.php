<?php
declare(strict_types=1);

namespace App\Service;

use App\ConfigCreator\FilePath;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class GetDockerComposeContents
{

    private Filesystem $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function getDockerComposeContentsAsArray(string $dockerComposeFileName): array
    {
        $filePath = FilePath::RESOURCE_FOLDER . 'Docker/DockerCompose/' . $dockerComposeFileName;
        if (!$this->filesystem->exists($filePath)) {
            throw new \Exception('Docker compose file ' . $filePath . ' not found.');
        }

        return Yaml::parseFile($filePath);
    }
}
