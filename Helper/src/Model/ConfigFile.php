<?php
declare(strict_types=1);

namespace App\Model;

class ConfigFile
{

    private string $fileName;
    private string $filePath;
    private string $contents = '';

    public function __construct(string $fileName, string $filePath)
    {
        $this->fileName = $fileName;
        $this->filePath = $filePath;
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getContents(): string
    {
        return $this->contents;
    }

    public function setContents(string $contents): ConfigFile
    {
        $this->contents = $contents;
        return $this;
    }
}
