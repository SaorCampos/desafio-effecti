<?php

namespace App\Infrastructure\Eloquent;

use App\Infrastructure\Eloquent\ContractModel;
use Database\Factories\ClientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientModel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'document', 'email', 'status'];

    public function contracts()
    {
        return $this->hasMany(ContractModel::class);
    }

    protected static function newFactory()
    {
        return ClientFactory::new();
    }
}
