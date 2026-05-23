# 🎓 Gestion Emploi du Temps — FLDM

![Laravel](https://img.shields.io/badge/Laravel-12-red?logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2-blue?logo=php)
![License](https://img.shields.io/badge/License-MIT-yellow)
![Status](https://img.shields.io/badge/Status-En%20développement-orange)

> Plateforme web full-stack de **digitalisation académique** pour la Faculté de Droit de Meknès (FLDM) — gestion des emplois du temps, des salles et des enseignants.

---

## 📸 Aperçu

### 🏠 Page d'accueil
![Page d'accueil](https://github.com/user-attachments/assets/264499ab-67c0-4fcf-85fd-99e88e5f3ebd)

### 📊 Tableau de bord Admin
![Tableau de bord](https://github.com/user-attachments/assets/8d3eddb8-fd24-4b83-88bb-90fafc0475d9)

### ✅ Tests automatisés — 115 passed (256 assertions)
![Tests](https://github.com/user-attachments/assets/c9f8669b-6a03-404f-a523-25c1e8e4a9d8)

---

## ✨ Fonctionnalités

- 📅 Génération et gestion des emplois du temps
- 👨‍🏫 Gestion des enseignants et leurs disponibilités
- 🏫 Gestion des salles et amphithéâtres
- 👥 Gestion des groupes et filières
- 📄 Export PDF des emplois du temps (DomPDF)
- 📊 Export Excel des données (Maatwebsite Excel)
- 🔐 Authentification et gestion des rôles (Laravel UI + Sanctum)

---

## 🏗️ Architecture

```
gestion-emploi-temps-fldm/
├── app/
│   ├── Http/Controllers/    # Contrôleurs Laravel
│   ├── Models/              # Modèles Eloquent
│   └── ...
├── database/
│   ├── migrations/          # Structure de la base de données
│   └── seeders/             # Données de test
├── resources/
│   └── views/               # Templates Blade
├── routes/
│   └── web.php              # Routes de l'application
└── public/                  # Assets publics
```

---

## 🔧 Stack technique

| Composant | Technologie |
|-----------|-------------|
| Backend | Laravel 12 / PHP 8.2 |
| Frontend | Blade + Bootstrap (Laravel UI) |
| Base de données | MySQL |
| Export PDF | barryvdh/laravel-dompdf |
| Export Excel | maatwebsite/excel + PhpSpreadsheet |
| Auth | Laravel Sanctum + Laravel UI |
| Tests | PHPUnit 11 |

---

## 🚀 Installation

### Prérequis
- PHP >= 8.2
- Composer
- MySQL
- Node.js & NPM

### Étapes

```bash
# 1. Cloner le repo
git clone https://github.com/firdawsM7/gestion-emploi-temps-fldm.git
cd gestion-emploi-temps-fldm

# 2. Installer les dépendances PHP
composer install

# 3. Installer les dépendances JS
npm install && npm run dev

# 4. Configurer l'environnement
cp .env.example .env
php artisan key:generate

# 5. Configurer la base de données dans .env
# DB_DATABASE=gestion_emploi_temps
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Exécuter les migrations
php artisan migrate --seed

# 7. Lancer le serveur
php artisan serve
```

L'application sera disponible sur `http://localhost:8000`

---

## 🗄️ Base de données

```bash
# Créer la base de données
php artisan migrate

# Avec les données de test
php artisan migrate --seed

# Réinitialiser complètement
php artisan migrate:fresh --seed
```

---

## 🧪 Tests

```bash
php artisan test
```

---

## 👤 Auteur

**firdawsM7** · [GitHub](https://github.com/firdawsM7)

---

## 📄 Licence

Ce projet est sous licence [MIT](LICENSE).
