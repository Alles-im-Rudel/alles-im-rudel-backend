<?php

namespace Database\Seeders;

use App\Models\InstagramProfile;
use Illuminate\Database\Seeder;

class InstagramProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        foreach ($this->items() as $item) {
            InstagramProfile::firstOrCreate([
                'instagram_id' => $item['instagram_id']
            ], [
                'name'         => $item['name'],
                'display_name' => $item['display_name']
            ]);
        }
    }

    public function items(): array
    {
        return [
            [
                'instagram_id' => env('INSTAGRAM_ID'),
                'name'         => 'allesimrudel',
                'display_name' => 'Alles im Rudel'
            ]
        ];
    }
}
