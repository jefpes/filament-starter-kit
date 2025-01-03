<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class UniqueWithinTenant implements ValidationRule
{
    protected string $table;

    protected string $column;

    protected mixed $ignore;

    public function __construct(string $table, string $column, mixed $ignore = null)
    {
        $this->table  = $table;
        $this->column = $column;
        $this->ignore = $ignore;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = DB::table($this->table)
            ->where($this->column, $value)
            ->where('tenant_id', (tenant()->id ?? null));

        // Ignora o registro atual apenas se houver um ID para ignorar
        if ($this->ignore !== null) {
            $query->where('id', '!=', $this->ignore);
        }

        if ($query->exists()) {
            $fail("$value já existe.");
        }
    }

    public static function make(string $table, ?string $column = null, mixed $ignore = null): self
    {
        return new self($table, $column, $ignore);
    }
}
