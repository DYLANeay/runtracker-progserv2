# RunTracker - Application Web de Suivi de Course à Pied

RunTracker est une application web simple permettant aux coureurs de saisir manuellement leurs séances de course et de suivre leur progression à travers des graphiques intuitifs.

---

## Objectifs du Projet

- Créer une application web complète permettant le suivi personnel des activités de course à pied
- Développer une interface utilisateur intuitive et responsive
- Implémenter un système d'authentification sécurisé
- Fournir des outils de visualisation de données pour motiver les utilisateurs

---

## Technologies Utilisées

| Catégorie       | Technologies   |
| --------------- | -------------- |
| Frontend        | HTML5, CSS3    |
| Backend         | PHP 8.x        |
| Base de données | MySQL 8.x      |
| Serveur web     | Apache/Nginx   |
| Graphiques      | ECharts/JS     |


---

## Architecture de l'Application

### Structure des Pages

| Page                                     | Description                                                                                       |
| ---------------------------------------- | ------------------------------------------------------------------------------------------------- |
| Page d'Accueil (index.php)               | Présentation de l'application et résumé des dernières activités (pour les utilisateurs connectés) |
| Page de Connexion/Inscription (auth.php) | Formulaire de connexion et d'inscription, gestion des sessions utilisateur                        |
| Page Course (course.php)                 | Formulaire de saisie d'une nouvelle course et historique des courses récentes                     |
| Page Progression (progression.php)       | Graphiques de performance et statistiques personnalisées                                          |
| Page Contact (contact.php)               | Formulaire de contact et informations sur l'équipe de développement                               |

---

## Base de Données

### Table users

| Champ      | Type                             | Description                |
| ---------- | -------------------------------- | -------------------------- |
| id         | INT, PRIMARY KEY, AUTO_INCREMENT | Identifiant unique         |
| username   | VARCHAR(50), UNIQUE              | Nom d'utilisateur          |
| email      | VARCHAR(100), UNIQUE             | Adresse email              |
| password   | VARCHAR(255)                     | Mot de passe haché         |
| created_at | DATETIME                         | Date de création du compte |

### Table runs

| Champ      | Type                             | Description                  |
| ---------- | -------------------------------- | ---------------------------- |
| id         | INT, PRIMARY KEY, AUTO_INCREMENT | Identifiant unique           |
| user_id    | INT, FOREIGN KEY                 | Identifiant de l'utilisateur |
| date       | DATE                             | Date de la course            |
| distance   | DECIMAL(5,2)                     | Distance parcourue en km     |
| duration   | TIME                             | Durée de la course           |
| pace       | TIME                             | Allure moyenne               |
| notes      | TEXT                             | Notes personnelles           |
| created_at | DATETIME                         | Date d'ajout dans la base    |

---

## Fonctionnalités Principales

### Système d'Authentification

- Inscription d'utilisateur
- Connexion/Déconnexion
- Gestion des sessions
- Validation des données

### Gestion des Courses

- Ajout manuel d'une course
- Calcul automatique de l'allure
- Validation des données saisies
- Historique des courses

### Visualisation des Données

- Graphiques de progression
- Statistiques personnalisées
- Filtres par période

---

## Contraintes Techniques

- Sécurité : Hashage des mots de passe, validation des entrées
- Responsive Design : Compatible mobile et desktop (sous réserve de manque de temps)
- Performance : Optimisation des requêtes SQL

---
