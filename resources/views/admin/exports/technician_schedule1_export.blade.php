<style type="text/css">
    th, td{
        border: 1px solid black;
        text-align: center;
    }
</style>

@php
    $index = 1;
    $stillGet = true;
    $arrTechnicianSchedules = current((array) $technician_schedules);
    $tglSekarang = null;
    if($arrTechnicianSchedules != null){
        $tglSekarang = new DateTime($arrTechnicianSchedules[0]['appointment']);
    }
@endphp

<table>
    <tbody>
        <tr>
            <th rowspan="3" colspan="8" style="font-weight: 900; font-size: 14px">Laporan Service  {{ $tglSekarang->format('F Y') }}</th>
        </tr>
        <tr></tr>
        <tr></tr>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Teknisi</th>
            <th>Customer</th>
            <th>Alamat</th>
            <th>Barang</th>
            <th>Kerusakan</th>
            <th>D/O</th>
        </tr>
        @php 
            $old_ts_id = $technician_schedules[0]->technician_id;
            $i = 0;
        @endphp
        @foreach ($technician_schedules as $ts)  
            @php
                $i++;
                if ($old_ts_id != $ts->technician_id) {
                    $i = 1;
                    echo "<tr><td colspan='8' style='background-color: #D3D3D3;'></td></tr>";
                    $old_ts_id = $ts->technician_id;
                }
            @endphp
            @foreach ($ts->product_technician_schedule_withProduct as $key=>$product_ts)
            <tr>
                @php 
                    $countProduct = count($ts->product_technician_schedule_withProduct);
                    $issues = json_decode($product_ts->issues); 
                @endphp
                @if ($key == 0)
                <td rowspan="{{ $countProduct }}">{{ $i }}</td>
                <td rowspan="{{ $countProduct }}">{{ date('d-m-Y H:i', strtotime($ts->appointment)) }}</td>
                <td rowspan="{{ $countProduct }}">{{ $ts->cso->name }}</td>
                <td rowspan="{{ $countProduct }}">{{ $ts->name }}</td>
                <td rowspan="{{ $countProduct }}">{{ $ts->address }}</td>
                @endif
                
                <td>{{ $product_ts->product['name'] ?? $product_ts->other_product }}</td>
                <td>{{ implode(",", $issues[0]->issues)}} {{ $issues[1]->desc ? '('. $issues[1]->desc .')' : '' }}</td>

                @if ($key == 0)
                <td rowspan="{{ $countProduct }}">{{ $ts->d_o }}</td>
                @endif
            </tr>
            @endforeach
        @endforeach
    </tbody>
</table>