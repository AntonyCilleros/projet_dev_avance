# Projet Symfony

## Essayer
Si mon serveur est allumé actuellement, vous pouvez essayer le projet à cette adress : http://planete.velvet-room.tech/
Le HTTPS bloque l'accès à mon api donc je reste sur HTTP pour l'instant.

## Installation

0. **Lancer le projet Symfony API**: https://github.com/AntonyCilleros/projet_dev_avance_api
1. **Cloner le projet**
   ```sh
   git clone https://github.com/ton-utilisateur/ton-repo.git
   cd ton-repo

2. **Installer les dépendances PHP**
   ```sh
   composer install

3. **Démarrer la base de données avec Docker**
   ```sh
   docker compose -f compose.yaml -f compose.override.yaml up -d

4. **Charger les fixtures**
   ```sh
   php bin/console doctrine:fixtures:load

5. **Configurer l'application**

.env.local doit contenir une variable MAILER_DSN valide ainsi qu'une variable EMBY_API_KEY valide.
.env doit avoir une variable SYMFONY_API_HOST valide aussi. SYMFONY_API_HOST correspond à l'adresse du projet Symfony API.
Le port symfony défini .symfony.local.yaml peut être modifié si nécessaire.


6. **Installer les dépendances js**
    ```sh
   npm install
   
Pour appliquer les changements en dev lors des modifications des vues de vue.js, il faut lancer la commande suivante :
   ```sh
   npm run watch
   ```
en prod :
   ```sh
   npm run build
   ```

7. **Lancer le serveur Symfony**
    ```sh
   symfony server:start

Fait par Antony Cilleros GA1
