<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\User;
use App\Models\UserGroup;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends BaseSeeder
{
    public ?string $model = User::class;
    public string $updateOrCreateKey = 'id';

    public function afterwards(): void
    {
        User::find(User::DEVELOPER_ID)->userGroups()->sync(UserGroup::DEVELOPER_ID);
    }

    public function updateOrCreate(): array
    {
        $now = Carbon::now();
        $changeMe = Hash::make('changeMe');

        return [
            [
                'id'                => User::DEVELOPER_ID,
                'level_id'          => Level::DEVELOPER,
                'first_name'        => 'Alles',
                'last_name'         => 'im Rudel',
                'password'          => $changeMe,
                'email'             => 'allesimrudel@gmail.com',
                'salutation'        => 'Boss',
                'phone'             => '+49 176 55234699',
                'street'            => 'Norderstraße 23',
                'postcode'          => '25335',
                'city'              => 'Elmshorn',
                'country_id'        => 1,
                'birthday'          => '1990-01-01',
                'email_verified_at' => $now,
                'activated_at'      => $now,
            ]
        ];
    }
}
