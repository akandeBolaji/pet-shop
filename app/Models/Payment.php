<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'type',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function setDetailsAttribute($value)
    {
        switch ($this->type) {
            case 'credit_card':
                $this->attributes['details'] = json_encode([
                    'holder_name' => $value['holder_name'],
                    'number' => $value['number'],
                    'ccv' => $value['ccv'],
                    'expire_date' => $value['expire_date'],
                ]);
                break;

            case 'cash_on_delivery':
                $this->attributes['details'] = json_encode([
                    'first_name' => $value['first_name'],
                    'last_name' => $value['last_name'],
                    'address' => $value['address'],
                ]);
                break;

            case 'bank_transfer':
                $this->attributes['details'] = json_encode([
                    'swift' => $value['swift'],
                    'iban' => $value['iban'],
                    'name' => $value['name'],
                ]);
                break;
        }
    }
}

