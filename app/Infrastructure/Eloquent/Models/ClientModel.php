<?php

namespace App\Infrastructure\Eloquent\Models;

use App\Infrastructure\Eloquent\Models\ContractModel;
use Database\Factories\ClientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientModel extends Model
{
    use HasFactory;

    protected $table = 'clients';

    protected $casts = [
    'document' => 'encrypted',
];

    protected $fillable = [
        'name',
        'document',
        'email',
        'status'
    ];

    public function contracts()
    {
        return $this->hasMany(ContractModel::class, 'client_id');
    }

    protected static function newFactory()
    {
        return ClientFactory::new();
    }
}
