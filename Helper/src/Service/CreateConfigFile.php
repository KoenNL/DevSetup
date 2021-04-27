<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\ConfigFile;

class CreateConfigFile
{

    private GetConfigFileContents $getConfigFileContents;
    private FillInPlaceholders $fillInPlaceholders;

    public function __construct(GetConfigFileContents $getConfigFileContents, FillInPlaceholders $fillInPlaceholders)
    {
        $this->getConfigFileContents = $getConfigFileContents;
        $this->fillInPlaceholders = $fillInPlaceholders;
    }

    public function createConfigFile(
        string $fileName,
        string $filePath,
        string $templateFileName,
        array $arguments = []
    ): ConfigFile
    {
        $configFile = new ConfigFile($fileName, $filePath);
        $templateContents = $this->getConfigFileContents->getConfigFileContents($templateFileName);

        if (empty($arguments)) {
            $configFile->setContents($templateContents);
        } else {
            $configFile->setContents($this->fillInPlaceholders->fillIn($templateContents, $arguments));
        }

        return $configFile;
    }
}
