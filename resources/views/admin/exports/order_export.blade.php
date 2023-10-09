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
            <th>Order Code | DO</th>
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
                <td> Code : {{$order['code']}} <br> DO : {{$order['temp_no']}}  </td>
                <td> {{$order->getCSO()->code}} </td>
                <td> {{$order['name']}} </td>
                <td> {{$order['phone']}} </td>
                <td> {{$order['address']}} </td>

                @php
                    $curnt_beli = 1;
                    $curnt_hadiah = 1;
                    $curnt_upgrade = 1;
                    $product_order = "";
                    $product_beli = "";
                    $product_hadiah = "";
                    $product_upgrade = "";

                    foreach ($order->orderDetail as $perProduct) {
                        if($perProduct['type'] == "pembelian"){
                            if(isset($perProduct->product['code'])){
                                $product_beli .= $perProduct->product['code']."-".$perProduct->product['name']." (Pembelian)";
                            }
                            else{
                                $product_beli .= $perProduct['other']." (Pembelian)";
                            }
                            $tempTot = $order->orderDetail->where('type', "pembelian")->count();
                            if($tempTot > 1 && $curnt_beli < $tempTot){
                                $curnt_beli++;
                                $product_beli .= "<br>";
                            }
                        }
                        elseif($perProduct['type'] == "prize"){
                            if(isset($perProduct->product['code'])){
                                $product_hadiah .= $perProduct->product['code']."-".$perProduct->product['name']." (Hadiah)";
                            }
                            else{
                                $product_hadiah .= $perProduct['other']." (Hadiah)";
                            }
                            $tempTot = $order->orderDetail->where('type', "prize")->count();
                            if($tempTot > 1 && $curnt_hadiah < $tempTot){
                                $curnt_hadiah++;
                                $product_hadiah .= "<br>";
                            }
                        }
                        elseif($perProduct['type'] == "upgrade"){
                            if(isset($perProduct->product['code'])){
                                $product_upgrade .= $perProduct->product['code']."-".$perProduct->product['name']." (Upgrade)";
                            }
                            else{
                                $product_upgrade .= $perProduct['other']." (Upgrade)";
                            }
                            $tempTot = $order->orderDetail->where('type', "upgrade")->count();
                            if($tempTot > 1 && $curnt_upgrade < $tempTot){
                                $curnt_upgrade++;
                                $product_upgrade .= "<br>";
                            }
                        }
                    }
                @endphp
                <td> {!! $product_beli !!}<br>{!! $product_hadiah !!}<br>{!! $product_upgrade !!} </td>

                <td> {{$order['total_payment']}}</td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>