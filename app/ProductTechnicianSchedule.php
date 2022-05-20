<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductTechnicianSchedule extends Model
{
    protected $table = "product_technician_schedules";
    protected $fillable = [
        'due_date', 'issues', 'other_product', 'product_id', 'technician_schedule_id', 'active',
    ];

    public function technician_schedule()
    {
        return $this->belongsTo('App\TechnicianSchedule', 'technician_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
