## README.md

# 🚀 Taupe Meubles

Ce projet utilise Docker et Docker Compose pour créer un environnement de développement local isolé contenant :
1. Un serveur web Apache avec PHP 8.2 (service `app`).
2. Une base de données MySQL 8.0 (service `db`).

## 📁 Structure du Projet

```
mon-site-php/
├── docker-compose.yml   \# Définition des services (PHP/DB)
├── Dockerfile           \# Construction de l'image PHP/Apache
├── README.md            \# Ce fichier
└── www/                 \# ⬅️ **Racine de votre application PHP**
├── index.php  
└── ... (vos fichiers JS, CSS, images, etc.)
````

## 🛠️ Prérequis

Assurez-vous d'avoir installé :

* **Docker**
* **Docker Compose** (souvent inclus avec les versions modernes de Docker)

## 💻 Démarrer l'environnement

Toutes les commandes doivent être exécutées depuis le répertoire racine du projet (`TaupeMeubles`).

### 1. Démarrage initial et construction des images

Cette commande construit les images (en utilisant le `Dockerfile`), crée les conteneurs et les lance en arrière-plan (`-d`).

```bash
docker compose up -d --build
````

### 2\. Accès à l'application

Votre site est désormais accessible via votre navigateur à l'adresse suivante :

[http://localhost:8080](https://www.google.com/search?q=http://localhost:8080)

-----

## ⚙️ Commandes Utiles de Docker Compose

### 🔄 Redémarrer les services

Si vous avez modifié le code dans le dossier `www`, un redémarrage n'est généralement pas nécessaire (grâce au volume de montage). Cependant, cette commande est utile après une modification des variables d'environnement dans `docker-compose.yml`.

```bash
docker compose restart
```

### 🏗️ Reconstruire une image (après modification du `Dockerfile`)

Si vous avez modifié le `Dockerfile` (par exemple, pour ajouter une extension PHP), vous devez reconstruire l'image du service `app`. Puis redémarrez le service pour qu'il utilise la nouvelle image :


```bash
docker compose build app
docker compose up -d
```

### 👁️ Afficher les logs (journaux)

Utile pour le débogage et voir les erreurs PHP ou les logs MySQL.

Pour tous les services :

```bash
docker compose logs -f
```

Pour un service spécifique (ex. l'application PHP) :

```bash
docker compose logs -f app
```

(Utilisez `Ctrl+C` pour sortir des logs.)

### 🛑 Arrêter les services

Arrête les conteneurs sans les supprimer (ils peuvent être redémarrés rapidement avec `docker compose start`).

```bash
docker compose stop
```

### 🗑️ Arrêter et Supprimer l'environnement

Arrête les conteneurs, les supprime et supprime le réseau. Le drapeau `-v` supprime également les volumes de données (ce qui efface votre base de données \!).

**Attention : Utilisez cette commande avec `-v` uniquement si vous voulez perdre les données MySQL.**

```bash
# Arrête, supprime les conteneurs et les réseaux
docker compose down
```

```bash
# Arrête, supprime les conteneurs ET EFFACE les données de la base de données
docker compose down -v
```

### 🐚 Se connecter au conteneur (Shell/Terminal)

Pour exécuter des commandes dans le conteneur PHP (par exemple, lancer Composer, exécuter un script CLI) :

```bash
docker compose exec app bash
# ou si 'bash' n'est pas disponible (selon l'image)
# docker compose exec app sh
```

### 🖥️ Vérifier l'état des conteneurs

Affiche si les services sont démarrés (`Up`) ou arrêtés (`Exit`).

```bash
docker compose ps
```

### 🖥️ Supprimer les images restantes

```bash
docker image rm taupemeubles-app
```

### 💿 Supprimer le disque Docker de la base de données

```bash
docker volume rm taupemeubles_dbdata
```