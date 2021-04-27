<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Dockerfile;
use App\Model\ProjectSettings;
use App\Recipe\RecipeInterface;

class MergeDockerfilesFromRecipes
{

    /**
     * @param RecipeInterface[] $recipes
     */
    public function mergeDockerfilesFromRecipes(array $recipes, ProjectSettings $projectSettings): Dockerfile
    {
        $dockerfile = new Dockerfile('mergedDockerfile', $projectSettings->getProjectName());
        $mergedDockerfileContents = '';
        foreach ($recipes as $recipe) {
            $dockerfileFromRecipe = $recipe->createDockerfile($projectSettings);
            if ($dockerfileFromRecipe !== null) {
                $mergedDockerfileContents .= '# ' . $dockerfileFromRecipe->getAppName() . ' - ' . $dockerfileFromRecipe->getImageName() . "\n" .
                    $dockerfileFromRecipe->getContents() . "\n";
            }
        }
        $dockerfile->setContents($mergedDockerfileContents);

        return $dockerfile;
    }
}
