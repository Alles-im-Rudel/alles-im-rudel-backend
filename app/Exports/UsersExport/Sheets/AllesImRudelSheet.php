<?php

namespace App\Exports\UsersExport\Sheets;

use App\Models\Branch;

class AllesImRudelSheet extends BaseBranchSheet
{
	/**
	 * @return int
	 */
	protected function getBranchId(): int
	{
		return Branch::AIR;
	}

	/**
	 * @return string
	 */
	protected function getSheetName(): string
	{
		return "Alles im Rudel";
	}
}