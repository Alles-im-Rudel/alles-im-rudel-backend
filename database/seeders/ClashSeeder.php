<?php

namespace Database\Seeders;

use App\Models\ClashTeam;
use App\Models\ClashTeamRole;
use Illuminate\Database\Seeder;

class ClashSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(): void
	{
		$clashTeam = ClashTeam::create([
			'name'      => 'Alles Im Rudel',
			'leader_id' => 3
		]);

		$clashTeamRoles = $this->clashTeamRoles();

		foreach ($clashTeamRoles as $clashTeamRole) {
			ClashTeamRole::firstOrCreate([
				'name' => $clashTeamRole['name'],
			], [
				'image' => $clashTeamRole['image']
			]);
		}

		$clashMembers = $this->clashMembers();

		foreach ($clashMembers as $clashMember) {
			$clashTeam->clashMembers()->create([
				'user_id'            => $clashMember['user_id'],
				'summoner_id'        => $clashMember['summoner_id'],
				'clash_team_role_id' => $clashMember['clash_team_role_id'],
				'is_active'          => $clashMember['is_active'],
			]);
		}
	}

	public function clashTeamRoles(): array
	{
		return [
			[
				'name'  => 'Toplane',
				'image' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIgAAACICAYAAAA8uqNSAAACDUlEQVR4Ae3SxZVWQRhF0WrLhXxwDYExQ9zd3aX/7hx4U7KCb4a9ixZW7LPWnZfspg+TJEmSJEmSJEmSJEnSm42Db//YbLO2BsjcbKO22mYCxNZrK20mQOxlwgGIPU84ALGnCQcg9jDhAMQe1JbbTIDYvdpSmwkQu5NwAGK3Eg5A7EbCAYhd6Ynj0J4t2wAZZ5c649hemwAZYxda1RsHIGPsXGccO2oTIGPsTGccO2sTIGPsVGccu2oTIGPs+O/AAci/uSOdcexOOIYE0vQ9OPZkHICE4AAEjr0ZBSAhOACBY3/GAEgIDkDgOPD9OACBAxB9PwpAAAFEgAACCCCAAAIIIIAAAggggAACCCCAACJA8n7/A/cdIIAAAggggAACCCCAAAIIIIAAAggggAACCCCAAAIIIID8tgABBBBAAAEEEEAAAQQQQAABBBBAAAEEEEAAAQQQQAABBBBAAAEEEED+jwABBBBAAAEEEEAAAQQQQAABBBBAjiIBSNpxHABJO4kCIGlnMEgBch6BFCAXm0KAXGoKAXKlttRmEiDX4UgBchOOFCC34UgBch+OFCAPeuKoC2+rva5N9u8DeVhbhmNugDyqrXTGMdkYQJ7BkQbICzjSAHkFRxogG3CkAbJZW4UjDZA1OADJwQEIHID8SRzbfSQgcAACByBwAAIHIOMmSZIkSZIkSZIkSZKkd8dKK/Dyg9OUAAAAAElFTkSuQmCC',
			],
			[
				'name'  => 'Jungle',
				'image' => 'data:image/png;base64,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'
			],
			[
				'name'  => 'Midlane',
				'image' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIgAAACICAYAAAA8uqNSAAACBUlEQVR4Ae3dwTFtQRSF4fOUmUREAwCACEwBiEcQTEUFE3jYDXTdVbe+v2olsM83Pj28TpIkSZIkSZIkSZIkSTrcmL55nu0Nv+j2av/icfd/uRwgthOAIxSIbQfgACR0WwE4AAndZgAOQEK3EYADkNCt5eMABA5A4rYaigOQgC3n4wAEDkCidv24pVAcgATgWMjHAQgcgMThmA/FAUgAjtkgHIAMyQXgAKRbcAACByBwAAJHv10CMpLgAAQOQOAABI7+AQIHIHAAAgcgcPQPEDgAgQMQOACBo3+AwAEIHIDAUXybCUDK4HjcISBVcBw17wsIHIDUwQHIB8FxXN0XEDhO2vcFBA5APgiO0x/cFxA4ACmCAxA4zqp7AgLH+Q/uCwgcgBTBAQgcFx/dDhA4/rVxjD+QPTiat52qbzf+QHbgqAJkG44qQLbgqAJkE44qQDbgqAJkDQ5A4Ph2gKzCUQXIMhyADHC0A+T3b7zBUd92chyA1M94weHvCXA0A+SzN97gAGQWDkC6BAcgcAACByBwANI/OACBAxA4AIEDkP7BAQgcgMABCByA9AkOQOAABA5A4BhpgMDRI0DgAAQOQODoECCx/wTbLQ4dMEDgAAQOQOAIGCBwAAIHIHAAAgcgcAQMEDgAgQMQOACBo8MAgQMQOABJxgGIJEmSJEmSJEmSJEmS9ABV/259BSpn/AAAAABJRU5ErkJggg=='
			],
			[
				'name'  => 'Botlane',
				'image' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIgAAACICAYAAAA8uqNSAAACYElEQVR4Ae3aRZYUMBSG0XR1117YAu7uLotgzAx3d3eXIROkYcquigSXPLS87nfOv4G8O0z6NkmSJEmSJEmSJEmSJGnHtilvuzbbCEg02zAAHACBAxA4/iNA4AAEDkDgAASOd0+2NwGJBsdE3lNAaoNjPO9JXguQ2uB4VHAAUhsc9wsMQGqD425BAUhtcNz8EQcgZXA08m7UcAACx1je9QJhaIEk/Q+OqxEMQOC4FKEABI4LEQhA4DgbYQAEjlMRBEAAORkhAASO4xEAQOA4Eh0fEDgORYcHBI795cCjDuRlUg3Hno8HBuR50o84doUHB0T1QwMiQAABBBBAAAEEEEAAAQQQQADp/SdqQAABBBBAAAGk4wECCCCAAAIIIIAAAggggADyDBBAfrXHgAACCCCAAAIIIIAAAggggAACCCCAAAIIIIAAAggggAACCCCAAAIIIIAAAgggIxMggADyH7uRBMgvdj1pxIEAAggggAACCCCAAAIIIIAAAggggADyKm9aEiABjhlJgAQ4ZiZVAuR13uykSoBM5s1JAiTAMTcJkADH/CRAAhwLkwAJcCxp8+PtzmvZ4AN5k7e0zTj2DuKBAKnjWNZmHPsH9ECAVHCsaDOOg8NxLEAKjlVtxnF4OA4FSMGxps04jg3HkQApONa1EcZY3snhORIgG9qM4/QQHQiQNuM466iARDjOOyggEY6LjglIhOOqQwJSw9HIu+6IgNRwjOfdcEBAIhy3HA+QCMcdhwMkwnHf0QCJcDxyMEBqOCbynjgWIDUczbynDgWIJEmSJEmSJEmSJEmS+qP3NNsmpc39RZcAAAAASUVORK5CYII='
			],
			[
				'name'  => 'Support',
				'image' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIgAAACICAYAAAA8uqNSAAAE/klEQVR4AezBgQAAAACAoP2pF6kCAAAAAACA2Tlr6KiCMArjce2wJi4d3sZ1+4PT4LDpsZcKd3eokwr3CnfXTRfpcNflFhNc3s3Ovpnd979zPiQhw87MPbP3W3NzXWgJZoNnIGyIHbILdgdkJQgb5DMYITthZziGgrcgbJgzoLfsiH0B2QnCljBOdsSucBSBjxYFpAOkys7YE5BWELaMJbIzdoRjBPhiYUDegXzZIfMBOQ7ClnJQdsh8QDZbHJDNskPmA9IPnLIwHKdAP9khO0KSAUIWhSMEMmVn7ApJHnhmQTieSTm1NyTl4JPBcHwCFbITdodkpsGAzJIdELMRY4mDgPT1yGzEWMRsxFjEbMRYxGzEWDy+xGzEWMRsxFjEbHjEWMRsxFjEbMRY3C9YE9XyzeMYMBstxoIxnBha50+gqfuG14AnvgkJbzazfBaOp6Dm1wkUgNs+C8lm1lh8EI7boOBvE0kGe+I9JITZKGPxTTj2gGQ3k5oCXvskJMpseGOJo3C8BlPYyZWCGz4JiTIb3ljiIBw3QWlPJ5kAlseI5Tg6zEZR4YNwfFJ7m6BDhceAez4IyUwwywfhuAfG6H68JEFN/m28hsQHtvIWOCAhmouQD45ISGIuHEdAvpcLUgvuWh8SCcddUGvyGdKJIGR/SHwXjhCYBPra8vbHyRZqcZsHc2+zbM431F70s/XJv0rQAt5ZsFjrPJjvOks+aqIFVMbaJw7OAscMmk+DB/OsN2gkx9QaZ8f6SwqSQR2YD1pVceqKcnDegCQP5pYE3kQ5CF1qzVrBArWWyfLiHpSsSLTOxfgDgfOfAjrQxTiHI7idk3q2OhKO4gifMAy6+D8au23nH3YScDFOMMInykpkx8m7JA2PseSRiur87esuxsnV8FgFcZchAdnjhd7i3+3/9XETRfgH9nuku3tl55ne4YHe4t91uhiry0PdnSwJ0N87eL1VBZUYbyCjuwb6iPQOVm+JguqGAKO7BvqI9A6Cw1F4DsVxOeYhYkwtfUR6B0+QK6guUEWV1l3pIx70Dp48tqASRZXWXfN9RHoHr7eqoJIM5HVX+ogNvYPXW1VQSQKGnt3dK71DH/X6CypdVOuiMK/J0jv06y1fUPmiyuuu/j4ivYPXW76gMkWV1139fUR6B0+QLqg8A1ndlT7CB2QE2A5eal68PLqg8gS81l3FS7VmI/zUQ9LANHBFr94SBZWn2eMXM18F00Ga3x8PGQ62gZca9FZnQSWKKq+7clrwQUnt4alSr6Gg6i6qdXJaGDhVCL0lCirFQF535bQwc6rwetuoISABXnfltDB5qgS1FVT9RTUop4X5UyVXW0HVX1RzY+u0kGB1aQhIVxwujVx8QeWLqv2XXPoLKl9U7b/kIgsqT/PX9u7YioAoiqLoK0hR09kUoRRoB6KfAFwP7LPWpMBwd/ZL31cDUAdUBaigCqhxqApQQRVQQRVQQVV5oIIqoIIqoIKqwkAFVUAFVUDthKoAFVQBFVQBFVQB9cXXsgSooAqooAqooKpWoIIqoIIqoIIqoIKqRkc0pm8AUAXUKFQFqKAKqKAKqKAKqKCqBqCCKqCCKqCCqhtkyh81cvORHVN9RW6SxThypOXaHp+zvi7n4c0NN8d8fK6SyTEpJsekmByTEsjkmBSTszYpgUyOSTlmckzKHZmcjUk5l8kxKZIkSZIkSZIkSWppD3zS30GHlwYQAAAAAElFTkSuQmCC'
			],
		];
	}

	public function clashMembers(): array
	{
		return [
			[
				'user_id'            => 2,
				'summoner_id'        => null,
				'clash_team_role_id' => 2,
				'is_active'          => true
			],
			[
				'user_id'            => 3,
				'summoner_id'        => null,
				'clash_team_role_id' => 3,
				'is_active'          => true
			],
			[
				'user_id'            => 4,
				'summoner_id'        => null,
				'clash_team_role_id' => 5,
				'is_active'          => true
			],
			[
				'user_id'            => 5,
				'summoner_id'        => null,
				'clash_team_role_id' => 4,
				'is_active'          => true
			],
			[
				'user_id'            => 6,
				'summoner_id'        => null,
				'clash_team_role_id' => 1,
				'is_active'          => true
			],
		];
	}

}
