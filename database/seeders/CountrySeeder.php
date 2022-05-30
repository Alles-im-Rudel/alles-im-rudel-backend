<?php

namespace Database\Seeders;

use App\Models\Country;

class CountrySeeder extends BaseSeeder
{
	public ?string $model = Country::class;
	public string $firstOrCreateKey = 'name';

	public function firstOrCreate(): array
	{
		return [
			['name' => 'Deutschland', 'iso_code' => 'DE'],
			['name' => 'Belgien', 'iso_code' => 'BE'],
			['name' => 'Albanien', 'iso_code' => 'AL'],
			['name' => 'Andorra', 'iso_code' => 'AD'],
			['name' => 'Belarus', 'iso_code' => 'BY'],
			['name' => 'Bosnien und Herzegowina', 'iso_code' => 'BA'],
			['name' => 'Bulgarien', 'iso_code' => 'BG'],
			['name' => 'Dänemark', 'iso_code' => 'DK'],
			['name' => 'Estland', 'iso_code' => 'EE'],
			['name' => 'Finnland', 'iso_code' => 'FI'],
			['name' => 'Frankreich', 'iso_code' => 'FR'],
			['name' => 'Griechenland', 'iso_code' => 'GR'],
			['name' => 'Irland', 'iso_code' => 'IE'],
			['name' => 'Island', 'iso_code' => 'IS'],
			['name' => 'Italien', 'iso_code' => 'IT'],
			['name' => 'Kroatien', 'iso_code' => 'HR'],
			['name' => 'Lettland', 'iso_code' => 'LV'],
			['name' => 'Liechtenstein', 'iso_code' => 'LI'],
			['name' => 'Litauen', 'iso_code' => 'LT'],
			['name' => 'Luxemburg', 'iso_code' => 'LU'],
			['name' => 'Malta', 'iso_code' => 'MT'],
			['name' => 'Moldau', 'iso_code' => 'MD'],
			['name' => 'Monaco', 'iso_code' => 'MC'],
			['name' => 'Montenegro', 'iso_code' => 'ME'],
			['name' => 'Niederlande', 'iso_code' => 'NL'],
			['name' => 'Nordmazedonien', 'iso_code' => 'MK'],
			['name' => 'Norwegen', 'iso_code' => 'NO'],
			['name' => 'Österreich', 'iso_code' => 'AT'],
			['name' => 'Polen', 'iso_code' => 'PL'],
			['name' => 'Portugal', 'iso_code' => 'PT'],
			['name' => 'Rumänien', 'iso_code' => 'RO'],
			['name' => 'Russische Föderation', 'iso_code' => 'RU'],
			['name' => 'San Marino', 'iso_code' => 'SM'],
			['name' => 'Schweden', 'iso_code' => 'SE'],
			['name' => 'Schweiz', 'iso_code' => 'CH'],
			['name' => 'Serbien', 'iso_code' => 'RS'],
			['name' => 'Slowakei', 'iso_code' => 'SK'],
			['name' => 'Slowenien', 'iso_code' => 'SI'],
			['name' => 'Spanien', 'iso_code' => 'ES'],
			['name' => 'Tschechien', 'iso_code' => 'CZ'],
			['name' => 'Türkei', 'iso_code' => 'TR'],
			['name' => 'Ukraine', 'iso_code' => 'UA'],
			['name' => 'Ungarn', 'iso_code' => 'HU'],
			['name' => 'Vatikanstadt', 'iso_code' => 'VA'],
			['name' => 'Vereinigtes Königreich', 'iso_code' => 'UK'],
			['name' => 'Zypern', 'iso_code' => 'CY'],
		];
	}
}
