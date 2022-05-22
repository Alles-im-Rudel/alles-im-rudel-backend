<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\Permission;
use App\Models\UserGroup;

class UserGroupsSeeder extends BaseSeeder
{
    public ?string $model = UserGroup::class;

    public function afterwards(): void
    {
        UserGroup::find(UserGroup::DEVELOPER_ID)->syncPermissions(Permission::all());
    }

    public function updateOrCreate(): array
    {
        return [
            [
                'id'           => UserGroup::DEVELOPER_ID,
                'level_id'     => Level::DEVELOPER,
                'display_name' => 'Developer',
                'color'        => 'dev',
                'description'  => 'Developer von Alles im Rudel e.V.',
            ],
            [
                'id'           => UserGroup::ADMIN_ID,
                'level_id'     => Level::ADMINISTRATOR,
                'display_name' => 'Vorstand',
                'color'        => 'admin',
                'description'  => 'Vorstand von Alles im Rudel e.V.',
            ],
            [
                'id'           => UserGroup::MODERATOR_ID,
                'level_id'     => Level::MODERATOR,
                'display_name' => 'Moderator',
                'color'        => 'moderator',
                'description'  => 'Moderator von Alles im Rudel e.V.',
            ],
            [
                'id'           => UserGroup::AIRSOFT_LEADER_ID,
                'level_id'     => Level::MODERATOR,
                'display_name' => 'Airsoft Spartenleiter',
                'color'        => 'moderator',
                'description'  => 'Leiter der Sparte Airsoft von Alles im Rudel e.V.',
            ],
            [
                'id'           => UserGroup::E_SPORTS_LEADER_ID,
                'level_id'     => Level::MODERATOR,
                'display_name' => 'E-Sports Spartenleiter',
                'color'        => 'moderator',
                'description'  => 'Leiter der Sparte E-Sports von Alles im Rudel e.V.',
            ],
            [
                'id'           => UserGroup::MEMBER_ID,
                'level_id'     => Level::MEMBER,
                'display_name' => 'Vereinsmitglied',
                'color'        => 'member',
                'description'  => 'Mitglied von Alles im Rudel e.V.',
            ],
            [
                'id'           => UserGroup::E_SPORTS_MEMBER_ID,
                'level_id'     => Level::MEMBER,
                'display_name' => 'E-Sports Mitglied',
                'color'        => 'eSports',
                'description'  => 'Mitglied der Sparte E-Sports von Alles im Rudel e.V.',
            ],
            [
                'id'           => UserGroup::AIRSOFT_MEMBER_ID,
                'level_id'     => Level::MEMBER,
                'display_name' => 'Airsoft Mitglied',
                'color'        => 'airsoft',
                'description'  => 'Mitglied der Sparte Airsoft von Alles im Rudel e.V.',
            ],
        ];
    }
}
