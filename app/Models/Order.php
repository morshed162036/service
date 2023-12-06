<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Service;
use App\Models\User;
class Order extends Model
{
    use HasFactory;
    public function service(){
        return $this->belongsTo(Service::class,'service_id');
    }
    public function employee(){
        return $this->belongsTo(User::class,'employee_id');
    }
    public function customer(){
        return $this->belongsTo(User::class,'customer_id');
    }
    public function approve(){
        return $this->belongsTo(User::class,'approved_id');
    }
}
