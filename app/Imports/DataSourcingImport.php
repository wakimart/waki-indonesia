<?php

namespace App\Imports;

use App\Branch;
use App\Cso;
use App\DataSourcing;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataSourcingImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if ($row['branch_id'] != null) {
            $row['branch_id'] = Branch::where('code', $row['b_code'])->value('id');
        }

        if ($row['cso_id'] != null) {
            $row['cso_id'] = Cso::where('code', $row['c_code'])->value('id');
        }

        return new DataSourcing([
            'name' => $row['name'],
            'phone' => $this->Decr($row['phone']),
            'address' => $row['address'],
            'active' => $row['active'],
            'created_at' => $row['created_at'],
            'updated_at' => $row['updated_at'],
            'branch_id' => $row['branch_id'],
            'cso_id' => $row['cso_id'],
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
