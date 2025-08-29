# 📋 Guide d'Installation - IDSeMarket Laravel 12

## 🚀 Installation Rapide (Automatique)

### Windows
```bash
# Double-cliquer sur install.bat
# OU exécuter en ligne de commande :
install.bat
```

### Linux/Mac
```bash
# Rendre le script exécutable
chmod +x install.sh

# Exécuter le script
./install.sh
```

## 📝 Installation Manuelle (Étape par Étape)

### 1. Prérequis Système
- **PHP** : 8.2 ou supérieur
- **Composer** : Dernière version
- **Node.js** : 18 ou supérieur
- **NPM** : Dernière version
- **MySQL** : 8.0 ou supérieur
- **Git** : Pour cloner le projet

### 2. Cloner le Projet
```bash
git clone https://github.com/Christian-IAI-GITHUB/Plateforme-E-commerce-Multi-Vendeurs-Laravel-12---Stage-l2-IAI-.git
cd Plateforme-E-commerce-Multi-Vendeurs-Laravel-12---Stage-l2-IAI-
```

### 3. Installer les Dépendances PHP
```bash
composer install
```

### 4. Installer les Dépendances Node.js
```bash
npm install
```

### 5. Configuration de l'Environnement
```bash
# Copier le fichier d'environnement
cp .env.example .env

# Générer la clé d'application
php artisan key:generate
```

### 6. Configuration de la Base de Données

#### 6.1 Créer la Base de Données
```sql
CREATE DATABASE idse_market CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### 6.2 Modifier le Fichier .env
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=idse_market
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe
```

### 7. Exécuter les Migrations
```bash
php artisan migrate
```

### 8. Compiler les Assets Frontend
```bash
npm run build
```

### 9. Nettoyer le Cache
```bash
php artisan optimize:clear
```

### 10. Démarrer le Serveur
```bash
php artisan serve
```

## 🌐 Accès à l'Application

- **Frontend** : http://127.0.0.1:8000
- **Admin** : http://127.0.0.1:8000/admin
- **Vendeur** : http://127.0.0.1:8000/vendor

## 🔐 Comptes par Défaut

### Administrateur
- **Email** : admin@idse.com
- **Mot de passe** : password

### Vendeur Test
- **Email** : vendor@idse.com
- **Mot de passe** : password

### Client Test
- **Email** : client@idse.com
- **Mot de passe** : password

## ⚠️ Dépannage

### Erreur "Class not found"
```bash
composer dump-autoload
php artisan optimize:clear
```

### Erreur de Permissions (Linux/Mac)
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Erreur de Base de Données
```bash
# Vérifier la connexion
php artisan tinker
DB::connection()->getPdo();
```

### Erreur d'Assets
```bash
npm run dev
# ou
npm run build
```

## 📁 Structure des Dossiers Importants

```
IDSeMarket/
├── app/                    # Code de l'application
├── config/                 # Configuration
├── database/               # Migrations et seeders
├── public/                 # Fichiers publics
├── resources/              # Vues et assets
├── routes/                 # Définition des routes
├── storage/                # Logs et cache
└── .env                    # Variables d'environnement
```

## 🔄 Mise à Jour du Projet

```bash
# Récupérer les dernières modifications
git pull origin main

# Mettre à jour les dépendances
composer install
npm install

# Compiler les assets
npm run build

# Nettoyer le cache
php artisan optimize:clear
```

## 📞 Support

En cas de problème :
1. Vérifier les logs : `storage/logs/laravel.log`
2. Vérifier la console du navigateur
3. Vérifier les prérequis système
4. Consulter la documentation Laravel

---

*Dernière mise à jour : Août 2025*
