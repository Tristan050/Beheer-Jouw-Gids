# Beheer Jouw Gids

Dit is het beheerportaal voor Jouw Gids. In dit project beheer je onder andere leefgebieden, functies, aandachtspunten, hulpbronnen, links, vragenlijsten en koppelingen.

De applicatie is een PHP-project met MySQL, Composer en Tailwind CSS. Routes lopen via `index.php` en `.htaccess`, bijvoorbeeld `/admin`, `/links` en `/link-aandachtspunt-koppelingen` .

## Vereisten

- PHP 8.2 of nieuwer
- MySQL of MariaDB
- Apache met `mod_rewrite` aan
- Composer
- npm

Lokaal kan dit bijvoorbeeld met XAMPP voor Apache/MySQL en losse installaties van Composer en npm.

## Project lokaal starten

1. Clone of plaats het project in je Apache webroot.

   Voor XAMPP is dat meestal:

   ```bash
   C:\xampp\htdocs\Beheer-Jouw-Gids
   ```

2. Installeer PHP dependencies.

   ```bash
   composer install
   ```

3. Installeer frontend dependencies en bouw de CSS.

   ```bash
   npm install
   npm run build
   ```

   Tijdens development:

   ```bash
   npm run dev
   ```

4. Maak een `.env` bestand.

   Kopieer `.env.example` naar `.env` en vul minimaal de databasegegevens in:
   ```bash
   cp .env.example .env
   ```

   ```env
   DB_HOST=localhost
   DB_NAME=jouw_database_naam
   DB_USER=root
   DB_PASS=

   APP_DEBUG=true
   APP_BASE_URL=http://localhost/Beheer-Jouw-Gids
   ```

   Staat het project direct op een eigen host of virtual host, dan kan `APP_BASE_URL` bijvoorbeeld `http://localhost:8082` zijn.

5. Maak een database aan in MySQL.

   Gebruik dezelfde naam als `DB_NAME` in `.env`.

6. Run de migrations.

   ```bash
   php database/run_migrations.php
   ```

7. Open de site in de browser.

   Bijvoorbeeld:

   ```text
   http://localhost/Beheer-Jouw-Gids
   ```
## Inloggen

Voor lokaal testen staat er een testgebruiker in de seed-informatie:

```text
Email: T1ester@gmail.com
Wachtwoord: testuser
```

Gebruik bij lokaal testen bij voorkeur `APP_DEBUG=true`. De OTP/mailflow kan dan makkelijker lokaal getest worden. Wil je echte mail testen, gebruik dan bijvoorbeeld Mailtrap en vul de `MAIL_*` waarden in `.env`.

## Hoe het beheer werkt

Het project gebruikt een repository/service/controller structuur:

- `controllers/` handelen requests af.
- `services/` bevatten validatie en businesslogic.
- `repositories/` database logic.
- `views/` bevatten de HTML/PHP pages.
- `libs/pages.php` bepaalt welke URL naar welke controller gaat.

Belangrijke beheeronderdelen:

- `Leefgebieden`: hoofdonderwerpen van de scan.
- `Functies`: onderdelen binnen een leefgebied.
- `Aandachtspunten`: concrete punten die aan functies hangen.
- `Hulpbronnen`: hulpbronnen die gekoppeld kunnen worden aan leefgebieden.
- `Links`: websites met optioneel belangrijk bericht en pop-up instelling.
- `Link koppelingen`: koppelt links aan aandachtspunten via `gids_aandachtspunt_koppeltabel`.
- `Vragenlijsten`: beheer van vragenlijstvragen en opties.

Organisatiebeheer en de oude verdiepingskoppelingen bestaan nog als bestanden, maar zijn niet meer bereikbaar via routes of navigatie.

## Nieuwe code toevoegen

Omdat Composer classmap-autoloading gebruikt, moet je na nieuwe controllers, services of repositories de autoload opnieuw bouwen:

```bash
composer dump-autoload
```
