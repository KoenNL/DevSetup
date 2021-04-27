<?php
declare(strict_types=1);

namespace App\Recipe\Frontend;

use App\Model\DockerCompose;
use App\Model\Dockerfile;
use App\Model\ProjectSettings;
use App\Recipe\RecipeInterface;
use App\Service\CreateDockerCompose;
use App\Service\CreateDockerfile;

class Vuejs implements RecipeInterface
{

    private CreateDockerfile $createDockerfile;
    private CreateDockerCompose $createDockerCompose;

    public function __construct(CreateDockerfile $createDockerfile, CreateDockerCompose $createDockerCompose)
    {
        $this->createDockerfile = $createDockerfile;
        $this->createDockerCompose = $createDockerCompose;
    }

    public function getName(): string
    {
        return 'VueJS';
    }

    public function createDockerfile(ProjectSettings $projectSettings): ?Dockerfile
    {
        return $this->createDockerfile->createDockerfile(
            'vue-builder',
            'Vuejs.Dockerfile',
            $projectSettings
        );
    }

    public function createDockerCompose(ProjectSettings $projectSettings): ?DockerCompose
    {
        return $this->createDockerCompose->createDockerCompose(
            'vue-builder',
            'Vuejs.yml',
            $projectSettings,
            ['APP_NAME' => $projectSettings->getProjectName()]
        );
    }

    public function createConfigFiles(ProjectSettings $projectSettings): ?array
    {
        return null;
    }

    public function getCommands(ProjectSettings $projectSettings): ?array
    {
        return null;
    }
}
