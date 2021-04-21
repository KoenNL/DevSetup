<?php
declare(strict_types=1);

namespace App\ConfigCreator;

use App\Model\ProjectSettings;
use Symfony\Component\Filesystem\Filesystem;

class Dockerfile implements ConfigCreatorInterface
{
    private Filesystem $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function getName(): string
    {
        return 'Dockerfile';
    }

    public function create(ProjectSettings $projectSettings): void
    {
        if (!$this->filesystem->exists(FilePath::RESOURCE_FOLDER . 'Docker/Dockerfile/Php74-apache.Dockerfile')) {
            throw new \Exception('Dockerfile template for webserver not found');
        }

        $dockerfile = $projectSettings->getTempPath() . 'Dockerfile';
        $this->filesystem->touch($dockerfile);

        $this->filesystem->appendToFile(
            $dockerfile,
            file_get_contents(FilePath::RESOURCE_FOLDER . 'Docker/Dockerfile/Php74-apache.Dockerfile')
        );

        if ($projectSettings->needsVue()) {
            if (!$this->filesystem->exists(FilePath::RESOURCE_FOLDER . 'Docker/Dockerfile/VueJs.Dockerfile')) {
                throw new \Exception('Dockerfile template for VueJs not found');
            }

            $this->filesystem->appendToFile(
                $dockerfile,
                "\n" . file_get_contents(FilePath::RESOURCE_FOLDER . 'Docker/Dockerfile/VueJs.Dockerfile')
            );
        }
    }
}
