<?php

namespace App\Infrastructure\Eloquent;

use Database\Factories\ServiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceModel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'base_value'];

    protected $casts = [
        'base_value' => 'decimal:2',
    ];

    protected static function newFactory()
    {
        return ServiceFactory::new();
    }
}
