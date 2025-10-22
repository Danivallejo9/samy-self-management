<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = "VWS_PEDIDOS";
    protected $primaryKey = 'PEDIDO';

    protected function statusRemittance(): Attribute //status_remittance
    {
        return Attribute::make(
            get: function (?string $value) {
                $value = strtolower($value);
                return ucfirst($value);
            }
        );
    }

    protected function status(): Attribute //status_remittance
    {
        return Attribute::make(
            get: function (string $value) {
                $value = strtolower($value);
                return ucfirst($value);
            }
        );
    }

    protected function description(): Attribute
    {
        return Attribute::make(
            get: function (string $value) {
                $value = strtolower($value);
                return ucfirst($value);
            }
        );
    }
}
