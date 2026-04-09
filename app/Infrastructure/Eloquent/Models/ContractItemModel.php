<?php

namespace App\Infrastructure\Eloquent\Models;

use App\Infrastructure\Eloquent\Models\ServiceModel;
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
        return $this->belongsTo(ServiceModel::class);
    }
}
