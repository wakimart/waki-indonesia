<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubmissionVideoPhoto extends Model
{
    protected $fillable = [
        'branch_id', 'submission_date', 'type',
        'status', 'active',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function submissionVideoPhotoDetails()
    {
        return $this->hasMany(SubmissionVideoPhotoDetail::class);
    }
}
