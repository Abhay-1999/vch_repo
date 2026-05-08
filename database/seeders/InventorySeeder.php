<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use DB;

class InventorySeeder extends Seeder
{

    public function run()
    {

        /*
        |--------------------------------------------------------------------------
        | UNITS
        |--------------------------------------------------------------------------
        */

        DB::table('units')->insert([

            [
                'unit_name'=>'Kilogram',
                'short_name'=>'KG',
            ],

            [
                'unit_name'=>'Gram',
                'short_name'=>'GM',
            ],

            [
                'unit_name'=>'Liter',
                'short_name'=>'LTR',
            ],

            [
                'unit_name'=>'Piece',
                'short_name'=>'PCS',
            ]

        ]);

        /*
        |--------------------------------------------------------------------------
        | SUPPLIERS
        |--------------------------------------------------------------------------
        */

        DB::table('suppliers')->insert([

            [
                'supplier_name'=>'Sharma Traders',
                'mobile'=>'9999999999',
                'gst_no'=>'23ABCDE1234F1Z5',
                'address'=>'Indore'
            ],

            [
                'supplier_name'=>'Food Hub Supplier',
                'mobile'=>'8888888888',
                'gst_no'=>'23PQRS1234T1Z5',
                'address'=>'Bhopal'
            ]

        ]);

        /*
        |--------------------------------------------------------------------------
        | RAW MATERIALS
        |--------------------------------------------------------------------------
        */

        DB::table('raw_materials')->insert([

            [
                'material_name'=>'Paneer',
                'material_code'=>'RM001',
                'unit_id'=>1,
                'opening_stock'=>50,
                'current_stock'=>50,
                'purchase_rate'=>300,
                'min_stock_alert'=>5,
            ],

            [
                'material_name'=>'Cheese',
                'material_code'=>'RM002',
                'unit_id'=>2,
                'opening_stock'=>1000,
                'current_stock'=>1000,
                'purchase_rate'=>5,
                'min_stock_alert'=>100,
            ],

            [
                'material_name'=>'Tomato Sauce',
                'material_code'=>'RM003',
                'unit_id'=>2,
                'opening_stock'=>5000,
                'current_stock'=>5000,
                'purchase_rate'=>0.50,
                'min_stock_alert'=>500,
            ],

            [
                'material_name'=>'Burger Bun',
                'material_code'=>'RM004',
                'unit_id'=>4,
                'opening_stock'=>200,
                'current_stock'=>200,
                'purchase_rate'=>8,
                'min_stock_alert'=>20,
            ],

            [
                'material_name'=>'Oil',
                'material_code'=>'RM005',
                'unit_id'=>3,
                'opening_stock'=>20,
                'current_stock'=>20,
                'purchase_rate'=>120,
                'min_stock_alert'=>2,
            ]

        ]);

        /*
        |--------------------------------------------------------------------------
        | PURCHASE HEAD
        |--------------------------------------------------------------------------
        */

        DB::table('purchase_heads')->insert([

            [
                'purchase_date'=>date('Y-m-d'),
                'supplier_id'=>1,
                'grand_total'=>5000,
            ]

        ]);

        /*
        |--------------------------------------------------------------------------
        | PURCHASE DETAILS
        |--------------------------------------------------------------------------
        */

        DB::table('purchase_details')->insert([

            [
                'purchase_id'=>1,
                'material_id'=>1,
                'qty'=>10,
                'rate'=>300,
                'amount'=>3000,
            ],

            [
                'purchase_id'=>1,
                'material_id'=>4,
                'qty'=>50,
                'rate'=>10,
                'amount'=>500,
            ],

            [
                'purchase_id'=>1,
                'material_id'=>5,
                'qty'=>10,
                'rate'=>150,
                'amount'=>1500,
            ]

        ]);

        /*
        |--------------------------------------------------------------------------
        | RECIPES
        |--------------------------------------------------------------------------
        */

        DB::table('recipes')->insert([

            [
                'item_id'=>1,
            ],

            [
                'item_id'=>2,
            ]

        ]);

        /*
        |--------------------------------------------------------------------------
        | RECIPE ITEMS
        |--------------------------------------------------------------------------
        */

        DB::table('recipe_items')->insert([

            [
                'recipe_id'=>1,
                'material_id'=>1,
                'qty'=>0.20,
            ],

            [
                'recipe_id'=>1,
                'material_id'=>4,
                'qty'=>1,
            ],

            [
                'recipe_id'=>1,
                'material_id'=>3,
                'qty'=>20,
            ],

            [
                'recipe_id'=>2,
                'material_id'=>1,
                'qty'=>0.10,
            ],

            [
                'recipe_id'=>2,
                'material_id'=>2,
                'qty'=>1,
            ]

        ]);

        /*
        |--------------------------------------------------------------------------
        | STOCK LEDGER
        |--------------------------------------------------------------------------
        */

        DB::table('stock_ledgers')->insert([

            [
                'material_id'=>1,
                'type'=>'PURCHASE',
                'qty'=>10,
                'stock_before'=>40,
                'stock_after'=>50,
                'reference_no'=>'PUR-1',
                'remarks'=>'Purchase Entry'
            ],

            [
                'material_id'=>4,
                'type'=>'SALE',
                'qty'=>2,
                'stock_before'=>200,
                'stock_after'=>198,
                'reference_no'=>'ORD-1',
                'remarks'=>'Burger Sale'
            ]

        ]);

        /*
        |--------------------------------------------------------------------------
        | WASTE ENTRY
        |--------------------------------------------------------------------------
        */

        DB::table('waste_entries')->insert([

            [
                'material_id'=>1,
                'qty'=>1,
                'remarks'=>'Damaged Paneer'
            ],

            [
                'material_id'=>5,
                'qty'=>2,
                'remarks'=>'Burnt Oil'
            ]

        ]);
    }
}