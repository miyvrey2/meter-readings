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
