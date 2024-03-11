# Poc & Share

## Installation

- Récupérez le projet avec ```git clone git@github.com:MDW-Guillaume/pocandshare.git```

- Allez dans le dossier avec ```cd pocandshare```

- Lancez la commande ```composer install``` 

- Configurez votre fichier .env afin de lier le projet à votre base de données

- Lancez la commande ```symfony console make:migration``` puis ```symfony console doctrine:migrations:migrate```

- Chargez les fixtures du projet avec ```symfony console doctrine:fixtures:load```

- Lancez le projet avec ```symfony serve```