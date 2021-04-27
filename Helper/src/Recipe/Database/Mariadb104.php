<?php
declare(strict_types=1);

namespace App\Recipe\Database;

use App\Model\DockerCompose;
use App\Model\Dockerfile;
use App\Model\ProjectSettings;
use App\Recipe\RecipeInterface;
use App\Service\CreateDockerCompose;

class Mariadb104 implements RecipeInterface
{

    private CreateDockerCompose $createDockerCompose;

    public function __construct(CreateDockerCompose $createDockerCompose)
    {
        $this->createDockerCompose = $createDockerCompose;
    }

    public function getName(): string
    {
        return 'MariaDB 10.4';
    }

    public function createDockerfile(ProjectSettings $projectSettings): ?Dockerfile
    {
        return null;
    }

    public function createDockerCompose(ProjectSettings $projectSettings): ?DockerCompose
    {
        return $this->createDockerCompose->createDockerCompose(
            'database',
            'Mariadb104.yml',
            $projectSettings,
            ['APP_NAME' => $projectSettings->getProjectName()]
        );
    }

    /**
     * @inheritDoc
     */
    public function createConfigFiles(ProjectSettings $projectSettings): ?array
    {
        return null;
    }

    public function getCommands(ProjectSettings $projectSettings): ?array
    {
        return null;
    }
}
