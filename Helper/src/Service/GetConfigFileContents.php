<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\FilePath;
use Symfony\Component\Filesystem\Filesystem;

class GetConfigFileContents
{

    private Filesystem $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function getConfigFileContents(string $fileName): string
    {
        $filePath = FilePath::RESOURCE_FOLDER . 'config/' . $fileName;
        if (!$this->filesystem->exists($filePath)) {
            throw new \Exception('Config file ' . $filePath . ' not found.');
        }

        return file_get_contents($filePath);
    }
}
