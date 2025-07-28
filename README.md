# Laravel Barcode Scraper — Documentation

## Project Overview

This Laravel project allows users to input a barcode and automatically scrape product details from external sources:

-   [OpenFoodFacts] (https://world.openfoodfacts.org/)
-   [BarcodeLookup] (https://www.barcodelookup.com/)
-   [TarracoImportExport] (https://tienda.tarracoimportexport.com/)
-   Secondary (experimental): Gemini AI-powered web search — used as a fallback but less accurate.

## Scraping Engine

This project consists of two separate service working together to scrape product data, with laravel as the main application where all product data is stored.

1. Laravel application using Symfony components for scraping static websites like openFoodFacts.
2. Node.js + Puppeteer microservice for scraping javaScript-heavy page, that load content using javascript

### Job Queue for Parallel Scraping

-   The Laravel application is the main service, responsible for controlling the scraping process and storing results.
-   A custom Artisan command reads barcodes from a file named barcodes.txt located in the root of the project.
-   For each barcode, a ScrapeProductJob is dispatched to the Laravel queue.
-   Each job triggers scraping from all three sources:
    -   OpenFoodFacts (static, Symfony-based)
    -   TarracoExportImport (via Puppeteer API)
    -   BarcodeLookup (via Puppeteer API)

## Setup Instructions

Before setup laravel proejct make sure u have setup first pupetter project [puppeteer-project](https://github.com/almant12/Barcode-scraper-puppeteer)

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
-   Set PUPPETEER_SCRAPER_URL=http://localhost:3000 to connect Laravel with Puppeteer service.

5. Run migrations:

```bash
php artisan migrate
```

6. scraper via command:

```bash
# This will scrape product through each website
php artisan scrape:product {barcode}

# To run this command, make sure you have a .txt file in the root of your project containing a list of barcodes
php artisan scrape:products {file} #file.txt
```

7. scraper via endPoints:
```bash
GET http://localhost:8000/api/scrape/open-food/{barcode}
GET http://localhost:8000/api/scrape/tarraco/{barcode}
GET http://localhost:8000/api/scrape/lookup/{barcode}
```

## Scrape Product with GeminiAi

### Disclaimer

The Gemini AI scraper is experimental and does not guarantee 100% accuracy. The results may contain incomplete or incorrect product information due to the inherent limitations of AI-powered web scraping. Please verify the data independently before use.

```bash
Endpoint: GET /products/ai-scrape/{barcode}
```
