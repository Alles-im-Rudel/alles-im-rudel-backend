<?php

namespace Database\Seeders;

use App\Models\Level;

class LevelsSeeder extends BaseSeeder
{
    public ?string $model = Level::class;

    public function firstOrCreate(): array
    {
        return [
            [
                'id'           => Level::DEVELOPER,
                'display_name' => 'Developer'
            ],
            [
                'id'           => Level::ADMINISTRATOR,
                'display_name' => 'Administrator'
            ],
            [
                'id'           => Level::MODERATOR,
                'display_name' => 'Moderator'
            ],
            [
                'id'           => Level::MEMBER,
                'display_name' => 'Mitglied'
            ],
            [
                'id'           => Level::PROSPECT,
                'display_name' => 'Prospect'
            ],
            [
                'id'           => Level::GUEST,
                'display_name' => 'Gast'
            ]
        ];
    }
}
