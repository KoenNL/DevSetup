<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\DockerCompose;
use App\Model\Dockerfile;

class FillInPlaceholders
{

    public function fillInDockerfile(Dockerfile $dockerfile, array $arguments): Dockerfile
    {
        return $dockerfile->setContents($this->fillIn($dockerfile->getContents(), $arguments));
    }

    public function fillInDockerCompose(DockerCompose $dockerCompose, array $arguments): DockerCompose
    {
        return $dockerCompose
            ->setServiceContents($this->fillInRecursive($dockerCompose->getServiceContents(), $arguments))
            ->setVolumes($this->fillInRecursive($dockerCompose->getVolumes(), $arguments))
        ;
    }

    public function fillInRecursive(array $source, array $arguments): array
    {
        foreach ($source as $index => $item) {
            if (is_array($item)) {
                $source[$index] = $this->fillInRecursive($item, $arguments);
            } else {
                $source[$index] = $this->fillIn($item, $arguments);
            }
        }

        return $source;
    }

    public function fillIn(string $source, array $arguments): string
    {
        $result = '';
        foreach ($arguments as $placeholder => $argument) {
            $result = str_replace('<<' . $placeholder . '>>', $argument, $source);
        }

        return $result;
    }
}
