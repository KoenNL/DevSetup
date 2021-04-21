<?php
declare(strict_types=1);

namespace App\ConfigCreator;

use App\Model\ProjectSettings;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class DockerCompose implements ConfigCreatorInterface
{

    private Filesystem $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function getName(): string
    {
        return 'docker-compose.yml';
    }

    public function create(ProjectSettings $projectSettings): void
    {
        if (!$this->filesystem->exists(FilePath::RESOURCE_FOLDER . 'Docker/DockerCompose/webserver.yml')) {
            throw new \Exception('DockerCompose webserver config not found.');
        }

        $dockerComposeContent = $this->parseFile('webserver', $projectSettings, []);

        if ($projectSettings->needsDatabase()) {
            $dockerComposeContent = $this->parseFile('database', $projectSettings, $dockerComposeContent);
        }

        if ($projectSettings->needsVue()) {
            $dockerComposeContent = $this->parseFile('vue-builder', $projectSettings, $dockerComposeContent);
        }

        $dockerComposeFile = $projectSettings->getTempPath() . 'docker-compose.yml';
        $this->filesystem->touch($dockerComposeFile);
        $this->filesystem->appendToFile($dockerComposeFile, Yaml::dump($dockerComposeContent));
    }

    private function parseFile(string $name, ProjectSettings $projectSettings, array $dockerComposeContent): array
    {
        if (!$this->filesystem->exists(FilePath::RESOURCE_FOLDER . 'Docker/DockerCompose/' . $name . '.yml')) {
            throw new \Exception('DockerCompose ' . $name . ' config not found.');
        }

        $parsedContent = Yaml::parse(file_get_contents(FilePath::RESOURCE_FOLDER . 'Docker/DockerCompose/' . $name .'.yml'));

        $dockerComposeContent['services'][$name] = $parsedContent['services'][$name];
        $dockerComposeContent['services'][$name]['container_name'] =
            str_replace(
                '<<APP_NAME>>',
                $projectSettings->getProjectName(),
                $dockerComposeContent['services'][$name]['container_name']
            );

        return $dockerComposeContent;
    }
}
