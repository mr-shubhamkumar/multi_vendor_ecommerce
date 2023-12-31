<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ShipState;

class ShipDistrict extends Model
{
    use HasFactory;

    protected $guarded = [];

   public function state()
   {
       return $this->belongsTo(ShipState::class, 'state_id', 'id');
   }
}
