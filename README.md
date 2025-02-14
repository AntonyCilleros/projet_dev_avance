# Projet Symfony

## Description
EMBY est une médiathèque accessible en ligne dans lequel on peut y trouver notr collection de musiques/films/séries/jeux-vidéos/livres et d'autres choses encore.
Ce projet symfony se contente de demander à EMBY, quels sont les prochains épisodes qui viennent de sortir ou qui ne vont pas tarder à sortir par rapport aux séries déjà présentes dans EMBY.

## Essayer
Si mon serveur est allumé actuellement, vous pouvez essayer le projet à cette adress : http://planete.velvet-room.tech/
Le HTTPS bloque l'accès à mon api donc je reste sur HTTP pour l'instant.

## Installation

0. **Pré-requis**:
   - un serveur Symfony API comme ceci : https://github.com/AntonyCilleros/projet_dev_avance_api
   - un compte sendgrid ou équivalent pour envoyer des mails.
   - la clé API d'une médiathèque EMBY.
2. **Cloner le projet**
   ```sh
   git clone https://github.com/ton-utilisateur/ton-repo.git
   cd ton-repo

3. **Installer les dépendances PHP**
   ```sh
   composer install

4. **Démarrer la base de données avec Docker**
   ```sh
   docker compose -f compose.yaml -f compose.override.yaml up -d

5. **Charger les fixtures**
   ```sh
   php bin/console doctrine:fixtures:load

6. **Configurer l'application**

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

## Informations complémentaires
L'utilisateur et le mail doivent être uniques.
Le mail entré lors de l'inscription doit respecter le format normal des mails.
Le mot de passe doit contenir :
- 8 caractères
- une lettre minuscule
- une lettre majuscule
- un chiffre

Fait par Antony Cilleros GA1
