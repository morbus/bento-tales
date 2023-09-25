<?php

declare(strict_types=1);

namespace TalesBot\Recipes;

use TalesBot\Attributes\Recipe;

/**
 * The simplest of bento recipes: egg on top of rice.
 */
#[Recipe]
class EggAndRice implements RecipeInterface
{
    /**
     * Return information about the recipe.
     */
    public function getInfo(): array
    {
        return [
            'name' => 'Egg and Rice',
            'description' => 'The simplest of recipes: egg and rice. Oh, sure, maybe the egg is hard-boiled and cut in two, or maybe it\'s scrambled into the rice, or maybe it\'s just sunny-side-up (*medamayaki*, 目玉焼き), but it is, simply and purely, an egg... and rice.',
        ];
    }
}
