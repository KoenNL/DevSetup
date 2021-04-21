<?php
declare(strict_types=1);

namespace App\ConfigCreator;

use App\Model\ProjectSettings;

interface ConfigCreatorInterface
{

    public function getName(): string;

    public function create(ProjectSettings $projectSettings): void;
}
