<?php

namespace App\Console\Commands;

use App\Models\Branch;
use App\Models\BranchUserMemberShip;
use App\Models\User;
use App\Notifications\Branches\BranchMembershipExitNotification;
use App\Notifications\Membership\MembershipExitNotification;
use Illuminate\Console\Command;

class CheckForLeavers extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'command:check-for-leavers';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Überprüft ob Mitglieder Sparten oder den Verein verlassen möchten und handled den Prozess.';

	public function handle(): void
	{
		$branchUserMemberShips = BranchUserMemberShip::with('user')
			->whereRaw('LAST_DAY(DATE_ADD(wants_to_leave_at, INTERVAL 1 MONTH)) <= ?',
				[now()->endOfMonth()->toDateString()])
			->whereNotNull('exported_at')
			->get();

		foreach ($branchUserMemberShips as $branchUserMemberShip) {
			$user = $branchUserMemberShip->user;
			if ($branchUserMemberShip->branch_id !== 1) {
				$user->notify(new BranchMembershipExitNotification());
				$branchUserMemberShip->delete();
				$this->removeUserGroup($user, $branchUserMemberShip->branch_id);
			} else {
				$user->notify(new MembershipExitNotification());
				$user->branchUserMemberShips()->delete();
				$this->removeUserGroup($user, $branchUserMemberShip->branch_id);
				$user->delete();
			}
		}
	}

	protected function removeUserGroup(User $user, $branchId): void
	{
		if ($branchId === Branch::AIRSOFT) {
			$user->userGroups()->detach(8);
		}

		if ($branchId === Branch::ESPORTS) {
			$user->userGroups()->detach(7);
		}

		if ($branchId === Branch::AIR) {
			$user->userGroups()->detach();
		}
	}
}
