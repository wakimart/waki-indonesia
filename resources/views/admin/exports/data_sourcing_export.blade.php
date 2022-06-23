<style type="text/css">
    th,
    td {
        border: 1px solid black;
        text-align: center;
    }
</style>

<table>
    <tbody>
        <tr>
            <td>Total : {{ $countDataSourcings }} data </td>
        </tr>
        <tr>
            <th class="center">No.</th>
            <th>Name</th>
            <th>Branch</th>
            <th>CSO</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Type Customer</th>
        </tr>
        @foreach ($data_sourcings as $key => $data_sourcing)
            <tr>
                <td class="right">
                    <?php echo $key+1; ?>
                </td>
                <td>{{ $data_sourcing['name'] }}</td>
                <td>{{ $data_sourcing['b_code'] }}</td>
                <td>{{ $data_sourcing['c_name'] }}</td>
                <td>{{ $data_sourcing['phone'] }}</td>
                <td>{{ $data_sourcing['address'] }}</td>
                <td>
                    {{ $data_sourcing['tc_name'] }}
                    @if ($data_sourcing['created_at'] == null && $data_sourcing['updated_at'] == null)
                        (old data)
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
