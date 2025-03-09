<?php

declare(strict_types=1);

namespace TalesBot\Addons\Recipes;

use TalesBot\Attributes\Recipe;
use TalesBot\RecipeInterface;
use TalesBot\TalesBot;

/**
 * The simplest of recipes: egg and rice.
 */
#[Recipe]
class EggAndRiceRecipe implements RecipeInterface
{
    /**
     * Return information about the recipe.
     */
    public function getInfo(TalesBot $talesBot): array
    {
        return [
            'name' => 'Egg and Rice',
            'description' => 'The simplest of recipes: egg and rice. Oh, sure, maybe the egg is hard-boiled and cut in two, or maybe it\'s scrambled into the rice, or maybe it\'s just sunny-side-up (*medamayaki*, 目玉焼き), but it is, simply and purely, an egg... and rice.',
        ];
    }
}
