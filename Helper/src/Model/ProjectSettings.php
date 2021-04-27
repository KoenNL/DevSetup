<?php
declare(strict_types=1);

namespace App\Model;

class ProjectSettings
{
    private string $projectName;
    private string $hostname;
    private string $tempPath;
    private string $outputPath;

    public function __construct(string $projectName, string $hostname)
    {
        $this->projectName = $projectName;
        $this->hostname = $hostname;
    }

    public function getProjectName(): string
    {
        return $this->projectName;
    }

    public function getHostname(): string
    {
        return $this->hostname;
    }

    public function getTempPath(): ?string
    {
        return $this->tempPath;
    }

    public function setTempPath(string $tempPath): ProjectSettings
    {
        $this->tempPath = $tempPath;
        return $this;
    }

    public function getOutputPath(): string
    {
        return $this->outputPath;
    }

    public function setOutputPath(string $outputPath): ProjectSettings
    {
        $this->outputPath = $outputPath;
        return $this;
    }
}
