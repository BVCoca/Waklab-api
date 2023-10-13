<?php

namespace App\Scraper;

interface ScraperInterface {

    // Retourne les entités lié au scraper
    public function getEntities() : array;

    // Retourne l'url de l'entité
    public function getUrl() : string;

    // Retourne le nom du type de ressource
    public function getName() : string;

    // Scrapper de la page
    public function getEntityData(string $slug, array $scraped_data = []);
}