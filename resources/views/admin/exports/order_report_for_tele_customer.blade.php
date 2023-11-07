<style type="text/css">
    th, td{
        border: 1px solid black;
        text-align: center;
    }
</style>


<table>
    <thead>
        <tr>
            <th>OrderDate</th>
            <th>Temp No</th>
            <th>Name</th>
            <th>Phone Number</th>
            <th>Marks</th>
        </tr>
    </thead>
    <tbody>
        
        @if (count($order)!=0)
            @foreach ($order as $order )
            <tr>
                <td> {{$order['orderDate']}} </td>
                <td> {{$order['temp_no']}}  </td>
                <td> {{$order['name']}} </td>
                <td> {{$order['phone']}} </td>
                <td></td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>