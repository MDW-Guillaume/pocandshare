# Poc & Share

## Installation

- Récupérez le projet avec ```git clone git@github.com:MDW-Guillaume/pocandshare.git```

- Allez dans le dossier avec ```cd pocandshare```

- Lancez la commande ```composer install``` 

- Configurez votre fichier ```.env``` afin de lier le projet à votre base de données, à l'aide du fichier ```.env.test```

- Lancez la commande ```symfony console make:migration``` puis ```symfony console doctrine:migrations:migrate```

- Chargez les fixtures du projet avec ```symfony console doctrine:fixtures:load```

- Lancez le projet avec ```symfony serve```

## Authentification

#### 2 profils sont disponibles sur la plateforme : 
 - Profil utilisateur : user@sv.fr | user
 - Profil administrateur : admin@sv.fr | admin

La plateforme est accessible à tous.

Un utilisateur connecté peut publier des projets et naviguer sur son profil afin de gérer ses projets.

Un administrateur a accès à toute la partie administration de la plateforme pour gérer les projets, les catégories, les technologies ainsi que les utilisateurs.

Un système d'inscription a été mis en place pour la création d'utilisateurs supplémentaires.