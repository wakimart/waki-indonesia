<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubmissionPromo extends Model
{
    protected $fillable = [
        "submission_id",
        "promo_1",
        "promo_2",
        "qty_1",
        "qty_2",
        "other_1",
        "other_2",
        "active",
    ];

    public function submission()
    {
        return $this->belongsTo("App\Submission");
    }

    public function promo_1()
    {
        return $this->belongsTo("App\Promo", "promo_1");
    }

    public function promo_2()
    {
        return $this->belongsTo("App\Promo", "promo_2");
    }
}
