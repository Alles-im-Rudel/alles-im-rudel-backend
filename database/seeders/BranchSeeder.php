<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;

class BranchSeeder extends BaseSeeder
{
    public ?string $model = Branch::class;
    public string $firstOrCreateKey = 'name';

    public function firstOrCreate(): array
    {
        return [
            [
                'name'          => 'Alles im Rudel',
                'user_id'       => User::DEVELOPER_ID,
                'description'   => 'Verein uns so',
                'price'         => '1.00',
                'is_selectable' => false,
                'activated_at'  => now(),
            ],
            [
                'name'          => 'Airsoft',
                'user_id'       => User::DEVELOPER_ID,
                'description'   => 'Airsoft ist ein Geländespiel, in dem mit Softairwaffen ausgerüstete Teams gegeneinander antreten',
                'price'         => '3.00',
                'is_selectable' => true,
                'activated_at'  => now(),
            ],
            [
                'name'          => 'E-Sports',
                'user_id'       => User::DEVELOPER_ID,
                'description'   => 'Ganz viel E-Sports und Gaming',
                'price'         => '0',
                'is_selectable' => true,
                'activated_at'  => now(),
            ]
        ];
    }
}
