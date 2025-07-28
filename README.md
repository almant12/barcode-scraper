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

0. Before setup laravel proejct make sure u have setup first pupetter project [puppeteer-project](https://github.com/almant12/Barcode-scraper-puppeteer)

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
Endpoint: GET /products/scrape/{barcode}
```

Description: Scrapes product data for the given barcode from OpenFoodFacts and saves it.

Params:

barcode (string) – The barcode to search

Response:

200 OK: Product details saved and returned.

404 Not Found: Product not found on OpenFoodFacts.

## Scrape Product with GeminiAi

### Disclaimer

The Gemini AI scraper is experimental and does not guarantee 100% accuracy. The results may contain incomplete or incorrect product information due to the inherent limitations of AI-powered web scraping. Please verify the data independently before use.

```bash
Endpoint: GET /products/ai-scrape/{barcode}
```
