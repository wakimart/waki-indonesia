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
            <th>Order Code</th>
            <th>CSO</th>
            <th>Name</th>
            <th>Phone Number</th>
            <th>City</th>
            <th>Distric</th>
            <th>Address</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        
        @if (count($order)!=0)
            @foreach ($order as $order )
            <tr>
                <td> {{$order['orderDate']}} </td>
                <td> {{$order['code']}} </td>
                <td> {{$order->getCSO()->code}} </td>
                <td> {{$order['name']}} </td>
                <td> {{$order['phone']}} </td>
                <td> {{$order['city']}} </td>
                <td> {{$order['distric']}} </td>
                <td> {{$order['address']}} </td>
                <td> {{$order['total_payment']}}</td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>