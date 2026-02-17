## Opdrachtuitwerking
Hieronder beschrijf ik mijn gedachtes bij de uitwerking van de opdracht.

Zojuist heb ik de opdracht doorgenomen. Een schematisch plan maak ik hieruit op

1. Maak migrations en bijbehorende models;
2. Maak seeders;
3. Maak een API endpoint;
4. Maak een event dat de kosten van de dag opslaat;
5. Maak een event dat een notificatie maakt bij afwijkend (10%) verbruik.

## Stap 1: maak migrations en bijbehorende models
Als eerste denk ik aan de volgende tabellen: ean (ean_code, adresgegevens, netbeheerder, cost_per_kwh_in_euro), meter_readings (ean_code, kwh_total, timestamp), daily_costs (ean_code, kwh_used, cost_in_euro, timestamp).
De relatie is tussen ean en meter_readings (ean_code) en tussen ean en daily_costs (ean_code)
De reden dat ik geen aparte contract en user tabel opstel is om de opdracht niet te uitgebreid te maken, maar zou in een verdere uitwerking nog meer kunnen opsplitsen.

- `php artisan make:model Ean -mf`
- `php artisan make:model MeterReadings -mf`
- `php artisan make:model DailyCosts -mf`

## Stap 2: Maak seeders

De relaties heb ik nu gelegd en de factories zijn aangemaakt. Tijdens het migreren worden alle tabellen en relaties aangemaakt.

--

Deze stap duurde langer dan ik had verwacht, maar de seeders zijn nu naar mijn zin om ook als data te kunnen gebruiken voor de opdracht

## Stap 3: maak een API endpoint
Quick note: ik wilde gebruik maken van `php artisan install:api`, maar gezien er geen andere packages zijn toegestaan focus ik mij nu enkel op de route/controller/method en niet de authenticatie.
Het routes/api.php bestand aangemaakt en deze verzorgt dat ik stateless de api kan aanroepen.
De api heeft op dit moment een 'connections' segment dat verwijst naar de EAN, en daarin een readings method om deze te kunnen opslaan. Gezien we dit eenmalig doen en niet wijzigen is dit een POST. Ook willen we de input valideren, dit doen we in het StoreConnectionsReadingsRequest
- `php artisan make:controller ConnectionsController`
- `php artisan make:request StoreConnectionsReadingsRequest`

De validatie is eigenlijk enkel conform wat ik weet van hoe deze data eruit moet zien. De timezone was even een double check maar gezien mijn database de timezone van mn laptop aanhoud ziet dat er ook goed uit.

## Stap 4: Maak een event dat de kosten van de dag opmaakt
Ik heb getwijfeld om, als elke keer de metingen worden opgeslagen, te checken of we ook de kosten wilden berekenen. Dit lijkt mij echter onveilig als data niet goed wordt doorgegeven. Daarom laat ik een cron draaien om dit af te vangen. Voor nu simuleer ik dat met een commando.
Kleine rekenfout met de seeders, maar nu opgelost met de kosten berekening. Het commando `php artisan app:calculate-daily-costs` berekent de prijs d.m.v. de eerste en laatste timestamp van de dag en daar het verschil van te noteren. Dit keer de prijs van de kwh in de ean tabel (omdat we geen contract hebben) maakt de juiste prijs.
Voor nu is het een commando in de cron aangezet. Een schonere manier is om dit in een event te zetten of op een later tijdstip dan vandaag (bijv. 1 of 2 uur later) het bedrag te berekenen, zodat alle data zeker binnen is.
Kleine fix: Imported Schedule voor console.php

## Stap 5: Maak een event dat een notificatie maakt bij afwijkend (10%) verbruik
- Dit kan het beste door de seeders aan te passen en meer data te laten genereren. Ik maak het zo dat we van de laatste 28 dagen meter_readings en daily_costs hebben voor 10 EAN's. Ook verhoog ik hier de kwh waardes met 15% per week.
- Met het commando `php artisan app:monitor-weekly-consumption` wil ik wekelijks kijken of er van de 2 volle afgelopen weken een verandering van 10% of meer heeft plaatsgevonden.
- Indien het geval (wat altijd zo is) wordt de Laravel Notification aangeroepen die de test user een notificatie mailt. Omdat er nog geen relatie is tussen ean en notifable entities heb ik voor nu gekozen om dit met de (test) User te doen.

## Uitwerkingen en verbeteringen
- Ga uit van duizenden EAN's
  - Zorg voor meer data in de seeder;
  - Houd rekening met verwerking van datasets d.m.v. chunks en jobs;
- Think the Laravel way:
  - ConnectionsController zou beter ConnectionReadingController gezien readings [genest](https://laravel.com/docs/12.x/controllers#restful-nested-resources) zitten in de connection;
  - readings zou dan ook beter 'store' kunnen zijn, om de CRUD methodiek van Laravel aan te houden
  - Gebruik bij Relationships 'with' om N+1 probleem te voorkomen
  - Controleer bij Eloquent gebruik of je echt de beste methods hanteert
    - whereBetween is bij de commando's vrij zwaar en onspecifiek, er is ook whereDate
    - orderBy heeft bij de commando's geen effect
    - create() hoeft geen created instance terug te geven
- Ean als model en Connection (in controller) is a. inconsistent en b. geen goede naamgeving. Connection(s) is hier meer passend dan 'id' of 'barcode' 
