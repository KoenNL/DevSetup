<?php
declare(strict_types=1);

namespace App\Service;

use App\ConfigCreator\ConfigCreatorInterface;
use App\ConfigCreator\DockerCompose;
use App\ConfigCreator\Dockerfile;
use App\ConfigCreator\FilePath;
use App\ConfigCreator\Webserver;
use App\Model\ProjectSettings;
use Symfony\Component\Filesystem\Filesystem;

class Installer
{
    private Dockerfile $dockerfileCreator;
    private DockerCompose $dockerComposeCreator;
    private Webserver $webserverCreator;
    private Filesystem $filesystem;

    public function __construct(
        Dockerfile $dockerfileCreator,
        DockerCompose $dockerComposeCreator,
        Webserver $webserverCreator,
        Filesystem $filesystem
    )
    {
        $this->dockerfileCreator = $dockerfileCreator;
        $this->dockerComposeCreator = $dockerComposeCreator;
        $this->webserverCreator = $webserverCreator;
        $this->filesystem = $filesystem;
    }

    public function init(ProjectSettings $projectSettings): void
    {
        $projectSettings->setTempPath(sys_get_temp_dir() . '/' . $projectSettings->getProjectName() . '/');
        $this->filesystem->mkdir($projectSettings->getTempPath());

        $projectSettings->setOutputPath(FilePath::OUTPUT_FOLDER . $projectSettings->getProjectName() . '/');
    }

    /**
     * @return ConfigCreatorInterface[]
     */
    public function getConfigCreators(): array
    {
        return [
            $this->dockerfileCreator,
            $this->dockerComposeCreator,
            $this->webserverCreator
        ];
    }

    public function finalize(ProjectSettings $projectSettings): void
    {
        if ($projectSettings->getTempPath() === null || !$this->filesystem->exists($projectSettings->getTempPath())) {
            throw new \Exception('Temp project not found');
        }

        $this->filesystem->mirror($projectSettings->getTempPath(), $projectSettings->getOutputPath());
    }
}
