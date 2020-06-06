<style type="text/css">
    th, td{
        border: 1px solid black;
        text-align: center;
    }
</style>

@php
    $index = 1;
    $stillGet = true;
    $arrHomeServices = current((array) $HomeServices);
    $tglSekarang = null;
    if($arrHomeServices != null){
        $tglSekarang = new DateTime($arrHomeServices[0]['appointment']);
    }
@endphp

<table>
    <thead>
        <tr>
            <th style="font-weight: 900;">Jadwal Home Service</th>
        </tr>
        <tr>
            <th>Tanggal : {{ $tglSekarang->format('j/m/Y') }}</th>
        </tr>
        <tr><th></th></tr>
        <tr>
            <th style="vertical-align: middle;">No.</th>
            <th style="vertical-align: middle;">-</th>
            @foreach($Branches as $branch)
                <th style="vertical-align: middle;">{{ $branch['code'] }} - {{ $branch['name'] }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @while($stillGet)
            @php $nowHomeService = []; @endphp
            @foreach($Branches as $keyB => $branch)
                @php
                    $nowBranchNull = true;
                    foreach ($arrHomeServices as $keyHS => $HomeServiceNya) {
                        if($HomeServiceNya['branch_id'] == $branch['id']){
                            $nowBranchNull = false;
                            array_push($nowHomeService, $HomeServiceNya);
                            array_splice($arrHomeServices, $keyHS, 1);
                            break;
                        }
                    }
                    if($nowBranchNull){
                        array_push($nowHomeService, null);
                    }
                @endphp
            @endforeach
            
            @php
                $stillGet = false;
                foreach($nowHomeService as $showHS){
                    if($showHS != null){
                        $stillGet = true;
                    }
                }
                if(!$stillGet){
                    break;
                }
            @endphp
            
            <tr>
                <td rowspan="8">{{ $index }}</td>
                <td>Appointment</td>
                @foreach($nowHomeService as $showHS)
                    @if($showHS != null)
                        @php
                            $dt = new DateTime($showHS['appointment']);
                        @endphp
                        <td>{{ $dt->format('H:i') }}</td>                    
                    @else
                        <td></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td>MPC</td>
                @foreach($nowHomeService as $showHS)
                    @if($showHS != null)
                        <td>{{ $showHS['no_member'] }}</td>
                    @else
                        <td></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td>Customer Name</td>
                @foreach($nowHomeService as $showHS)
                    @if($showHS != null)
                        <td>{{ $showHS['name'] }}</td>                    
                    @else
                        <td></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td>Phone</td>
                @foreach($nowHomeService as $showHS)
                    @if($showHS != null)
                        <td>{{ $showHS['phone'] }}</td>
                    @else
                        <td></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td>Address</td>
                @foreach($nowHomeService as $showHS)
                    @if($showHS != null)
                        <td>{{ $showHS['address'] }}</td>
                    @else
                        <td></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td>CSO</td>
                @foreach($nowHomeService as $showHS)
                    @if($showHS != null)
                        @php
                            $csoNya = App\Cso::find($showHS['cso_id']);
                        @endphp
                        <td>{{ $csoNya['code'] }} - {{ $csoNya['name'] }}</td>
                    @else
                        <td></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td>Delivery Name</td>
                @foreach($nowHomeService as $showHS)
                    <td></td>
                @endforeach
            </tr>
            <tr>
                <td>Result</td>
                @foreach($nowHomeService as $showHS)
                    <td></td>
                @endforeach
            </tr>

            @php $index++; @endphp
        @endwhile
    </tbody>
</table>