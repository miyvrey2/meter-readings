## Opdrachtomschrijving
We zien graag dat je een kleine API bouwt om inzicht te krijgen in je vaardigheden. Focus op de
PHP code en separation of concern. Gebruik hiervoor Laravel 12.x zonder extra packages.

## Meterstanden verwerken
- Klanten sturen ieder kwartier meterstanden (kWh) door voor een aansluiting (EAN). Creëer
hiervoor het volgende endpoint:
```POST /api/connections/{ean}/readings```
```{"timestamp":"2026-02-01T03:45:00+00:00","kwh_total":12345}```
Deze data moet worden gevalideerd en opgeslagen in de `meter_readings` tabel. 
- Wanneer de laatste meterstand van de dag is opgeslagen, moeten de kosten van die dag worden gecalculeerd op basis van het contract van de klant. Deze kosten slaan we op in de `daily_costs`
tabel.
- Wanneer het weekverbruik meer dan 10% afwijkt van het verbruik van de voorgaande week
sturen we de klant een notificatie

## Tijdsduur/Aanleveren
Spendeer niet meer dan 2 uur aan het bouwen van de applicatie.
