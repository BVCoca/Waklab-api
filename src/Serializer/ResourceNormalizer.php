<?php

namespace App\Serializer;

use App\Entity\Resource;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;

final class ResourceNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = 'CLEAR_NORMALIZER_ALREADY_CALLED';

    protected $baseUrl;

    public function __construct(RequestStack $requestStack)
    {
        $this->baseUrl = $requestStack->getCurrentRequest()->getSchemeAndHttpHost();
    }

    public function normalize($object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $context[self::ALREADY_CALLED] = true;

        // Suppression des resourceDrop des ingrédients des recettes
        foreach( $object->getRecipes() as $recipes ) {
            foreach($recipes->getRecipeIngredients() as $ingredients) {

                $ingredients->setRecipe(null);

                if($ingredients->getResource()) {
                    $ingredients->getResource()->setDescription(null);
                    $ingredients->getResource()->setResourceDrops(null);
                    $ingredients->getResource()->setRecipes(null);
                    $ingredients->getResource()->setRecipeIngredients(null);
                } else {
                    $ingredients->getStuff()->setDescription(null);
                    $ingredients->getStuff()->setStuffDrops(null);
                    $ingredients->getStuff()->setRecipes(null);
                    $ingredients->getStuff()->setRecipeIngredients(null);
                }
            }
        }

        foreach( $object->getResourceDrops() as $drops ) {
            $drops->setResource(null);
        }

        // Ajout des ingrédients de recettes, on peut pas utiliser les Groups car ça fait une boucle infinie
        foreach($object->getRecipeIngredients() as $recipeIngredients) {
            $recipeIngredients->getRecipe()->setRecipeIngredients(null);
            $recipeIngredients->setResource(null);

            if($recipeIngredients->getRecipe()->getResource()) {
                $recipeIngredients->getRecipe()->getResource()->setDescription(null);
                $recipeIngredients->getRecipe()->getResource()->setResourceDrops(null);
                $recipeIngredients->getRecipe()->getResource()->setRecipes(null);
                $recipeIngredients->getRecipe()->getResource()->setRecipeIngredients(null);
            } else {
                $recipeIngredients->getRecipe()->getStuff()->setDescription(null);
                $recipeIngredients->getRecipe()->getStuff()->setStuffDrops(null);
                $recipeIngredients->getRecipe()->getStuff()->setRecipes(null);
                $recipeIngredients->getRecipe()->getStuff()->setRecipeIngredients(null);
            }
        }

        return $this->normalizer->normalize($object, $format, $context);
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }
        
        return $data instanceof Resource;
    }
}