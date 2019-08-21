<?php

namespace App\Models\Traits;

trait ColumnValueExists
{
    /**
     * 判断指定字段值是否已经存在.
     *
     * @param  string  $column
     * @param  mixed  $value
     * @param  static|null  $without
     * @param  \Illuminate\Database\Eloquent\Builder|null  $query
     * @return bool
     */
    public static function columnValueExists(string $column, $value, $without = null, $query = null)
    {
        if (is_null($query)) {
            $query = static::query();
        }

        $target = $query->where($column, $value)->first();
        $exists = mb_strtoupper(optional($target)->{$column}) == mb_strtoupper($value);

        if (! is_null($without) && $without->is($target)) {
            $exists = false;
        }

        return $exists;
    }
}
