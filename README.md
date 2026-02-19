# MediaTekFormation (Évolutions)

## Présentation du projet
Ce dépôt contient la version évoluée de l'application **MediaTekFormation**.
Il s'agit d'une reprise du projet existant pour y intégrer une interface d'administration sécurisée, des tests automatisés et un déploiement continu.
---

## Fonctionnalités ajoutées (Back-Office)

L'objectif principal de cette évolution était de permettre au personnel de MediaTek86 de gérer le catalogue de formations en toute autonomie.

### 1. Authentification et Sécurité
L'accès à la partie administration (`/admin`) est désormais protégé.
- Système de Login sécurisé.
- Protection contre les failles CSRF sur tous les formulaires.
- Hachage des mots de passe en base de données.

<img width="1394" height="268" alt="image" src="https://github.com/user-attachments/assets/1544cd50-aa67-48eb-9b07-2eaf774ad5ae" />


### 2. Gestion des Formations
Interface complète pour gérer les vidéos :
- Ajout et modification avec contrôle de saisie (dates cohérentes, champs obligatoires).
- Suppression sécurisée.
- Liaison avec les playlists et catégories.

<img width="1356" height="685" alt="image" src="https://github.com/user-attachments/assets/16dd09db-09f7-4acd-8ee3-7d2603d48fba" />

### 3. Gestion des Playlists
Interface permettant de créer et modifier les playlists.
- **Règle de gestion :** Il est impossible de supprimer une playlist si elle contient encore des formations (message d'avertissement à l'utilisateur).
- Affichage en lecture seule des formations contenues lors de la modification.

<img width="1366" height="637" alt="image" src="https://github.com/user-attachments/assets/90255739-cd48-480a-b3da-dbc8d61a90c8" />



### 4. Gestion des Catégories
Interface permettant d'ajouter et supprimer des catégories :
- Formulaire d'ajout rapide et liste des catégories sur la même page.
- Protection contre la suppression de catégories utilisées.

<img width="1335" height="696" alt="image" src="https://github.com/user-attachments/assets/6316d32f-6783-4e22-a215-d12cf26a5fac" />


---

## Qualité et Tests
- **Nettoyage du code :** Analyse et correction via **SonarLint** (Code smells, sécurité).
- **Tests Automatisés :** Mise en place d'une suite de tests avec **PHPUnit** :
    - Tests Unitaires (Entités).
    - Tests d'Intégration (Repository et Validations).
    - Tests Fonctionnels (Navigation, Tris, Filtres, Code HTTP).

---

## Installation et Utilisation en local

Pour tester cette application sur votre machine :

### Prérequis
- PHP 8.1 ou supérieur
- Composer
- Serveur MySQL (Wamp/Xamp/Laragon)
- Symfony CLI (recommandé)

### Mode opératoire
1.  **Cloner le dépôt :**
    ```bash
    git clone https://github.com/TON_PSEUDO/mediatekformation.git
    cd mediatekformation
    ```

2.  **Installer les dépendances :**
    ```bash
    composer install
    ```

3.  **Configuration :**
    - Dupliquer le fichier `.env` en `.env.local`.
    - Configurer la variable `DATABASE_URL` avec vos accès MySQL locaux.

4.  **Base de données :**
    - Créer la base : `php bin/console doctrine:database:create`
    - Importer le fichier `mediatekformation.sql` (fourni à la racine) pour avoir les données initiales.
    - Mettre à jour la structure (table User) : `php bin/console doctrine:migrations:migrate` (ou `schema:update`)
    - Créer l'administrateur : `php bin/console doctrine:fixtures:load --append`

5.  **Lancer le serveur :**
    ```bash
    symfony server:start
    ```
    Accédez au site via `http://localhost:8000`.

---

## Accès à la version en ligne

L'application est déployée et accessible publiquement.

🔗 **Lien vers le site :** [[http://mediatekducci.page.gd]]

Pour tester la partie Back-Office, veuillez ajouter `/admin` à l'URL ou cliquer sur "Login".
Les urls sont /admin/categories /admin/playlists /admin/formations
