<style type="text/css">
    th, td{
        border: 1px solid black;
        text-align: center;
    }
</style>

<table>
	<thead>
		<tr>
            <th>No</th>
            <th>Acceptance Date</th>
            <th>Customer Name</th>
            <th>Customer Phone</th>
            <th>Acceptance Product</th>
            <th>Branch - CSO</th>
            <th>Status</th>
        </tr>
	</thead>
	<tbody>
		@if(count($acceptances) != 0)
			@foreach($acceptances as $key => $acceptance)
			<tr>
				<td>{{$key + 1}}</td>
				<td>{{ date("d/m/Y", strtotime($acceptance['upgrade_date'])) }}</td>
				<td>{{$acceptance['name']}}</td>
				<td>{{$acceptance['phone']}}</td>
				<td>
					{{ $acceptance['other_product'] == null ? $acceptance->oldproduct['code'] : $acceptance['other_product'] }} -> {{ $acceptance->newproduct['code'] }}
				</td>
				<td>
					{{ $acceptance->branch->code }} - {{ $acceptance->cso->code }}
				</td>
				<td>
					@if(strtolower($acceptance['status']) == "new")
                        <span class="badge badge-primary">New</span>
                    @elseif(strtolower($acceptance['status']) == "approved")
                        <span class="badge badge-success">Approved by : {{ $acceptance->acceptanceLog[sizeof($acceptance->acceptanceLog)-1]->user['name'] }}</span>
                    @elseif(strtolower($acceptance['status']) == "rejected")
                        <span class="badge badge-danger">Rejected by : {{ $acceptance->acceptanceLog[sizeof($acceptance->acceptanceLog)-1]->user['name'] }}</span>
                    @endif
				</td>
			</tr>
			@endforeach
		@endif
	</tbody>
</table>