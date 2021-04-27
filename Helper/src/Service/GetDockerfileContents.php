<?php
declare(strict_types=1);

namespace App\Service;

use App\ConfigCreator\FilePath;
use Symfony\Component\Filesystem\Filesystem;

class GetDockerfileContents
{

    private Filesystem $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function getDockerfileContents(string $dockerfileName): string
    {
        $filePath = FilePath::RESOURCE_FOLDER . 'Docker/Dockerfile/' . $dockerfileName;
        if (!$this->filesystem->exists($filePath)) {
            throw new \Exception('Dockerfile ' . $filePath . ' not found.');
        }

        return file_get_contents($filePath);
    }
}
