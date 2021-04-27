<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\DockerCompose;
use App\Model\ProjectSettings;

class CreateDockerCompose
{

    private GetDockerComposeContents $getDockerComposeContents;
    private FillInPlaceholders $fillInPlaceholders;

    public function __construct(GetDockerComposeContents $getDockerComposeContents, FillInPlaceholders $fillInPlaceholders)
    {
        $this->getDockerComposeContents = $getDockerComposeContents;
        $this->fillInPlaceholders = $fillInPlaceholders;
    }

    public function createDockerCompose(string $serviceName, string $templateFileName, ProjectSettings $projectSettings, array $arguments = []): DockerCompose
    {
        $dockerCompose = new DockerCompose($serviceName, $projectSettings->getProjectName());
        $dockerComposeTemplate = $this->getDockerComposeContents->getDockerComposeContentsAsArray($templateFileName);

        if (isset($dockerComposeTemplate['services'])) {
            $dockerCompose->setServiceContents($dockerComposeTemplate['services']);
        }
        if (isset($dockerComposeTemplate['volumes'])) {
            $dockerCompose->setVolumes($dockerComposeTemplate['volumes']);
        }

        if (!empty($arguments)) {
            $dockerCompose = $this->fillInPlaceholders->fillInDockerCompose($dockerCompose, $arguments);
        }

        return $dockerCompose;
    }
}
