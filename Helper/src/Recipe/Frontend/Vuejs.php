<?php
declare(strict_types=1);

namespace App\Recipe\Frontend;

use App\Model\DockerCompose;
use App\Model\Dockerfile;
use App\Model\ProjectSettings;
use App\Recipe\RecipeInterface;
use App\Service\CreateConfigFile;
use App\Service\CreateDockerCompose;
use App\Service\CreateDockerfile;

class Vuejs implements RecipeInterface
{

    private CreateDockerfile $createDockerfile;
    private CreateDockerCompose $createDockerCompose;
    private CreateConfigFile $createConfigFile;

    public function __construct(
        CreateDockerfile $createDockerfile,
        CreateDockerCompose $createDockerCompose,
        CreateConfigFile $createConfigFile
    ) {
        $this->createDockerfile = $createDockerfile;
        $this->createDockerCompose = $createDockerCompose;
        $this->createConfigFile = $createConfigFile;
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
        return [
            $this->createConfigFile->createConfigFile(
                'package.json',
                '/vue',
                'Vuejs/package.json',
                ['NAME' => strtolower($projectSettings->getProjectName())]
            ),
            $this->createConfigFile->createConfigFile(
                'main.js',
                '/vue/src',
                'Vuejs/main.js'
            ),
            $this->createConfigFile->createConfigFile(
                'App.vue',
                '/vue/src',
                'Vuejs/App.vue',
            ),
            $this->createConfigFile->createConfigFile(
                'index.js',
                '/vue/src/router',
                'Vuejs/index.js',
            ),
            $this->createConfigFile->createConfigFile(
                '.gitignore',
                '/vue',
                'Vuejs/.gitignore'
            ),
            $this->createConfigFile->createConfigFile(
                '.env',
                '/vue',
                'Vuejs/.env',
                ['NAME' => $projectSettings->getProjectName()]
            ),
            $this->createConfigFile->createConfigFile(
                '.htaccess',
                '/app/public',
                'Vuejs/.htaccess'
            ),
        ];
    }

    public function getCommands(ProjectSettings $projectSettings): ?array
    {
        return [
            'mkdir ' . $projectSettings->getTempPath() . '/vue/src',
            'mkdir ' . $projectSettings->getTempPath() . '/vue/dist',
        ];
    }
}
