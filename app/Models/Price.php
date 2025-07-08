<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $type
 * @property string $sub_type
 * @property int $price
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read string $formatted_amount // Accessor
 **/
class Price extends Model
{
    protected $fillable = ['type','sub_type','price'];
}
