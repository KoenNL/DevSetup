<?php
declare(strict_types=1);

namespace App\Recipe;

use App\Recipe\Database\Mariadb104;
use App\Recipe\Frontend\Vuejs;
use App\Recipe\Webserver\ApachePhp74Symfony;

class GetAvailableRecipes
{

    private array $availableWebserverRecipes;
    private array $availableDatabaseRecipes;
    private array $availableFrontendRecipes;

    public function __construct(ApachePhp74Symfony $apachePhp74Symfony, Mariadb104 $mariadb104, Vuejs $vuejs)
    {
        $this->availableWebserverRecipes = [$apachePhp74Symfony];
        $this->availableDatabaseRecipes = [$mariadb104];
        $this->availableFrontendRecipes = [$vuejs];
    }

    /**
     * @return RecipeInterface[]
     */
    public function getAvailableWebserverRecipes(): array
    {
        return $this->availableWebserverRecipes;
    }

    /**
     * @return RecipeInterface[]
     */
    public function getAvailableDatabaseRecipes(): array
    {
        return $this->availableDatabaseRecipes;
    }

    /**
     * @return RecipeInterface[]
     */
    public function getAvailableFrontendRecipes(): array
    {
        return $this->availableFrontendRecipes;
    }

    /**
     * @param RecipeInterface[] $recipes
     */
    public function getNames(array $recipes): array
    {
        $names = [];
        foreach ($recipes as $recipe) {
            $names[get_class($recipe)] = $recipe->getName();
        }

        return $names;
    }

    public function getByClassName(string $className): ?RecipeInterface
    {
        foreach ([$this->availableWebserverRecipes, $this->availableDatabaseRecipes, $this->availableFrontendRecipes] as $recipes) {
            foreach ($recipes as $recipe) {
                if ($recipe instanceof $className) {
                    return $recipe;
                }
            }
        }

        return null;
    }
}
