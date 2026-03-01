README

Instructions d'installation détaillées

Ce projet nécessite plusieurs outils pour fonctionner correctement : XAMPP ou un autre serveur d’environnement de base de donnée(WAMP, Laragon), PHP 8.1 ou supérieur, Composer, Symfony CLI, Git, Visual Studio Code, Node avec NPM ou Yarn.

1. Programme d'installation XAMPP
⚠️Si vous utilisez WAMP ou LARAGON, vous pourrez y stocker votre base de données. Il n'est donc pas nécessaire de l’installer.
Site officiel : https://www.apachefriends.org/fr/index.html
Étapes :
Cliquez sur le bouton « Télécharger » correspondant à votre système d'exploitation.
Lancez l'installateur téléchargé.
Suivez les instructions à l'écran et laissez les options par défaut.
Une fois installé, ouvrez XAMPP.
Cliquez sur Démarrer pour Apache et MySQL (les deux premiers boutons).

2.Installer PHP 8.1 ou supérieur
⚠️Si vous utilisez XAMPP, PHP est déjà inclus. Il n'est donc pas nécessaire de le réinstaller.
Site officiel : https://www.php.net/downloads
Étapes :
Téléchargez la version correspondant à votre OS.
Installez PHP en suivant les instructions par défaut.
Vérifiez l'installation : ouvrez un terminal et tapez :
php -v
Vous devriez voir la version installée.
3. Installer Composer
Site officiel : https://getcomposer.org/download/
Étapes :
Téléchargez l'installateur Windows (.exe) ou suivez les instructions pour macOS/Linux.
Lancez l'installateur et suivez les instructions.
Vérifier l'installation :
compositeur -V
Vous devriez voir la version de Composer.

4. Installer Symfony CLI
Site officiel : https://symfony.com/download
Étapes :
Téléchargez l'installateur pour votre système.
Suivez les instructions pour installer Symfony CLI.
Vérifier l'installation :
symfony -v

5. Installer Git
Site officiel : https://git-scm.com/downloads
Étapes :
Téléchargez Git pour votre système.
Installez-le en laissant les options par défaut.
Vérifier l'installation :
git --version

6. Programme d'installation de Visual Studio Code
Site officiel : https://code.visualstudio.com/
Étapes :
Téléchargez le programme d'installation correspondant à votre système.
Installer Visual Studio Code.

7. Installer Node avec NPM
Site officiel : https://nodejs.org
Télécharge la version LTS (recommandée)
Lance l’installation
Clique sur Next jusqu’à la fin (vous pouvez laisser les options par défaut)
Vérifiez que ça marche
Ouvrez un terminal avec cmd ou PowerShell pour Windows, Terminal pour  Mac et Linux
Tapez : node -v
Puis : npm -v
Si vous voyez des numéros de version, c’est bon !

8. Ou Installer Yarn
🔹 Prérequis
Avant d'installer Yarn, il est nécessaire d'avoir installé :
Node.js (version 18 ou supérieure recommandée)
Vérifiez que Node.js est installé :
nœud -v -v

Si une version s'affiche, Node est bien installé.
Sinon, téléchargez-le depuis le site officiel :

👉 Node.js

🔹 Installation de Yarn
✅ Méthode recommandée (via Corepack – inclus avec Node.js ≥ 16.10)

Depuis Node.js 16.10, Corepack est inclus par défaut.

1️⃣ Pack de base Activer
activer corepack
2️⃣ Installer la version stable de Yarn
corepack préparer yarn@stable --activer--activer
3️⃣ Vérifier l'installation
fil -v-v
Si un numéro de version s'affiche, l'installation est réussie ✅
🔹 Alternative d'installation (si Corepack ne fonctionne pas)
Si nécessaire, vous pouvez installer Yarn globalement avec npm : npm install -g yarninstaller -g yarn
Puis vérifier : fil -v-v
🔹 Installation des dépendances du frontend
Une fois Yarn installé :
Aller dans le dossier frontend : interface CDl'extrémité avant
Installer les dépendances : installation de yarn
🔹 Lancer le frontend du projet
développeur de Yarn
ou selon la configuration : début de filcommencer
ou choco installer le fil

9. Importer la base de données
Dans les fichiers du projet, il existe un fichier nommé produit_local.sql
Étapes : 1.Ouvrez ce fichier avec Visual Studio Code. 
         2.Sélectionnez tout le contenu (CTRL + A). 
         3. Copiez tout le contenu (CTRL + C).
Ensuite :
Ouvrez votre navigateur et allez sur : http://127.0.0.1/phpmyadmin/
Identifiants : Nom d'utilisateur : root Mot de passe : (laisser vide)
Cliquez sur Se connecter.
Cliquez sur le logo maison en haut à gauche.
Cliquez sur Bases de données.
Dans le champ Nom de base de données, écrivez exactement : produit_local
Cliquez sur Créer.
Dans le menu de gauche, cliquez sur la base de données produit_local
Cliquez sur l'onglet SQL.
Collez le code copié précédemment (CTRL + V).
Cliquez sur Exécuter.
La base de données est maintenant installée.

II.	Liste des variables d'environnement nécessaires
	
  JWT_SECRET=...


III. 	Commandes de lancement (dev et prod)

Ouvrez Visual Studio Code.
Cliquez sur Open Folder et sélectionnez le dossier du projet back-end.
En haut, cliquez sur Terminal, puis Nouveau Terminal.
Une fenêtre noire apparaît en bas avec une ligne ressemblant à : 
PS C:\chemin\vers\le\projet>
Tapez la commande suivante : 
composer install php bin/console doctrine:migrations:migrate
Si un message demande une confirmation, tapez : Oui
Lancez ensuite le serveur local avec : symfony serve
Cette commande lance le serveur local de Symfony.
Ensuite, en haut, cliquez sur File, puis New Windows.
Cliquez sur Open Folder et sélectionnez le dossier du projet front-end.
En haut, cliquez sur Terminal, puis Nouveau Terminal.
Tapez la commande suivante :  npm start
Ouvrez votre navigateur internet et tapez dans la barre d'adresse :
http://127.0.0.1:3000/login
Le site devrait maintenant s'afficher.

IV.	Structure du projet avec explications

Le projet est séparé en deux dossier : Produit_Local_Frontend et Produit_Local_Backend.
🔹 Produit_Local_Backend (Symfony)
Le dossier backend/ contient l’API développée avec Symfony.
src/ : Contient le code principal (Controllers, Entities, Services, Repository, Security)
config/ : Fichiers de configuration
migrations/ : Fichiers de migration de base de données
public/ : Point d’entrée de l’application (index.php)
.env : Variables d’environnement
🔹  Produit_Local_Frontend (React)
Le dossier frontend/ contient l’application cliente développée avec React.
src/ : Composants React et pages de User et Admin, Login pour la page de connexion, ProtectedRoute pour protéger les composants React et page Admin, services API
public/ : Fichiers statiques
package.json : Dépendances et scripts npm

