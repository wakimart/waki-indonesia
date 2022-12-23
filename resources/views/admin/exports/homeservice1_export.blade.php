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
            <th style="font-weight: 900; text-align:center; border: 1px solid black" colspan="{{count($Branches) + 2}}">Jadwal Home Service</th>
        </tr>
        <tr>
            <th style="text-align:center; border: 1px solid black" colspan="{{count($Branches) + 2}}">Tanggal : {{ $tglSekarang->format('j/m/Y') }}</th>
        </tr>
        <tr><th></th></tr>
        <tr>
            <th style="vertical-align: center; text-align: center; border: 1px solid black; height: 55px; width: 15px">No.</th>
            <th style="vertical-align: center; text-align: center; border: 1px solid black; width: 25px">-</th>
            @php $colors = ['#55efc4', '#81ecec', '#74b9ff', '#a29bfe', '#dfe6e9', '#b2bec3', '#6c5ce7', '#0984e3', '#00cec9', '#00b894', '#ffeaa7', '#fab1a0', '#ff7675', '#fd79a8', '#fdcb6e', '#e17055', '#d63031', '#e84393', '#fbc531', '#e1b12c', '#4cd137' ]  @endphp
            @foreach($Branches as $i => $branch)
                <th style="vertical-align: center; text-align: center; border: 1px solid black; width: 18px; word-wrap: break-word; background: {{$colors[$i]}}">{{ $branch['code'] }} - {{ $branch['name'] }}</th>
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
                <td rowspan="6" style="vertical-align: center; text-align: center; border: 1px solid black">{{ $index }}</td>
                <td style="vertical-align: center; text-align: center; border: 1px solid black; background: yellow;">Appointment</td>
                @foreach($nowHomeService as $showHS)
                    @if($showHS != null)
                        @php
                            $dt = new DateTime($showHS['appointment']);
                        @endphp
                        <td style="vertical-align: center; text-align: center; border: 1px solid black; background: yellow;">{{ $dt->format('H:i') }}</td>                    
                    @else
                        <td style="border: 1px solid black; background: yellow;"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="vertical-align: center; text-align: center; border: 1px solid black"><b>Tipe Homeservice</b></td>
                @foreach($nowHomeService as $showHS)
                    @if($showHS != null)
                        <td style="vertical-align: center; text-align: center; border: 1px solid black; word-wrap: break-word; height: 50px"><b>{{ $showHS['type_homeservices'] }}</b></td>
                    @else
                        <td style="border: 1px solid black"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="vertical-align: center; text-align: center; border: 1px solid black">Customer Name</td>
                @foreach($nowHomeService as $showHS)
                    @if($showHS != null)
                        <td style="vertical-align: center; text-align: center; border: 1px solid black; word-wrap: break-word; height: 50px">{{ $showHS['name'] }}</td>                    
                    @else
                        <td style="border: 1px solid black"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td rowspan="2" style="vertical-align: center; text-align: center; border: 1px solid black">Address</td>
                @foreach($nowHomeService as $showHS)
                    @if($showHS != null)
                        <td style="vertical-align: center; text-align: center; border: 1px solid black; word-wrap: break-word; height: 100px">{{ $showHS['address'] }}</td>
                    @else
                        <td style="border: 1px solid black"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                @foreach($nowHomeService as $showHS)
                    @if($showHS != null)
                        <td style="vertical-align: center; text-align: center; border: 1px solid black; word-wrap: break-word; height: 100px">{{ $showHS->provinceObj['province'] }}, {!! $showHS->cityObj['type'].' '.$showHS->cityObj['city_name'] !!}, {{ $showHS->districObj['subdistrict_name'] }}</td>
                    @else
                        <td style="border: 1px solid black"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="vertical-align: center; text-align: center; border: 1px solid black">CSO</td>
                @foreach($nowHomeService as $showHS)
                    @if($showHS != null)
                        @php
                            $csoNya = App\Cso::find($showHS['cso_id']);
                        @endphp
                        <td style="vertical-align: center; text-align: center; border: 1px solid black; word-wrap: break-word; height: 50px">{{ $csoNya['code'] }} - {{ $csoNya['name'] }}</td>
                    @else
                        <td style="border: 1px solid black"></td>
                    @endif
                @endforeach
            </tr>

            @php $index++; @endphp
        @endwhile
    </tbody>
</table>