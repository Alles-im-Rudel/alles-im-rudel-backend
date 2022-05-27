<?php

namespace App\Exports\UsersExport\Sheets;

use App\Models\Branch;

class AirsoftSheet extends BaseBranchSheet
{
	/**
	 * @return int
	 */
	protected function getBranchId(): int
	{
		return Branch::AIRSOFT;
	}

	/**
	 * @return string
	 */
	protected function getSheetName(): string
	{
		return "Airsoft";
	}
}