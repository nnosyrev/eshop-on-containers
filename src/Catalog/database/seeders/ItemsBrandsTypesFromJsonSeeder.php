<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsBrandsTypesFromJsonSeeder extends Seeder
{
    private const DEFAULT_AVAILABLE_STOCK = 100;
    private const DEFAULT_MAX_STOCK_THRESHOLD = 200;
    private const DEFAULT_RESTOCK_THRESHOLD = 10;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rawJson = file_get_contents(config('app.catalog_seed_file'));

        $list = json_decode($rawJson);

        DB::transaction(function () use ($list) {
            $types = [];
            $brands = [];

            foreach ($list as $value) {
                $brand = $value->Brand;
                if (!isset($brands[$brand])) {
                    $brands[$brand] = DB::table('brands')->insertGetId(
                        ['name' => $brand]
                    );
                }

                $type = $value->Type;
                if (!isset($types[$type])) {
                    $types[$type] = DB::table('types')->insertGetId(
                        ['name' => $type]
                    );
                }
            }

            foreach ($list as $value) {
                DB::table('items')->insert([
                    'id' => $value->Id,
                    'brand_id' => $brands[$value->Brand],
                    'type_id' => $types[$value->Type],
                    'name' => $value->Name,
                    'description' => $value->Description,
                    'price' => $value->Price,
                    'available_stock' => self::DEFAULT_AVAILABLE_STOCK,
                    'max_stock_threshold' => self::DEFAULT_MAX_STOCK_THRESHOLD,
                    'restock_threshold' => self::DEFAULT_RESTOCK_THRESHOLD,
                    'picture_file_name' => $value->Id . '.webp',
                    'on_reorder' => false,
                ]);

                DB::select("SELECT setval('items_id_seq', :id, true)", ['id' => $value->Id]);
            }
        });
    }
}
