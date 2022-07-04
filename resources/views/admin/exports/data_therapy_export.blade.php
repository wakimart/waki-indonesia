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
            <td>Total : {{ $countDataTherapies }} data </td>
        </tr>
        <tr>
            <th class="center">No.</th>
            <th>Name</th>
            <th>KTP</th>
            <th>Branch</th>
            <th>CSO</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Type Customer</th>
        </tr>
        @foreach ($data_therapies as $key => $data_therapy)
            <tr>
                <td class="right">
                    <?php echo $key + 1; ?>
                </td>
                <td>{{ $data_therapy['name'] }}</td>
                <td>{{ $data_therapy['no_ktp'] }}</td>
                <td>{{ $data_therapy['b_code'] }}</td>
                <td>{{ $data_therapy['c_name'] }}</td>
                <td>{{ $data_therapy['phone'] }}</td>
                <td>{{ $data_therapy['address'] }}</td>
                <td>
                    {{ $data_therapy['tc_name'] }}
                    @if ($data_therapy['created_at'] == null && $data_therapy['updated_at'] == null)
                        (old data)
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
