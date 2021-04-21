<?php
declare(strict_types=1);

namespace App\Model;

class ProjectSettings
{
    private string $projectName;
    private string $hostname;
    private bool $needsDatabase;
    private bool $needsVue;
    private string $tempPath;
    private string $outputPath;

    public function __construct(string $projectName, string $hostname, bool $needsDatabase, bool $needsVue)
    {
        $this->projectName = $projectName;
        $this->hostname = $hostname;
        $this->needsDatabase = $needsDatabase;
        $this->needsVue = $needsVue;
    }

    public function getProjectName(): string
    {
        return $this->projectName;
    }

    public function getHostname(): string
    {
        return $this->hostname;
    }

    public function needsDatabase(): bool
    {
        return $this->needsDatabase;
    }

    public function needsVue(): bool
    {
        return $this->needsVue;
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
