<?php

namespace App\Infrastructure\Eloquent\Models;

use App\Infrastructure\Eloquent\Models\ContractItemModel;
use Database\Factories\ContractFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractModel extends Model
{
    use HasFactory;

    protected $table = 'contracts';

    protected $fillable = [
        'client_id',
        'start_date',
        'end_date',
        'status',
        'total_monthly_value',
        'calculation_history'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_monthly_value' => 'decimal:2',
        'calculation_history' => 'array',
    ];

    public function client()
    {
        return $this->belongsTo(ClientModel::class, 'client_id');
    }

    public function items()
    {
        return $this->hasMany(ContractItemModel::class, 'contract_id');
    }

    protected static function newFactory()
    {
        return ContractFactory::new();
    }
}
