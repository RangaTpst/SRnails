
# SRNails - Projet BTS SIO

SRNails est un projet développé dans le cadre du BTS SIO. Il s'agit d'une application web qui permet de gérer des utilisateurs et des articles avec un système d'authentification, de gestion et d'administration. Ce projet est évolutif et de nombreuses fonctionnalités sont prévues pour enrichir l'application.

## Objectifs du projet
- Créer un système d'authentification pour les utilisateurs.
- Gérer des articles (création, modification, suppression).
- Administrer les utilisateurs (création, modification, suppression).
- Mettre en place des tests unitaires.
- Générer de la documentation automatiquement avec Doxygen et PHPDoc.
- Préparer l’ajout futur de fonctionnalités comme :
  - Affichage public des articles.
  - Système de commandes.
  - Gestion des catégories et des produits.

## Fonctionnalités actuelles
- Authentification utilisateur (connexion, inscription, déconnexion).
- Gestion des utilisateurs (dashboard administrateur).
- Gestion des articles (création, modification, suppression).
- Génération automatique de la documentation.
- Tests unitaires avec PHPUnit.

## Fonctionnalités prévues
- Visualisation publique des articles.
- Ajout au panier et commande.
- Gestion des rôles avancés.
- API pour consultation externe.

## Installation du projet

### Prérequis
- PHP 8.3+
- Composer
- MySQL
- Graphviz (pour la génération des diagrammes UML)
- plantUML
- Doxygen (pour la documentation)

### Tutoriel d'installation

1. Clonez le projet :
    ```bash
    git clone https://github.com/RangaTpst/SRnails.git
    ```

2. Accédez au dossier du projet :
    ```bash
    cd SRnails
    ```

3. Installez les dépendances :
    ```bash
    composer install
    ```

4. Configurez la base de données :
    - Créez une base de données MySQL.
    - Importez le fichier `database.sql` qui se trouve dans le dossier `/config/`.

5. Renseignez les informations de connexion dans le fichier `config.php` :
    ```php
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'srnails_db');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    ```

6. Lancez le serveur :
    ```bash
    php -S localhost:8000 -t public
    ```

7. Accédez au site à l'adresse : `http://localhost:8000`

### Informations de connexion par défaut :
- **Admin** : admin@example.com / admin123
- **Utilisateur** : user@example.com / user123

## Structure du projet

```
/app
  /Controllers
  /Models
  /Views
/config
  config.php
  config.php.qal
  database.sql
/core
  Database.php
  router.php
/docs
  /html
  /latex
/public
  /assets
    /css
    /img
    /js
  .htaccess
  index.php
/tests
  /factories
  bootstrap.php
  LoginTest.php
/vendor
.gitignore
composer.json
composer.lock
Doxyfile
LICENSE
phpunit.xml
README.md
```

## Documentation
- **Documentation technique** générée avec Doxygen dans `/docs/html`.
- **Diagrammes UML** créés avec PlantUML et Graphviz.
  - Diagramme de classes
  - Diagramme de cas d'utilisation
  - Diagrammes de séquence
  - Diagramme des entités

## Contributions
Les contributions sont les bienvenues. N'hésitez pas à proposer vos idées et améliorations.

## Licence
Projet sous licence MIT.
