<?php
declare(strict_types=1);

namespace App\Model;

class Dockerfile
{

    private string $imageName;
    private string $contents;
    private string $appName;

    public function __construct(string $imageName, string $appName)
    {
        $this->imageName = $imageName;
        $this->appName = $appName;
    }

    public function getImageName(): string
    {
        return $this->imageName;
    }

    public function getAppName(): string
    {
        return $this->appName;
    }

    public function setContents(string $contents): Dockerfile
    {
        $this->contents = $contents;
        return $this;
    }

    public function getContents(): string
    {
        return $this->contents;
    }
}
