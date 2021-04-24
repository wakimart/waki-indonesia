<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubmissionDeliveryorder extends Model
{
    protected $fillable = [
        "submission_id",
        "no_deliveryorder",
    ];

    public function submission()
    {
        return $this->belongsTo("App\Submission");
    }
}
