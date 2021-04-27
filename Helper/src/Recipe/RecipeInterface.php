<?php
declare(strict_types=1);

namespace App\Recipe;

use App\Model\ConfigFile;
use App\Model\DockerCompose;
use App\Model\Dockerfile;
use App\Model\ProjectSettings;

interface RecipeInterface
{

    public function getName(): string;

    public function createDockerfile(ProjectSettings $projectSettings): ?Dockerfile;

    public function createDockerCompose(ProjectSettings $projectSettings): ?DockerCompose;

    /**
     * @return ConfigFile[]
     */
    public function createConfigFiles(ProjectSettings $projectSettings): ?array;

    public function getCommands(ProjectSettings $projectSettings): ?array;
}
