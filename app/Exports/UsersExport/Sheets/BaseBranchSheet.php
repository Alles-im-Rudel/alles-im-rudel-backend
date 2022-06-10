<?php

namespace App\Exports\UsersExport\Sheets;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

abstract class BaseBranchSheet implements FromQuery, WithTitle, WithHeadings, WithColumnFormatting, WithMapping, ShouldAutoSize, WithStyles
{
	private $search;
	private $branchId;
	private $sheetName;
	private $user;

	public function __construct(?string $search)
	{
		$this->search = $search;
		$this->branchId = $this->getBranchId();
		$this->sheetName = $this->getSheetName();
		$this->user = Auth::user();
	}

	/**
	 * @return int
	 */
	abstract protected function getBranchId(): int;

	abstract protected function getSheetName(): string;

	/**
	 * @var array
	 */
	protected array $searchFields = [
		'email',
		'first_name',
		'last_name',
	];

	public function query()
	{
		$userQuery = User::query()->whereHas('branchUserMemberShips', function ($query) {
			$query->where('branch_id', $this->branchId);
		});
		if ($this->search) {
			$searchFields = $this->searchFields;
			$userQuery->where(function (Builder $builder) use ($searchFields): Builder {
				foreach ($searchFields as $field) {
					$builder->orWhere($field, 'LIKE', $this->search);
				}
				return $builder;
			});
		}

		return $userQuery;
	}

	/**
	 * @return string
	 */
	public function title(): string
	{
		return $this->sheetName;
	}

	public function headings(): array
	{
		$headings = [
			'Vorname',
			'Nachname',
			'Geburstag',
			'E-Mail',
			'Telefon',
			'Straße & Hausnummer',
			'Postleitzahl',
			'Stadt',
			'Land',
		];

		if ($this->user->can('members.allesimrudel')) {
			array_push($headings, 'Kontoinhaber', 'IBAN', 'BIC');
		}

		return $headings;
	}

	/**
	 * @return array
	 * @var User $user
	 */
	public function map($row): array
	{
		$map = [
			$row->first_name,
			$row->last_name,
			Carbon::parse($row->birthday)->format('d.m.Y'),
			$row->email,
			$row->phone,
			$row->street,
			$row->postcode,
			$row->city,
			$row->country->name,
		];

		if ($this->user->can('members.allesimrudel')) {
			array_push($map,
				$row->bankAccount->first_name.' '.$row->bankAccount->last_name,
				$row->bankAccount->iban,
				$row->bankAccount->bic,);
		}

		return $map;
	}

	public function columnFormats(): array
	{
		return [
			'E' => '+#',
		];
	}

	public function styles(Worksheet $sheet): array
	{
		return [
			1 => ['font' => ['bold' => true]],
		];
	}
}