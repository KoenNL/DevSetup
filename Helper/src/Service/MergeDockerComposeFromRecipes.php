<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\DockerCompose;
use App\Model\ProjectSettings;
use App\Recipe\RecipeInterface;

class MergeDockerComposeFromRecipes
{

    /**
     * @param RecipeInterface[] $recipes
     */
    public function mergeDockerComposeFromRecipes(array $recipes, ProjectSettings $projectSettings): DockerCompose
    {
        $mergedDockerCompose = new DockerCompose('mergedDockerCompose', $projectSettings->getProjectName());
        $mergedDockerComposeServices = [];
        $mergedDockerComposeVolumes = [];
        foreach ($recipes as $recipe) {
            $dockerComposeFromRecipe = $recipe->createDockerCompose($projectSettings);
            if ($dockerComposeFromRecipe !== null) {
                $mergedDockerComposeServices += $dockerComposeFromRecipe->getServiceContents();
                $mergedDockerComposeVolumes += $dockerComposeFromRecipe->getVolumes();
            }
        }

        $mergedDockerCompose->setServiceContents($mergedDockerComposeServices);
        $mergedDockerCompose->setVolumes($mergedDockerComposeVolumes);

        return $mergedDockerCompose;
    }
}
