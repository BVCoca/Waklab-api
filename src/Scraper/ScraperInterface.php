<?php

namespace App\Scraper;

interface ScraperInterface
{
    // Retourne l'entité utilisée pour la création
    public function getEntity(array $data = [], array &$scraped_data = []);

    // Retourne les entités lié au scraper
    public function getLinkedEntities(): array;

    // Retourne la clé utilisée dans le tableau scraped_data
    public function getKey(): string;

    // Retourne l'url de l'entité
    public function getUrl(): string;

    // Retourne le nom du type de ressource
    public function getName(): string;

    // Scrapper de la page
    public function getEntityData(string $slug, array &$scraped_data = []);
}
