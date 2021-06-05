<?php
declare(strict_types=1);

namespace App\Recipe\Webserver;

use App\Model\DockerCompose;
use App\Model\Dockerfile;
use App\Model\ProjectSettings;
use App\Recipe\RecipeInterface;
use App\Service\CreateConfigFile;
use App\Service\CreateDockerCompose;
use App\Service\CreateDockerfile;

class ApachePhp74Symfony implements RecipeInterface
{

    private CreateDockerfile $createDockerfile;
    private CreateDockerCompose $createDockerCompose;
    private CreateConfigFile $createConfigFile;

    public function __construct(
        CreateDockerfile $createDockerfile,
        CreateDockerCompose $createDockerCompose,
        CreateConfigFile $createConfigFile
    )
    {
        $this->createDockerfile = $createDockerfile;
        $this->createDockerCompose = $createDockerCompose;
        $this->createConfigFile = $createConfigFile;
    }

    public function getName(): string
    {
        return 'Apache, PHP 7.4, Symfony 5';
    }

    public function createDockerfile(ProjectSettings $projectSettings): ?Dockerfile
    {
        return $this->createDockerfile->createDockerfile('webserver', 'Apache-php74-symfony.Dockerfile', $projectSettings);
    }

    public function createDockerCompose(ProjectSettings $projectSettings): ?DockerCompose
    {
        return $this->createDockerCompose->createDockerCompose(
            'webserver',
            'Apache-php74-symfony.yml',
            $projectSettings,
            ['APP_NAME' => $projectSettings->getProjectName()]
        );
    }

    public function createConfigFiles(ProjectSettings $projectSettings): ?array
    {
        return [
            $this->createConfigFile->createConfigFile(
                'app.conf',
                '/config/etc/apache2/sites-enabled',
                'Apache-php74-symfony/app.conf',
                ['HOSTNAME' => $projectSettings->getHostname()]
            ),
            $this->createConfigFile->createConfigFile(
                'php.ini',
                '/config/usr/local/etc/php',
                'Apache-php74-symfony/php.ini'
            ),
        ];
    }

    public function getCommands(ProjectSettings $projectSettings): ?array
    {
        return [
            'composer create-project symfony/skeleton ' . $projectSettings->getTempPath() . '/app -q',
            'composer require --dev symfony/maker-bundle ' . $projectSettings->getTempPath() . '/app -q',
            'rm -rf ' . $projectSettings->getTempPath() .'/app/var',
            'rm -rf ' . $projectSettings->getTempPath() .'/app/vendor',
        ];
    }
}
