<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IcecatService
{
    protected string $username;

    protected string $baseUrl = 'https://live.icecat.biz/api';

    public function __construct()
    {
        $this->username = (string) config('icecat.username');
    }

    /**
     * Look up a product by GTIN (EAN/UPC), or by ProductCode + Brand.
     *
     * Icecat requires a Brand whenever you search by ProductCode - a code is
     * only unique *within* a brand. A GTIN is unique across the whole
     * catalog, so it may be used on its own.
     */
    public function getProductByEanOrMpn(string $searchTerm, string $brand = ''): ?array
    {
        if ($this->username === '') {
            Log::warning('Icecat import: ICECAT_USERNAME is not configured.');

            return null;
        }

        $isGtin = ctype_digit($searchTerm);

        if (! $isGtin && $brand === '') {
            // Icecat can never resolve a bare product code without a brand -
            // don't waste a request finding that out.
            Log::warning("Icecat import: skipped '{$searchTerm}' - a brand is required when searching by product code.");

            return null;
        }

        $query = [
            'UserName' => $this->username,
            'Language' => 'en',
            // Leave "content" unset to receive the full datasheet - Icecat's
            // granular "content" values (essentialinfo, gallery, ...) are
            // for *partial* responses, not language selection.
        ];

        if ($isGtin) {
            $query['GTIN'] = $searchTerm;
        } else {
            $query['ProductCode'] = $searchTerm;
            $query['Brand'] = $brand;
        }

        $response = Http::timeout(20)->get($this->baseUrl, $query);

        $data = $response->json();

        if (! $response->successful() || empty($data['data'])) {
            $message = $data['message'] ?? $data['Message'] ?? $response->body();
            Log::warning("Icecat import: lookup failed for '{$searchTerm}' (brand: '{$brand}') - {$message}");

            return null;
        }

        return $data['data'];
    }
}