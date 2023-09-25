<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubmissionVideoPhotoDetail extends Model
{
    protected $fillable = [
        'submission_video_photo_id', 
        'cso_id', 'detail_date', 
        'url_drive', 'status', 
        'mpc_wakimart', 'name',
        'phone', 'address', 'souvenir',
        'acc_description',
    ];

    public function cso()
    {
        return $this->belongsTo(Cso::class);
    }
    
    public function submissionVideoPhoto()
    {
        return $this->belongsTo(SubmissionVideoPhoto::class);
    }
}
