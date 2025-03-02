<?php
/**
 * Initialisation pour PHPUnit
 * 
 * Ce fichier est utilisé pour configurer l'environnement de test pour PHPUnit.
 * Il inclut le fichier d'autoload de Composer pour charger les dépendances nécessaires,
 * et définit une constante pour indiquer que PHPUnit est en cours d'exécution.
 * 
 * @package Tests
 */
define('PHPUNIT_RUNNING', true); // Définir une constante pour indiquer que PHPUnit est en cours d'exécution

// Charger les dépendances via Composer
require_once __DIR__ . '/../vendor/autoload.php';
