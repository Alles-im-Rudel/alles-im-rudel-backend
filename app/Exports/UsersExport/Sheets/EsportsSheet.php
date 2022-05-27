<?php

namespace App\Exports\UsersExport\Sheets;

use App\Models\Branch;

class EsportsSheet extends BaseBranchSheet
{
	/**
	 * @return int
	 */
	protected function getBranchId(): int
	{
		return Branch::ESPORTS;
	}

	/**
	 * @return string
	 */
	protected function getSheetName(): string
	{
		return "E-Sports";
	}
}