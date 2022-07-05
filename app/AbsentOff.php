<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AbsentOff extends Model
{
    protected $fillable = ['division', 'duration_work', 'start_date', 'end_date', 'duration_off', 'work_on', 'desc', 'cso_id', 'branch_id', 'user_id', 'supervisort_id', 'coordinator_id', 'status'];
    static $status = ['1' => 'new', '2' => 'approved', '3' => 'rejected'];

    public function cso()
    {
        return $this->belongsTo('App\Cso');
    }

    public function branch()
    {
        return $this->belongsTo('App\Branch');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function supervisor()
    {
        return $this->belongsTo('App\User', 'supervisor_id', 'id');
    }

    public function coordinator()
    {
        return $this->belongsTo('App\User', 'coordinator_id', 'id');
    }

    public function historyUpdateAcc()
    {
        $historyUpdates = HistoryUpdate::from('history_updates as hu')
            ->select('hu.*', 'u.name as u_name')
            ->join('users as u', 'u.id', 'hu.user_id')
            ->where([['hu.type_menu', 'Absent Off Acc'], ['hu.menu_id', $this->attributes['id']]])
            ->orderBy('hu.id', 'asc')->get();
        return $historyUpdates;
    }
}
