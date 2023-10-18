<?php

namespace App\Serializer;

use App\Entity\Stuff;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;

final class StuffNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = 'STUFF_NORMALIZER_ALREADY_CALLED';

    protected $baseUrl;

    public function __construct(RequestStack $requestStack)
    {
        $this->baseUrl = $requestStack->getCurrentRequest()->getSchemeAndHttpHost();
    }

    public function normalize($object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $context[self::ALREADY_CALLED] = true;

        // Suppression des resourceDrop des ingrédients des recettes
        foreach( $object->getRecipes() ?? [] as $recipe ) {

            $recipe->setStuff(null);

            foreach($recipe->getRecipeIngredients() as $ingredients) {

                $ingredients->setRecipe(null);

                if($ingredients->getResource()) {
                    $ingredients->getResource()->clear();
                } else {
                    $ingredients->getStuff()->clear();
                }
            }
        }

        foreach( $object->getStuffDrops() ?? [] as $drop ) {
            $drop->setStuff(null);
        }

        // Ajout des ingrédients de recettes, on peut pas utiliser les Groups car ça fait une boucle infinie
        foreach($object->getRecipeIngredients() ?? [] as $recipeIngredients) {
            $recipeIngredients->getRecipe()->setRecipeIngredients(null);
            $recipeIngredients->setStuff(null);

            if($recipeIngredients->getResource()) {
                $recipeIngredients->getRecipe()->getResource()->clear();
            } else {
                $recipeIngredients->getRecipe()->getStuff()->clear();
            }
        }

        return $this->normalizer->normalize($object, $format, $context);
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }
        
        return $data instanceof Stuff;
    }
}