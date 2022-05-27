<?php

namespace App\Exports\UsersExport;

use App\Exports\UsersExport\Sheets\AirsoftSheet;
use App\Exports\UsersExport\Sheets\AllesImRudelSheet;
use App\Exports\UsersExport\Sheets\EsportsSheet;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;


class UsersExport implements WithMultipleSheets
{
	private $search;
	private $branchId;


	public function __construct(?string $search, ?int $branchId)
	{
		$this->search = $search;
		$this->branchId = $branchId;
	}

	/**
	 * @return array
	 */
	public function sheets(): array
	{
		$sheets = [];

		/** @var User $user */
		$user = Auth::user();
		$canAllesImRudel = $user->can('members.allesimrudel');
		if ($canAllesImRudel) {
			$sheets[] = new AllesImRudelSheet($this->search);
		}
		if ($canAllesImRudel || $user->can('members.airsoft')) {
			$sheets[] = new AirsoftSheet($this->search);
		}
		if ($canAllesImRudel || $user->can('members.e_sports')) {
			$sheets[] = new EsportsSheet($this->search);
		}

		return $sheets;
	}

}
