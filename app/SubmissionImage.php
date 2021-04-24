<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubmissionImage extends Model
{
    protected $fillable = [
        "submission_id",
        "image_1",
        "image_2",
        "image_3",
        "image_4",
        "image_5",
        "active",
    ];

    public function submission()
    {
        return $this->belongsTo("App\Submission");
    }
}
