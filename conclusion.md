# Conclusion du projet RunTracker

---

## Récapitulatif des fonctionnalités réalisées

En référence au cahier des charges défini dans `specifications.md`, voici le bilan des tâches accomplies :

### Système d'authentification

| Fonctionnalité | Statut | Détails |
|----------------|--------|---------|
| Inscription d'utilisateur | Réalisé | Formulaire complet avec validation des champs, vérification de l'unicité du nom d'utilisateur et de l'email |
| Connexion/Déconnexion | Réalisé | Authentification sécurisée avec gestion des sessions PHP |
| Gestion des sessions | Réalisé | Protection des pages privées, redirection automatique |
| Validation des données | Réalisé | Validation des entrées, validation email, confirmation mot de passe |
| Hashage des mots de passe | Réalisé | Utilisation de `password_hash()` avec algorithme PASSWORD_DEFAULT |

### Gestion des courses

| Fonctionnalité | Statut | Détails |
|----------------|--------|---------|
| Ajout manuel d'une course | Réalisé | Formulaire avec date, distance, durée et notes |
| Calcul automatique de l'allure | Réalisé | Calcul d'allure en min/km à partir de la durée et distance |
| Validation des données saisies | Réalisé | Vérification du format HH:MM:SS (fonctionnel mais pas pratique, on s'en est rendu compte trop tard), distance positive, date obligatoire |
| Historique des courses | Réalisé | Affichage sous forme de tableau avec tri par date |
| Suppression d'une course | Réalisé | Bouton de suppression avec confirmation |

### Visualisation des données dans les formulaires

| Fonctionnalité | Statut | Détails |
|----------------|--------|---------|
| Graphiques de progression | Réalisé | Graphique ECharts avec distance, durée et allure |
| Statistiques personnalisées | Réalisé | Visualisation de l'évolution dans le temps |
| Filtres par période | Non réalisé | Fonctionnalité non implémentée |

### Architecture et pages

| Page | Statut | Fichier |
|------|--------|---------|
| Page d'Accueil / Dashboard | Réalisé | `index.php` |
| Page de Connexion | Réalisé | `login.php` |
| Page d'Inscription | Réalisé | `register.php` |
| Page Course (création) | Réalisé | `create.php` |
| Page Progression | Réalisé | `progress.php` |
| Page Contact | Réalisé | `contact.php` |

### Email, internationalisation, responsivité et base de données

| Fonctionnalité | Détails |
|----------------|---------|
| Internationalisation (i18n) | Support de 3 langues : Français, English, Deutsch |
| Envoi d'emails | Email de bienvenue à l'inscription et formulaire de contact fonctionnel via PHPMailer |
| Design responsive | Utilisation du framework CSS Pico pour une interface moderne et adaptative |
| Création automatique de la BDD | La base de données et les tables sont créées automatiquement si elles n'existent pas |

---

## Points d'amélioration possibles

### Fonctionnalités à ajouter (pas ajoutées par manque de temps ou compétences)

1. **Filtres par période** : Permettre aux utilisateurs de filtrer leurs courses par semaine, mois ou année
2. **Modification d'une course** : Ajouter la possibilité de modifier une course existante (actuellement seule la suppression est possible)
3. **Récupération de mot de passe** : Implémenter un système de réinitialisation par email
4. **Statistiques avancées** : Ajouter des statistiques comme la distance totale, le temps total, la meilleure allure, etc.
5. **Export des données** : Permettre l'export des courses en CSV ou PDF
6. **Objectifs personnels** : Permettre aux utilisateurs de définir des objectifs (distance hebdomadaire, etc.)

### Améliorations techniques

1. **Tests unitaires** : Ajouter des tests PHPUnit pour garantir la stabilité du code
2. **Validation côté client** : Ajouter une validation JavaScript pour une meilleure expérience utilisateur

### Améliorations de sécurité

1. **Politique de mots de passe** : Imposer des critères de complexité pour les mots de passe (notamment avec des regexs par exemple pour forcer un caractère spécial, ...)

---

## Ce que nous avons appris

### Compétences techniques

1. **Programmation Orientée Objet en PHP** : Utilisation de classes, namespaces, interfaces et autoloading pour structurer le code au mieux

2. **Sécurité web** :
   - Hashage des mots de passe avec les fonctions natives PHP
   - Protection contre les injections SQL avec PDO et les requêtes préparées
   - Sécurisation des entrées utilisateur avec `htmlspecialchars()`
   - Validation des données côté serveur

3. **Gestion de base de données** :
   - Conception de schéma relationnel (users, runs)
   - Utilisation de clés étrangères et d'index pour l'optimisation
   - Requêtes SQL avec PDO

4. **Internationalisation** :
   - Mise en place d'un système de traduction multi-langues
   - Gestion des préférences utilisateur via cookies

5. **Intégration d'APIs tierces** :
   - Utilisation de PHPMailer pour l'envoi d'emails
   - Intégration de la bibliothèque ECharts pour les graphiques

6. **Frontend moderne** :
   - JavaScript pour l'interactivité (graphiques avec ECharts, confirmations)

### Compétences méthodologiques / de travail en équipe

1. **Gestion de projet** : Définition d'un cahier des charges clair et suivi des fonctionnalités

2. **Versioning** : Utilisation de Git pour le contrôle de version et la collaboration

3. **Configuration** : Séparation des fichiers de configuration sensibles (database.ini, mail.ini) avec des fichiers d'exemple

4. **Documentation** : Rédaction de spécifications et documentation du code avec des commentaires PHPDoc (même si parfois généré par IA, puis révisé personellement)

---

## Conclusion générale

Le projet RunTracker a permis de développer une application web fonctionnelle de suivi de course à pied, répondant à la majorité des objectifs fixés dans le cahier des charges. L'application offre une expérience utilisateur complète avec l'inscription, la connexion, l'ajout de courses, la visualisation de la progression et un formulaire de contact.

Les principales réussites du projet sont :
- Une architecture de code propre (au possible) et maintenable
- Un système d'authentification sécurisé
- Des graphiques de progression interactifs
- Un support multilingue

---

### Conclusion personelles

#### Dylan 

Ce projet m'a beaucoup plus, je me suis amusé à intégrer certains principes de POO vu en DAI2 l'année passée (notamment le Singleton) que je n'avais pas forcément eu l'occasion de mettre en pratique auparavant. La charge de travail m'a semblé cohérente. 

Selon moi, le projet a encore beaucoup d'axes d'améliorations, notamment l'interface qui est assez primaire (pour ne pas dire dégeulasse ^^) ainsi que les fonctionnalités utilisateurs qui pourraient/devraient être bien plus poussées dans un vrai projet. Nous ne nous sommes pas non plus attardés sur l'optimisation en elle-même, qui pourrait être revue sur certains points (réutilisation de code et du header/footer par exemple). 

Merci pour ce projet et au plaisir de vous croiser dans les couloirs ou dans une future unité d'enseignement!

#### Valentin
