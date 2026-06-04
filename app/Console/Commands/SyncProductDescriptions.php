<?php

namespace App\Console\Commands;

use App\Models\Product;
use Google\Client;
use Google\Service\Sheets;
use Illuminate\Console\Command;

class SyncProductDescriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:sync-descriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync product descriptions from Google Sheets';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $credentialsPath = storage_path('app/google-creds.json');
        $spreadsheetId = '14Hw1XqSqbE3PgznmqwlmCOc2WOGUyQVHFTRuDjfMWT0';
        $range = 'Sheet1!A2:B';

        if (! file_exists($credentialsPath)) {
            $this->error("Google credentials file not found: {$credentialsPath}");

            return Command::FAILURE;
        }

        try {
            $client = new Client();
            $client->setApplicationName('Product Description Sync');
            $client->setAuthConfig($credentialsPath);
            $client->setScopes([Sheets::SPREADSHEETS_READONLY]);

            $service = new Sheets($client);
            $response = $service->spreadsheets_values->get($spreadsheetId, $range);
            $rows = $response->getValues() ?: [];
        } catch (\Throwable $exception) {
            $this->error('Failed to read Google Sheet: ' . $exception->getMessage());

            return Command::FAILURE;
        }

        if (empty($rows)) {
            $this->info('No rows found in the Google Sheet.');

            return Command::SUCCESS;
        }

        $updated = 0;
        $skipped = 0;

        foreach ($rows as $index => $row) {
            $sheetRowNumber = $index + 2;
            $productId = $row[0] ?? null;
            $jsonPayload = $row[1] ?? null;

            if (! $productId || ! is_numeric($productId)) {
                $this->error("Row {$sheetRowNumber}: invalid product_id.");
                $skipped++;
                continue;
            }

            if (! $jsonPayload) {
                $this->error("Row {$sheetRowNumber}: missing Gemini JSON payload.");
                $skipped++;
                continue;
            }

            $decoded = json_decode($jsonPayload, true);

            if (json_last_error() !== JSON_ERROR_NONE || ! is_array($decoded)) {
                $this->error("Row {$sheetRowNumber}: invalid JSON. " . json_last_error_msg());
                $skipped++;
                continue;
            }

            $description = $decoded['ai_description'] ?? null;

            if (! is_string($description) || trim($description) === '') {
                $this->error("Row {$sheetRowNumber}: ai_description is missing or empty.");
                $skipped++;
                continue;
            }

            $product = Product::find((int) $productId);

            if (! $product) {
                $this->error("Row {$sheetRowNumber}: product ID {$productId} was not found.");
                $skipped++;
                continue;
            }

            $product->update([
                'long_description' => $description,
            ]);

            $this->info("Row {$sheetRowNumber}: updated product #{$product->id} ({$product->name}).");
            $updated++;
        }

        $this->info("Sync complete. Updated: {$updated}. Skipped: {$skipped}.");

        return Command::SUCCESS;
    }
}
