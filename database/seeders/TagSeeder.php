<?php

namespace Database\Seeders;

use App\Models\Tag;

class TagSeeder extends BaseSeeder
{
    public ?string $model = Tag::class;
    public string $firstOrCreateKey = 'name';

    public function firstOrCreate(): array
    {
        return [
            [
                'name'  => 'Airsoft',
                'color' => '#4D4832'
            ],
            [
                'name'  => 'Allgemein',
                'color' => '#B8B8B5'
            ],
            [
                'name'  => 'E-Sports',
                'color' => '#0362fc'
            ]
        ];
    }
}
