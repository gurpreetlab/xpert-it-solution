<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

#[Signature('cart:sync-redis')]
#[Description('Sync all Redis active carts to Database in single bulk queries')]
class SyncRedisCartsToDb extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Scan for all active user cart keys in Redis
        $cartKeys = Redis::keys('cart_user_*');

        if (empty($cartKeys)) {
            return;
        }

        $bulkRows = [];
        $userIds = [];
        $now = now();

        foreach ($cartKeys as $key) {
            // Extract user_id from key "cart_user_{id}"
            $userId = explode('_', $key)[2] ?? null;
            if (!$userId) continue;

            $userIds[] = $userId;
            $items = Redis::hgetall($key);

            foreach ($items as $itemJson) {
                $item = json_decode($itemJson, true);
                $bulkRows[] = [
                    'user_id'    => $userId,
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        if (empty($bulkRows)) {
            return;
        }

        // Execute persistence in 2 optimal queries regardless of item count
        DB::transaction(function () use ($userIds, $bulkRows) {
            // Query 1: Bulk delete old DB entries for synced users
            DB::table('cart_items')->whereIn('user_id', $userIds)->delete();

            // Query 2: Single bulk insert for thousands of items
            foreach (array_chunk($bulkRows, 1000) as $chunk) {
                DB::table('cart_items')->insert($chunk);
            }
        });

        $this->info('Successfully synchronized Redis carts to MySQL.');
    }
}
