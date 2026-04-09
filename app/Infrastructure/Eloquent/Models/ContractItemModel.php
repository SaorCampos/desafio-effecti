<?php

namespace App\Infrastructure\Eloquent\Models;

use App\Infrastructure\Eloquent\Models\ServiceModel;
use Database\Factories\ContractItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractItemModel extends Model
{
    use HasFactory;

    protected $table = 'contract_items';

    protected $fillable = [
        'contract_id',
        'service_id',
        'quantity',
        'unit_value'
    ];

    protected $casts = [
        'unit_value' => 'decimal:2',
    ];

    public function service()
    {
        return $this->belongsTo(ServiceModel::class, 'service_id');
    }

    protected static function newFactory()
    {
        return ContractItemFactory::new();
    }

    public function contract()
    {
        return $this->belongsTo(ContractModel::class, 'contract_id');
    }
}
