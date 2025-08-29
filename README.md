# IDSeMarket - Plateforme E-commerce Laravel 12

## 📋 Description

IDSeMarket est une plateforme e-commerce moderne développée avec Laravel 12, offrant une solution complète pour la vente en ligne avec gestion des vendeurs et des clients.

## ✨ Fonctionnalités

### 🛍️ Front-end
- **Catalogue de produits** avec filtres avancés
- **Système de panier** pour utilisateurs connectés et invités
- **Processus de commande** simplifié
- **Tableau de bord client** pour suivre les commandes
- **Interface responsive** et moderne

### 👨‍💼 Espace Vendeur
- **Gestion des produits** (CRUD complet)
- **Upload d'images** et de vidéos
- **Gestion des stocks** et des prix
- **Statistiques** et rapports de vente
- **Interface d'administration** dédiée

### 🔧 Administration
- **Gestion des utilisateurs** (clients et vendeurs)
- **Suivi des commandes** en temps réel
- **Gestion des catégories** et marques
- **Tableau de bord** avec métriques

### 💳 Système de Paiement
- **Intégration FedaPay** (Flooz/TMoney)
- **Gestion des transactions** sécurisée
- **Statuts de commande** automatisés

## 🚀 Technologies Utilisées

- **Backend** : Laravel 12, PHP 8.2+
- **Frontend** : Blade, Bootstrap 5, JavaScript
- **Base de données** : MySQL
- **Serveur** : XAMPP/Apache
- **Gestion des images** : Intervention Image

## 📦 Installation

### Prérequis
- PHP 8.2 ou supérieur
- Composer
- MySQL
- Node.js et NPM

### Étapes d'installation

1. **Cloner le repository**
   ```bash
   git clone [URL_DU_REPO]
   cd IDSeMarket
   ```

2. **Installer les dépendances PHP**
   ```bash
   composer install
   ```

3. **Installer les dépendances Node.js**
   ```bash
   npm install
   ```

4. **Configurer l'environnement**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configurer la base de données**
   - Créer une base de données MySQL
   - Mettre à jour les informations de connexion dans `.env`
   - Exécuter les migrations : `php artisan migrate`

6. **Compiler les assets**
   ```bash
   npm run build
   ```

7. **Démarrer le serveur**
   ```bash
   php artisan serve
   ```

## 🔐 Configuration

### Variables d'environnement importantes
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=idse_market
DB_USERNAME=root
DB_PASSWORD=

FEDAPAY_PUBLIC_KEY=your_public_key
FEDAPAY_SECRET_KEY=your_secret_key
FEDAPAY_ENVIRONMENT=sandbox
```

## 📁 Structure du Projet

```
IDSeMarket/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Contrôleurs admin
│   │   ├── Front/          # Contrôleurs front-end
│   │   └── Vendor/         # Contrôleurs vendeurs
│   ├── Models/             # Modèles Eloquent
│   └── Http/Requests/      # Validation des formulaires
├── resources/views/
│   ├── admin/              # Vues administration
│   ├── front/              # Vues front-end
│   └── vendor/             # Vues vendeurs
├── database/
│   ├── migrations/         # Migrations de base de données
│   └── seeders/            # Seeders pour les données de test
└── public/
    └── front/              # Assets front-end (CSS, JS, images)
```

## 🎯 Utilisation

### Créer un compte vendeur
1. S'inscrire avec le rôle "Vendeur"
2. Accéder au tableau de bord vendeur
3. Ajouter des produits avec images et descriptions

### Passer une commande
1. Parcourir le catalogue de produits
2. Ajouter au panier
3. Procéder au checkout
4. Choisir le mode de paiement
5. Confirmer la commande

## 🤝 Contribution

Ce projet est développé dans le cadre d'un stage de deuxième année à l'Institut IAI.

## 📄 Licence

Projet académique - Tous droits réservés.

## 👨‍💻 Développeur

- **Étudiant** : Stage de deuxième année
- **Institution** : Institut IAI
- **Technologies** : Laravel, PHP, MySQL, Bootstrap

---

*Dernière mise à jour : Août 2025*
