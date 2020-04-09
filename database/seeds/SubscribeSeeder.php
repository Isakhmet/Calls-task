<?php

use Illuminate\Database\Seeder;

class SubscribeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('webhooks')
          ->insert(
              [
                  'service_names' => 'subscribe',
                  'done'          => false,
              ]
          )
        ;
    }
}
