RESUMé : 
Le projet consiste en une application web de lecture de webcomics en ligne développée avec Symfony. 
Le processus de développement a commencé par la création d'une base de données avec une entité utilisateur et 
l'utilisation du bundle de sécurité pour gérer l'authentification et l'autorisation d'accès. Ensuite, les fonctionnalités 
principales de l'application ont été développées, y compris la gestion des webcomics, des catégories, des chapitres et des images. 
L'application permet aux utilisateurs de créer un compte, de parcourir les webcomics disponibles, de filtrer les webcomics 
par catégorie et de lire les chapitres associés.

necessite :
composer pour les commandes
wamp pour la bdd
mailhog pour simuler le mailing

configurer le .env
JWT_SECRET='u05e0re1b1e0g2r0e!'

une fois la bdd remise en route avec le bon lien de connection :
php bin/console doctrine:migrations:migrate

pour gnerer de fausses donné :
symfony console d:f:l 

lancer le serveur local
symfony serve -d