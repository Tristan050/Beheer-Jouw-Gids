### Jouw gids beheer

Stap 1: Lokaal instellen: je hebt mysql nodig en apache dus het kan via xammp 

Stap 2: Composer installeren : composer install

Stap 3: tailwind installeren: npm install && npm run build

Stap 4: Maak een .env file, zet daar de credentials in. De mail services zullen niet werken omdat in de productie site worden de credentials via de server zelf gedaan dus is het aan te raden om debug modus aan te hebben in .env, als je wel graag de mail wil testen kun je met mailtrap 

Stap 5: run migrations, run het script: run_migrations in /database

Stap 6: om in te loggen kun je met de user T1ester@gmail.com inloggen ik raad aan om debug mode aan 