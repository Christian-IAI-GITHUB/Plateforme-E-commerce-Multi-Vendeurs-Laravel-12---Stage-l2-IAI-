@echo off
echo ========================================
echo Installation d'IDSeMarket Laravel 12
echo ========================================
echo.

echo [1/8] Installation des dependances PHP...
composer install
if %errorlevel% neq 0 (
    echo ERREUR: Composer install a echoue!
    pause
    exit /b 1
)

echo [2/8] Installation des dependances Node.js...
npm install
if %errorlevel% neq 0 (
    echo ERREUR: NPM install a echoue!
    pause
    exit /b 1
)

echo [3/8] Configuration de l'environnement...
if not exist .env (
    if exist .env.example (
        copy .env.example .env
        echo Fichier .env cree a partir de .env.example
    ) else (
        echo ATTENTION: Fichier .env.example non trouve!
        echo Veuillez creer manuellement le fichier .env
    )
) else (
    echo Fichier .env existe deja
)

echo [4/8] Generation de la cle d'application...
php artisan key:generate
if %errorlevel% neq 0 (
    echo ERREUR: Generation de la cle a echoue!
    pause
    exit /b 1
)

echo [5/8] Compilation des assets frontend...
npm run build
if %errorlevel% neq 0 (
    echo ERREUR: Compilation des assets a echoue!
    pause
    exit /b 1
)

echo [6/8] Nettoyage du cache...
php artisan optimize:clear

echo [7/8] Verification des permissions...
if not exist storage\logs (
    mkdir storage\logs
)
if not exist storage\framework\cache (
    mkdir storage\framework\cache
)
if not exist storage\framework\sessions (
    mkdir storage\framework\sessions
)
if not exist storage\framework\views (
    mkdir storage\framework\views
)

echo [8/8] Installation terminee avec succes!
echo.
echo ========================================
echo ETAPES SUIVANTES:
echo ========================================
echo 1. Configurer la base de donnees dans .env
echo 2. Executer: php artisan migrate
echo 3. Demarrer le serveur: php artisan serve
echo 4. Ouvrir: http://127.0.0.1:8000
echo ========================================
echo.
pause
