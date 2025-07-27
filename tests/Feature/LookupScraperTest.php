<?php

namespace Tests\Feature;

use App\Scraper\LookupScraper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LookupScraperTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $scraper = new LookupScraper();

        $data = $scraper->scrapeProduct('8697817871019');

        dd($data);

        $this->assertIsArray($data);
    }
}
