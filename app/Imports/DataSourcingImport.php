<?php

namespace App\Imports;

use App\Cso;
use App\DataSourcing;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataSourcingImport implements ToModel, WithHeadingRow
{
    protected $csos;

    public function __construct()
    {
        $this->csos = Cso::get(['id'])->keyBy('id');
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new DataSourcing([
            'name' => $row['name'],
            'phone' => $this->Decr($row['phone']),
            'address' => $row['address'],
            'active' => $row['active'],
            'created_at' => $row['created_at'],
            'updated_at' => $row['updated_at'],
            'branch_id' => $row['branch_id'],
            'cso_id' => $this->csos[$row['cso_id']]['id'] ?? null,
            'type_customer_id' => $row['type_customer_id'],
            'user_id' => Auth::user()['id'],
        ]);
    }

    public function Decr($x)
    {
        if ($x != null) {
            $pj = mb_strlen($x);
            //return $pj;
            $hasil = '';
            //return mb_chr('8223');
            //return ord('†'); //#226
            // return mb_ord('†'); //#8224
            // return mb_chr(134); //
            for($i=0; $i<$pj; $i++)
            {
                $ac = ord(substr($x, $i, 1))+4;
                //return $ac. "-";
                if($ac % 2 == 1)
                {
                    $ac+=255;
                }
                $hs = $ac/2;
                //return $hs . "-";
                $hasil .= chr($hs);
            }
            return $hasil;
        } else 
            return null;
    }
}
