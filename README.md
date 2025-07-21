EcoRide - Plateforme de Covoiturage Ecologique

## Sommaire
- [Objectif du projet](#objectif-du-projet)
- [Cahier des charges](#cahier-des-charges--spécifications-fonctionnelles)
- [Technologies utilisées](#technologies-utilisées)
- [Environnement de travail](#environnement-de-travail)
- [Mécanismes de sécurité](#mécanismes-de-sécurité)
- [Veille technologique](#veille-technologique)
- [Recherche anglophone](#situation-de-recherche)
- [Méthodologie Agile avec Trello](#suivi-agile-du-projet)


EcoRide est une application web qui permet de faciliter le covoiturage
entre particuliers. Le projet vise à encourager une mobilité responsable
en mettant en relation des conducteurs et des passagers tout en suivant
une logique d'éco-conduite et de réduction de l'empreinte carbone.
Les utilisateurs peuvent proposer des trajets, s'inscrire comme passagers,
recevoir des avis, et accumuler des crédits selon leur comportement sur le site internet.

## Cahier des charges / Spécifications fonctionnelles

* Authentification des utilisateurs (inscription, connexion, déconnexion)
* Possibilité pour les conducteurs d'ajouter modifier voir supprimer des trajets
* Possibilité pour les passagers de rechercher, réserver, annuler un trajet
* Un système de note entre passagers et conducteurs 
* Gestion des rôles : utilisateur, employé, administrateur
* Attribution de crédits aux utilisateurs (chauffeur, passager)
* Tableau de bord employé pour valider les avis (ils pourront être rejetés)
* Tableau de bord administrateur pour créer des comptes employés, 
suspendre des utilisateurs et afficher des statistiques (dont un graphique)


## Technologies utilisées

* MAMP comme environnement de développement local
* PHP 8 avec architecture MVC
* HTML5/CSS 3 pour la structure et le style
* Chart.js pour les graphiques statistiques en admin
* MySQL pour la base de données relationnelle

LES RAISONS DE CES CHOIX ?

PHP/MVC est un standard simple et efficace pour des projets web.
MAMP permet de travailler localement rapidement avec Apache/MySQL préconfigurés.
MySQL est adapté à une gestion structurée de données.


## Environnement de travail
* Code écrit avec Visual Studio Code
* Navigateur Safari/Brave pour les tests utilisateurs
* Git pour le suivi de versions (GitHub en local et distant), branche développement et branche master)
* MAMP installé sur macOS


## Mécanismes de sécurité

* Utilisation de requêtes préparées avec PDO::prepare() pour éviter les injections SQL
* Utilisation de password_hash() et password_verify() pour les mots de passe
* Validation des données avec trim(), htmlspecialchars() pour éviter XSS
* Contrôle d'accès avec la vérification des rôles des utilisateurs (admin/employé)
* Contrôle des actions par méthodes HTTP (GET/POST)

## Veille technologique

Durant le projet, une veille a été réalisée sur les injections SQL et la gestion sécurisée des sessions utilisateurs.


## Situation de recherche

Situation de travail nécessitant une recherche sur un site anglophone

Lors de la création du tableau de bord administrateur, j’ai souhaité mettre en place des statistiques visuelles pour les trajets effectués jour après jour.

J’ai choisi d’utiliser la bibliothèque Chart.js, mais je n’avais aucune idée de comment intégrer les données.

Après de multiples essais, j’ai effectué une recherche sur un site anglais de nom de « Stack Overflow », dans le développement on dit souvent que lorsqu’on ne trouve pas une information on va à la doc, et Stack Overflow est un site bien connu d’informations complémentaires.
L’aspect communauté est très importante dans le développement, et l’expérience des autres peut nous aider à répondre à une question.
Parfois, on peut chercher à répondre à une question et finalement on trouve une réponse pour une autre interrogation que l’on avait.

En recherchant sur ce site, j’ai trouvé le cas d’une personne qui expliquait comment utiliser json_encode() en PHP pour transférer des tableaux associatifs dans du code JavaScript.

J’ai ensuite été sur le site chart.js pour pouvoir effectuer l’opération.
Cette méthode m’a permis d’afficher les données sous forme de graphique en barres, directement sur la page admin-dashboard.php, en convertissant notamment les dates et le nombre de trajets au format JSON compréhensible par Chart.js

Source de l’information 

Stack Overflow - How to use json_encode to format PHP to JavaScript for Chart.js
https://stackoverflow.com/questions/66211191/how-to-use-json-encode-to-format-php-to-javascript-for-chart

Extrait du site (en anglais)
« Don’t encode different parts of the date separately. Keep it all in PHP variables and then turn all of it into a JSON string right at the last moment, when you actually need to. »

Traduction en français :
« N’encodez pas séparément différentes parties des données. Gardez toutes les données dans des variables PHP, puis transformez-les en chaîne JSON juste au moment où vous en avez besoin. »


## Suivi agile du projet

Un tableau Kanban a été mis en place sur Trello avec les colonnes suivantes : 

* L'ensemble des fonctionnalités prévues
* A faire
* En cours de développement
* Développement (branche dev)
* Intégrées (branche main)

Un lien Trello permettra de voir l'évolution du projet.