<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\FilePath;
use App\Model\DockerCompose;
use App\Model\Dockerfile;
use App\Model\ProjectSettings;
use App\Recipe\RecipeInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class Installer
{

    private Filesystem $filesystem;
    private MergeDockerfilesFromRecipes $mergeDockerfilesFromRecipes;
    private MergeDockerComposeFromRecipes $mergeDockerComposeFromRecipes;

    public function __construct(
        Filesystem $filesystem,
        MergeDockerfilesFromRecipes $mergeDockerfilesFromRecipes,
        MergeDockerComposeFromRecipes $mergeDockerComposeFromRecipes
    )
    {
        $this->filesystem = $filesystem;
        $this->mergeDockerfilesFromRecipes = $mergeDockerfilesFromRecipes;
        $this->mergeDockerComposeFromRecipes = $mergeDockerComposeFromRecipes;
    }

    /**
     * @param RecipeInterface[] $recipes
     */
    public function installFromRecipes(ProjectSettings $projectSettings, array $recipes): void
    {
        $projectSettings->setTempPath(sys_get_temp_dir() . '/' . $projectSettings->getProjectName() . '/');
        $this->filesystem->mkdir($projectSettings->getTempPath());

        $projectSettings->setOutputPath(FilePath::OUTPUT_FOLDER . $projectSettings->getProjectName() . '/');

        $this->writeDockerfile($this->mergeDockerfilesFromRecipes->mergeDockerfilesFromRecipes($recipes, $projectSettings), $projectSettings);
        $this->writeDockerCompose($this->mergeDockerComposeFromRecipes->mergeDockerComposeFromRecipes($recipes, $projectSettings), $projectSettings);
        $this->writeConfigFiles($recipes, $projectSettings);
        $this->executeCommands($recipes, $projectSettings);
    }

    private function writeDockerfile(Dockerfile $dockerfile, ProjectSettings $projectSettings): void
    {
        $filePath = $projectSettings->getTempPath() . 'Dockerfile';
        $this->filesystem->touch($filePath);
        file_put_contents($filePath, $dockerfile->getContents());
    }

    private function writeDockerCompose(DockerCompose $dockerCompose, ProjectSettings $projectSettings): void
    {
        $filePath = $projectSettings->getTempPath() . 'docker-compose.yml';
        $this->filesystem->touch($filePath);
        file_put_contents(
            $filePath,
            Yaml::dump(['services' => $dockerCompose->getServiceContents(), 'volumes' => $dockerCompose->getVolumes()])
        );
    }

    /**
     * @param RecipeInterface[] $recipes
     */
    private function writeConfigFiles(array $recipes, ProjectSettings $projectSettings): void
    {
        foreach ($recipes as $recipe) {
            $configFiles = $recipe->createConfigFiles($projectSettings);
            if ($configFiles !== null) {
                foreach ($configFiles as $configFile) {
                    $filePath = $projectSettings->getTempPath() . $configFile->getFilePath();
                    if (!$this->filesystem->exists($filePath)) {
                        $this->filesystem->mkdir($filePath);
                    }
                    $filePath .= '/' . $configFile->getFileName();
                    $this->filesystem->touch($filePath);
                    file_put_contents($filePath, $configFile->getContents());
                }
            }
        }
    }

    /**
     * @param RecipeInterface[] $recipes
     */
    private function executeCommands(array $recipes, ProjectSettings $projectSettings): void
    {
        exec('cd ' . $projectSettings->getTempPath());

        foreach ($recipes as $recipe) {
            $commands = $recipe->getCommands($projectSettings);
            if ($commands !== null) {
                foreach ($commands as $command) {
                    exec($command);
                }
            }
        }
    }

    public function finalize(ProjectSettings $projectSettings): void
    {
        if ($projectSettings->getTempPath() === null || !$this->filesystem->exists($projectSettings->getTempPath())) {
            throw new \Exception('Temp project not found');
        }

        $this->filesystem->mirror($projectSettings->getTempPath(), $projectSettings->getOutputPath());
        $this->filesystem->chmod($projectSettings->getOutputPath(), 0755, recursive: true);
    }
}
