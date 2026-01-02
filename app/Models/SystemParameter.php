<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemParameter extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'value',
        'description',
    ];

    /**
     * Get a system parameter value by code
     */
    public static function getValue(string $code, $default = null)
    {
        $param = static::where('code', $code)->first();
        return $param ? $param->value : $default;
    }

    /**
     * Set a system parameter value by code
     */
    public static function setValue(string $code, $value): void
    {
        static::updateOrCreate(
            ['code' => $code],
            ['value' => $value]
        );
    }
}
