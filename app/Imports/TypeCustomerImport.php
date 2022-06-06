<?php

namespace App\Imports;

use App\TypeCustomer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TypeCustomerImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new TypeCustomer([
            'id' => $row['id'],
            'name' => $row['name'],
            'active' => $row['active'],
            'created_at' => $this->transformDate($row['created_at']),
            'updated_at' => $this->transformDate($row['updated_at']),
        ]);
    }

    public function transformDate($date)
    {
        if ($date != null) {
            $format = 'd/m/Y H:i';
            return \Carbon\Carbon::createFromFormat($format, $date)->toDateTimeString();
        } else
            return null;
    }
}
