# Waklab-api

## Installation du projet

- Copier le .docker/.env.dist en .docker/.env
- Copier le .docker/.env.nginx.dist en .docker/.env.nginx
- Lancer les containers via ```docker compose up -d``` la commande suivante depuis le dossier docker 
- Il faut travailler depuis le conteneur php, vous pouvez utiliser l'extension [Dev container](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-containers), depuis le dossier **/var/www/symfony**
- Un petit ```composer install``` pour installer les dépendances du projet
- Créer la base de données via  ```sf doctrine:database:create```
- Jouer les migrations ```sf doctrine:migrations:migrate```
- Lancer le scraping via ```sf app:scrap-wakfu```

## URL avec les valeurs par défaut en local

- Documentation API : http://localhost:7071/api
- PhpMyadmin : http://localhost:7070/

## URL du serveur de production

- Documentation API : https://waklaboratory.fr/
- PhpMyadmin : https://db.waklaboratory.fr/