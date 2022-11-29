# docker-lamp

Configuration Docker pour déployer une pile LAMP (Linux, Apache, MySQL, PHP)

# Installation

1. Installer *Docker* (et *docker-compose*) sur votre système
2. Copier les fichiers de ce repo dans le répertoire de votre choix :
  ```bash
  git clone git@github.com:vpoupet/docker-lamp.git
  cd docker-lamp
  ```
3. Créer et démarrer les différents containers Docker:
  ```bash
  docker-compose -p lamp up -d
  ```
Si tout se passe bien, les différents containers devraient être exécutés en arrière-plan (l'option `-d` sert à démarrer les *containers* en arrière-plan, mais n'est pas toujours reconnue).

# Description

Ces fichiers de configuiration Docker permettent de démarrer 3 composants :
- un serveur Apache avec PHP
- une base de données MariaDB (c'est du MySQL)
- un serveur PhpMyAdmin pour accéder à la base de données

## Apache + PHP

Par défaut, c'est l'image la plus récente de PHP qui est exécutée (8.1). Si vous devez utiliser une version précédente, vous pouvez modifier le contenu du fichier `Dockerfile` en changeant la ligne `FROM php:apache` (par exemple en `FROM php:7.4-apache`). Vous devez alors recréer l'image si elle avait été créée.

Le serveur tourne sur le port 80 de la machine sur laquelle le *container* est exécuté (si vous devez changer ça, c'est dans le fichier `docker-compose.yml` dans la section `ports:` du bloc `apache-php:`. Vous pouvez par exemple mettre `- "3232:80"` pour utiliser le port 3232 de votre machine.

Pour vous connecter au serveur, vous pouvez donc ouvrir un navigateur et aller à l'adresse [127.0.0.1/](127.0.0.1/) (ou [localhost/](localhost/)). Vous devriez voir la page d'informations PHP.

Pour modifier les documents du serveur, modifiez le contenu du répertoire `./html` (notez que ce répertoire contient actuellement un unique fichier `index.php` qui est celui qui est affiché quand vous allez à l'adresse indiquée précédemment).

### Personalisation

Si vous devez configurer le serveur, vous pouvez ajouter des fichiers dans le répertoire `./config/php` (ou modifier un des fichiers déjà présents dans ce répertoire, qui correspond au répertoire `/usr/local/etc/php` du container qui contient la config de PHP). D'autre part, vous pouvez à tout moment vous connecter au container qui fait tourner le serveur en utilisant la commande
```bash
docker exec -it lamp_apache-php_1 /bin/bash
```
Ceci ouvrira un shell dans la machine virtuelle du serveur, ce qui vous permettra d'exécuter les commandes de votre choix (vous pouvez installer des nouveaux programmes avec `apt-get` par exemple, ou éditer des fichiers avec `nano`, etc.).

Si vous voulez que certaines commandes soient automatiquement exécutées quand vous créez le container Docker, vous pouvez les ajouter au fichier `Dockerfile` en ajoutant des lignes commençant par `RUN` (cf. `Dockerfile` dans lequel on exécute les commandes `apt-get update` puis `apt-get -y install nano`).


## MariaDB

*MariaDB* est une branche *open-source* du projet *MySQL* (donc en pratique ça s'utilise pareil).

Le script `docker-compose` démarre un serveur avec un utilisateur `root` dont le mot de passe est « `admin` ». Vous pouvez changer le mot de passe en modifiant la ligne « `MARIADB_ROOT_PASSWORD: admin` » du fichier `docker-compose.yml`.

Une fois lancé, le serveur est disponible sur le port 3306 de votre machine. Cependant, si vous voulez y accéder depuis un autre container *Docker* (ce qui sera probablement le cas si vous essayez de vous y connecter depuis un script PHP exécuté par le container *Apache*), vous devez donner le nom de machine (*hostname*) « `mariadb` ».

Un exemple de connexion est donné dans le fichier `html/dbtest.php`, que vous pouvez exécuter en ouvrant l'URL

    http://localhost/dbtest.php

dans un navigateur pour vérifier que la connexion fonctionne).


## PHPMyAdmin

Si vous voulez administrer la base de données, vous pouvez utiliser l'interface *PHPMyAdmin* en ouvrant l'URL

    http://localhost:8080/

Cette interface se connecte au container *MariaDB* et permet de manipuler vos bases de données.