<style type="text/css">
    th, td{
        border: 1px solid black;
        text-align: center;
    }
</style>

<table>
	<thead>
		<tr>
            <th style="border: 1px solid black; font-weight: bold;">No</th>
            <th style="border: 1px solid black; font-weight: bold;">Acceptance Date</th>
            <th style="border: 1px solid black; font-weight: bold;">Bill NO</th>
            <th style="border: 1px solid black; font-weight: bold;">Acceptance Product</th>
            <th style="border: 1px solid black; font-weight: bold;">Branch - CSO</th>
            <th style="border: 1px solid black; font-weight: bold;">Status</th>
        </tr>
	</thead>
	<tbody>
		@if(count($acceptances) != 0)
		@php $tempBranch = $acceptances[0]->branch->id;  @endphp
			@foreach($acceptances as $key => $acceptance)			

			@if($tempBranch != $acceptance->branch->id)
				@php $tempBranch = $acceptance->branch->id;  @endphp
				<tr>
					<td colspan="6" style="border: 1px solid black; background-color: #CCCCCC"></td>
				</tr>
			@endif

			<tr>
				<td style="border: 1px solid black; {{ $acceptance['without_commission'] ? 'background-color: #FFFF00' : '' }}">{{$key + 1}}</td>
				<td style="border: 1px solid black; {{ $acceptance['without_commission'] ? 'background-color: #FFFF00' : '' }}">{{ date("d/m/Y", strtotime($acceptance['upgrade_date'])) }}</td>
				<td style="border: 1px solid black; {{ $acceptance['without_commission'] ? 'background-color: #FFFF00' : '' }}">{{$acceptance['bill_do']}}</td>
				<td style="border: 1px solid black; {{ $acceptance['without_commission'] ? 'background-color: #FFFF00' : '' }}">
					{{ $acceptance['other_product'] == null ? $acceptance->oldproduct['code'] : $acceptance['other_product'] }} -> {{ $acceptance->newproduct['code'] }}
				</td>
				<td style="border: 1px solid black; {{ $acceptance['without_commission'] ? 'background-color: #FFFF00' : '' }}">
					{{ $acceptance->branch->code }} - {{ $acceptance->cso->code }}
				</td>
				<td style="border: 1px solid black; {{ $acceptance['without_commission'] ? 'background-color: #FFFF00' : '' }}">
					@if(strtolower($acceptance['status']) == "new")
                        <span class="badge badge-primary">New</span>
                    @elseif(strtolower($acceptance['status']) == "approved")
                        <span class="badge badge-success">Approved by : {{ $acceptance->acceptanceLog[sizeof($acceptance->acceptanceLog)-1]->user['name'] }}</span>
                        @if($acceptance['without_commission'])
                        	<br><span>Without Commission</span>
                        @endif
                    @elseif(strtolower($acceptance['status']) == "rejected")
                        <span class="badge badge-danger">Rejected by : {{ $acceptance->acceptanceLog[sizeof($acceptance->acceptanceLog)-1]->user['name'] }}</span>
                    @endif
				</td>
			</tr>
			@endforeach
		@endif
	</tbody>
</table>