# meter-readings
A simple application built with Laravel, designed to store and calculate gas consuming and prices. Build as requested in the assignment file [OPDRACHT.md](OPDRACHT.md). Thoughts and considerations are documented in the assignment elaboration file [OPDRACHT-UITWERKING.md](OPDRACHT-UITWERKING.md).

## Installation
1. Clone the repository:
   ```git clone git@github.com:miyvrey2/meter-readings.git```
2. Change directory:
   ```cd meter-readings```
3. Install dependencies:
   ```composer install```
4. Copy the `.env.example` file to `.env`:
   ```cp .env.example .env```
5. Generate the application key:
   ```php artisan key:generate```
6. Run the migrations and seed the database:
   ```php artisan migrate --seed```

## Usage
1. Inserting via API
   ```POST /api/connections/{ean}/readings```
   ```{"timestamp":"2026-02-01T03:45:00+00:00","kwh_total":12345}```
2. Calculate the daily costs 
   `php artisan app:calculate-daily-costs`
3. Monitor differences in week consumptions and notify
   `app:monitor-weekly-consumption`
