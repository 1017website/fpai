<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['key', 'value', 'group', 'type', 'label', 'help_text', 'position'];

    public static function values(): array
    {
        return static::query()->pluck('value', 'key')->all();
    }
}
