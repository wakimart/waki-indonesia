<style type="text/css">
    th, td{
        border: 1px solid black;
        text-align: center;
    }
</style>


<table>
    <thead>
        <tr>
            <th>Branch</th>
            <th>OrderDate</th>
            <th>Order Code</th>
            <th>CSO</th>
            <th>Name</th>
            <th>Phone Number</th>
            <th>Address</th>
            <th>Promo Names</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        
        @if (count($order)!=0)
            @foreach ($order as $order )
            <tr>
                <td> {{$order->branch['code']}} </td>
                <td> {{$order['orderDate']}} </td>
                <td> {{$order['code']}} </td>
                <td> {{$order->getCSO()->code}} </td>
                <td> {{$order['name']}} </td>
                <td> {{$order['phone']}} </td>
                <td> {{$order['address']}} </td>

                @php
                    $ProductPromos = json_decode($order['product'], true);
                @endphp
                <td> {{ App\DeliveryOrder::$Promo[$ProductPromos[0]['id']]['code'] }} - {{ App\DeliveryOrder::$Promo[$ProductPromos[0]['id']]['name'] }} ( {{ App\DeliveryOrder::$Promo[$ProductPromos[0]['id']]['harga'] }} ) </td>

                <td> {{$order['total_payment']}}</td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>