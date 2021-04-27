<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Dockerfile;
use App\Model\ProjectSettings;

class CreateDockerfile
{

    private GetDockerfileContents $getDockerfileContents;
    private FillInPlaceholders $fillInPlaceholders;

    public function __construct(GetDockerfileContents $getDockerfileContents, FillInPlaceholders $fillInPlaceholders)
    {
        $this->getDockerfileContents = $getDockerfileContents;
        $this->fillInPlaceholders = $fillInPlaceholders;
    }

    public function createDockerfile(string $imageName, string $templateFileName, ProjectSettings $projectSettings, array $arguments = []): Dockerfile
    {
        $dockerfile = new Dockerfile($imageName, $projectSettings->getProjectName());
        $dockerfile->setContents($this->getDockerfileContents->getDockerfileContents($templateFileName));

        if (!empty($arguments)) {
            $dockerfile = $this->fillInPlaceholders->fillInDockerfile($dockerfile, $arguments);
        }

        return $dockerfile;
    }
}
