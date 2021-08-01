<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

abstract class BaseSeeder extends Seeder
{
    public ?string $model = null;
    public string $firstOrCreateKey = 'id';
    public string $updateOrCreateKey = 'id';

    /**
     * @return array
     */
    public function create(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function firstOrCreate(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function updateOrCreate(): array
    {
        return [];
    }

    public function before(): void
    {}

    public function afterwards(): void
    {}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->before();

        foreach ($this->create() as $data) {
            $this->model::create($data);
        }

        foreach ($this->firstOrCreate() as $data) {
            $this->model::firstOrCreate([
                $this->firstOrCreateKey => $data[$this->firstOrCreateKey]
            ], $data);
        }

        foreach ($this->updateOrCreate() as $data) {
            $this->model::updateOrCreate([
                $this->updateOrCreateKey => $data[$this->updateOrCreateKey]
            ], $data);
        }

        $this->afterwards();
    }
}
