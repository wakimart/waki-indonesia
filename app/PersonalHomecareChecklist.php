<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonalHomecareChecklist extends Model
{
    public static $completeness_list = [
        "A" => ["Pen", "Matras (Single Cable)", "Alas Kaki", "Buku Manual", "Connector Cable (Hpt Matras)", "Cable Power"],
        "B" => ["Pen", "Matras (Single Cable)", "Alas Kaki", "Buku Manual", "Connector Cable (Hpt Matras)", "Cable Power"],
        "C" => ["Pen", "Matras (Single Cable)", "Alas Kaki", "Buku Manual", "Connector Cable (Hpt Matras)", "Cable Power"],
        "D" => ["Pulse Pegangan", "Buku Manual", "Bantalan Pantat", "Pad Bantalan", "Botol Pelastik", "Bandage", "Sabuk Pinggang", "Kabel"],
        "E" => ["Filter Udara", "Tangki Air", "Kabel"],
        "F" => ["Filter Udara", "Tangki Air", "Kabel"],
    ];

    protected $fillable = [
        "condition",
        "image",
    ];

    protected $casts = [
        "condition" => "array",
        "image" => "array",
    ];

    public function personalHomecareOut()
    {
        return $this->hasMany("App\PersonalHomecare", "checklist_out");
    }

    public function personalHomecareIn()
    {
        return $this->hasMany("App\PersonalHomecare", "checklist_in");
    }
}
