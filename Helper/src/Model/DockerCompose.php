<?php
declare(strict_types=1);

namespace App\Model;

class DockerCompose
{

    private string $serviceName;
    private string $appName;
    private array $volumes;
    private array $serviceContents;

    public function __construct(string $serviceName, string $appName)
    {
        $this->serviceName = $serviceName;
        $this->appName = $appName;
    }

    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    public function getAppName(): string
    {
        return $this->appName;
    }

    public function getVolumes(): array
    {
        return $this->volumes;
    }

    public function setVolumes(array $volumes): DockerCompose
    {
        $this->volumes = $volumes;
        return $this;
    }

    public function getServiceContents(): array
    {
        return $this->serviceContents;
    }

    public function setServiceContents(array $serviceContents): DockerCompose
    {
        $this->serviceContents = $serviceContents;
        return $this;
    }


}
