#!/bin/bash

echo "========================================"
echo "Installation d'IDSeMarket Laravel 12"
echo "========================================"
echo

echo "[1/8] Installation des dependances PHP..."
composer install
if [ $? -ne 0 ]; then
    echo "ERREUR: Composer install a echoue!"
    exit 1
fi

echo "[2/8] Installation des dependances Node.js..."
npm install
if [ $? -ne 0 ]; then
    echo "ERREUR: NPM install a echoue!"
    exit 1
fi

echo "[3/8] Configuration de l'environnement..."
if [ ! -f .env ]; then
    if [ -f .env.example ]; then
        cp .env.example .env
        echo "Fichier .env cree a partir de .env.example"
    else
        echo "ATTENTION: Fichier .env.example non trouve!"
        echo "Veuillez creer manuellement le fichier .env"
    fi
else
    echo "Fichier .env existe deja"
fi

echo "[4/8] Generation de la cle d'application..."
php artisan key:generate
if [ $? -ne 0 ]; then
    echo "ERREUR: Generation de la cle a echoue!"
    exit 1
fi

echo "[5/8] Compilation des assets frontend..."
npm run build
if [ $? -ne 0 ]; then
    echo "ERREUR: Compilation des assets a echoue!"
    exit 1
fi

echo "[6/8] Nettoyage du cache..."
php artisan optimize:clear

echo "[7/8] Verification des permissions..."
mkdir -p storage/logs
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views

# Donner les bonnes permissions (Linux/Mac)
chmod -R 775 storage
chmod -R 775 bootstrap/cache

echo "[8/8] Installation terminee avec succes!"
echo
echo "========================================"
echo "ETAPES SUIVANTES:"
echo "========================================"
echo "1. Configurer la base de donnees dans .env"
echo "2. Executer: php artisan migrate"
echo "3. Demarrer le serveur: php artisan serve"
echo "4. Ouvrir: http://127.0.0.1:8000"
echo "========================================"
echo
