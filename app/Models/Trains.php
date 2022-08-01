<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trains extends Model
{
    use HasFactory,softDeletes;
    protected $fillable = [
        'train_name',
        'departure_date_time',
        'no_of_seats',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function bookings(){
        return $this->hasMany(TrainBookings::class,'train_id','id');
    }
}
