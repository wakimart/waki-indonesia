<style type="text/css">
    th, td{
        border: 1px solid black;
        text-align: center;
    }
</style>


<table>
    <thead>
        <tr>
            <th rowspan="2" colspan="2"> - </th>
            <th colspan="{{ sizeof($result['VVIP (Type A)']['Home service']) }}">Branch</th>
        </tr>
        <tr>
            @foreach($result['VVIP (Type A)']['Home service'] as $key => $perBranch)
                <th>{{ $key }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($result as $keyNya => $perTyepCust)
            @php
                $firstRow = true;
            @endphp

            <tr>            
                <td rowspan="{{ sizeof($perTyepCust) }}">{{ $keyNya }}</td>
                @foreach($perTyepCust as $key => $type_hs)
                    <td style="text-align: left;">{{ $key }}</td>
                    @foreach($type_hs as $perBranch)
                        <td>{{ $perBranch }}</td>
                    @endforeach
                    @php
                        break;
                    @endphp
                @endforeach
            </tr>

            @foreach($perTyepCust as $key => $type_hs)
                @php
                    if($firstRow){
                        $firstRow = false;
                        continue;
                    }
                @endphp
                <tr>
                    <td style="text-align: left;">{{ $key }}</td>
                    @foreach($type_hs as $perBranch)
                        <td>{{ $perBranch }}</td>
                    @endforeach
                </tr>
            @endforeach
        @endforeach
        <tr>
            <td colspan="2">Total </td>
            @foreach($totalPerBranch as $perTotal)
                <td>{{ $perTotal }}</td>
            @endforeach
        </tr>

    </tbody>
</table>