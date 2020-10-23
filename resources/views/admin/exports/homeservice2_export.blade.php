<style type="text/css">
    th, td{
        border: 1px solid black;
        text-align: center;
    }
</style>


<table>
    <thead>
        <tr>
            <th>Created_at</th>
            <th>Date Appointment</th>
            <th>Time Appointment</th>
            <th>Customer Name</th>
            <th>Customer Phone</th>
            <th>City</th>
            <th>Address</th>
            <th>Branch Code</th>
            <th>CSO</th>
        </tr>
    </thead>
    <tbody>
        @if (count($HomeServices)!=0)
            @foreach ($HomeServices as $homeService )
            <tr>
                <td> {{$homeService['created_at']}} </td>
                <td> {{date('Y-m-d', strtotime($homeService['appointment']))}} </td>
                <td> {{date('H:i:s', strtotime($homeService['appointment']))}} </td>
                <td> {{$homeService['name']}} </td>
                <td> {{$homeService['phone']}} </td>
                <td> {{$homeService['city']}} </td>
                <td> {{$homeService['address']}} </td>
                <td> {{$homeService['branches_code']."-".$homeService['branches_name']}} </td>
                <td> {{$homeService['csos_code']."-".$homeService['csos_name']}} </td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>