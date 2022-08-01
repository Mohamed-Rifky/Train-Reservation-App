<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['key' => 'app_name', 'value' => 'Train APP'],
            ['key' => 'app_description',  'value' => 'Assessment'],
            ['key' => 'version',  'value' => '0.1'],
        ];
        foreach ($data as $value) {
            if(!Settings::where('key', $value['key'])->first()) {
                Settings::create($value);
            }
        }
    }
}
