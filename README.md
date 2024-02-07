# Balance Ton Spot - Backend (Symfony)

Bienvenue dans le backend de l'application Balance Ton Spot ! Cette application permet aux utilisateurs de découvrir les spots de skateboard et snowboard dans les villes près de chez eux!

## Prérequis

- PHP
- Symfony 5.4
- Base de données (MySQL)

## Installation

1. Clonez le repository:

    ```bash
    git clone git@github.com:O-clock-Falafel/projet-04-balance-ton-spot-back.git
    ```

2. Installez les dépendances avec Composer:

    ```bash
    composer install ou composer update
    ```

3. Configurez votre base de données en copiant le fichier `.env.example` en `.env` et en modifiant les paramètres de connexion à la base de données.

4. Créez la base de données et effectuez les migrations:

    ```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
    ```

5. Lancez le serveur de développement:

    ```bash
    php -S 0.0.0.0:8000 -t public
    ```

Votre backend Symfony devrait être accessible à l'adresse http://localhost:8000.

## Endpoints

- `/api/snowboard/spots` - GET - Récupérer la liste des spots de snowboard.
- `/api/snowboard/spot/{id}` - GET - Accéder à un spot de snowboard spécifique.
- `/admin/snowboard/spot` - POST - Ajouter un nouveau spot de snowboard.
- `/admin/snowboard/spot/{id}` - PUT - Modifier les détails d'un spot de snowboard.
- `/admin/snowboard/spot/{id}` - DELETE - Supprimer un spot de snowboard.
- `/api/skateboard/spots` - GET - Récupérer la liste des spots de skateboard.
- `/api/skateboard/spot/{id}` - GET - Accéder à un spot de skateboard spécifique.
- `/admin/skateboard/spot` - POST - Ajouter un nouveau spot de skateboard.
- `/admin/skateboard/spot/{id}` - PUT - Modifier les détails d'un spot de skateboard.
- `/admin/skateboard/spot/{id}` - DELETE - Supprimer un spot de skateboard.

## Documentation

Pour plus d'informations sur l'utilisation de chaque endpoint, veuillez consulter le fichier `endpoints.md` dans le dossier `docs`.
