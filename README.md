# Laravel Barcode Scraper — Documentation

## Project Overview

This Laravel project allows users to input a barcode and automatically scrape product details from external sources:

-   Primary source: [OpenFoodFacts] (https://world.openfoodfacts.org/)
-   Secondary (experimental): Gemini AI-powered web search — used as a fallback but less accurate.

## Setup Instructions

1. Clone the repository:

```bash
git clone https://github.com/your-username/barcode-scraper.git
cd barcode-scraper
```

2. Install dependencies:

```bash
composer install

```

3. Configure .env:

-   Set DB connection
-   (Optional) Add Gemini API Keys if used

5. Run migrations:

```bash
php artisan migrate
```

6. Run the scraper via command:

```bash
php artisan scrape:product {barcode}
```

```bash
GET /products/scrape/{barcode}
```

Description: Scrapes product data for the given barcode from OpenFoodFacts and saves it.

Params:

barcode (string) – The barcode to search

Response:

200 OK: Product details saved and returned.

404 Not Found: Product not found on OpenFoodFacts.
