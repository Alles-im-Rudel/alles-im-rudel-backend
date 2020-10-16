<?php

namespace App\Traits\Requests;

use Carbon\Carbon;

trait RequestHelper
{
    /**
     * @var array
     */
    protected array $validationRules;

    public function __construct()
    {
        $this->validationRules = [];
    }

    /**
     * @param string $key
     */
    protected function addKeyIfNotExist(string $key): void
    {
        if (!$this->has($key)) {
            $this->merge([$key => null]);
        }
    }

    /**
     * @param string $key
     */
    protected function convertToBoolean(string $key): void
    {
        $this->addKeyIfNotExist($key);
        if ($this->input($key) !== null) {
            $this->merge([$key => filter_var($this->input($key), FILTER_VALIDATE_BOOLEAN)]);
        }
    }

    /**
     * @param string $key
     */
    protected function convertToInteger(string $key): void
    {
        $this->addKeyIfNotExist($key);
        if ($this->input($key) !== null) {
            $this->merge([$key => (int)$this->input($key)]);
        }
    }

    /**
     * @param string $key
     */
    protected function convertToString(string $key): void
    {
        $this->addKeyIfNotExist($key);
        if ($this->input($key) !== null) {
            $this->merge([$key => (string)$this->input($key)]);
        }
    }

    /**
     * @param string $key
     */
    protected function convertToCarbonDate(string $key): void
    {
        $this->addKeyIfNotExist($key);
        if ($this->input($key) !== null) {
            $this->merge([$key => Carbon::parse($this->input($key))]);
        }
    }

    protected function convertToNumber(string $key): void
    {
        $value = $this->input($key);
        $value = implode('', explode('.', $value));
        $value = implode('.', explode(',', $value));

        $this->addKeyIfNotExist($key);
        if ($this->input($key) !== null) {
            $this->merge([$key => (float)$value]);
        }
    }

    protected function addPaginationFields(): void
    {
        $this->convertToInteger('perPage');
        $this->validationRules['perPage'] = 'nullable|integer|min:1';
        $this->convertToInteger('page');
        $this->validationRules['page'] = 'nullable|integer|min:1';
    }

    protected function addSearchField(): void
    {
        $this->convertToString('search');
        $this->validationRules['search'] = 'nullable|string';
    }
}
