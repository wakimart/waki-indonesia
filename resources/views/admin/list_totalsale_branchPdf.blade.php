<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pdf.css') }}">
    <title></title>
</head>

<body>

    <div class="text-center" style="margin: 8px;">
        <button onclick='myFunction()' class='btn btn-primary m-2' style="width:200px">Print</button>
    </div>
    <div id="section-to-print">
        <div class="page a4">
            <div class="header">
                <div style="position:absolute;bottom:0;right:100px;">
                    <!--<img src="#" class="pt-10" style="width: 84px; height: 84px;"/>-->
                </div>

            </div>
            <div class="content">
                <p class="title">
                    Order Report By Branch
                </p>
                <br>
                <h4>Branch : {{ $currentBranch ? $currentBranch['code'] . " - " . $currentBranch['name'] : "All Branch" }}</h4>
                <h5>
                    Date: {{ date("d/m/Y", strtotime($startDate)) }} - {{ date("d/m/Y", strtotime($endDate)) }}</h5>
                </h5>
                <div>
                    Total : {{ $countTotalSales }} data
                </div>

                <p class="normal">
                <table border="1" width="100%">
                    <thead>
                        <tr>
                            <th class="col-md-1" style="width: 10%; text-align:center;">No</th>
                            <th class="col-md-3" style="width: 30%; text-align:center;">Cso</th>
                            <th class="col-md-2" style="width: 20%; text-align:center;"> Bank In</th>
                            <th class="col-md-2" style="width: 20%; text-align:center;"> Debit</th>
                            <th class="col-md-2" style="width: 20%; text-align:center;"> Netto Debit</th>
                            <th class="col-md-2" style="width: 20%; text-align:center;"> Card</th>
                            <th class="col-md-2" style="width: 20%; text-align:center;"> Netto Card</th>
                            <th class="col-md-2" style="width: 20%; text-align:center;"> Total Sale </th>
                            <th class="col-md-2" style="width: 20%; text-align:center;"> Total Sale Netto </th>
                        </tr>
                    </thead>
                    <tbody name="collection">
                        @php
                            $totalSale = 0;
                            $totalSaleNetto = 0;
                        @endphp
                        @foreach ($total_sales as $key => $total_sale)
                            @php
                                $totalSale_temp = $total_sale['sum_ts_bank_in'] + $total_sale['sum_ts_debit'] + $total_sale['sum_ts_card'];
                                $totalSaleNetto_temp = $total_sale['sum_ts_netto_debit'] + $total_sale['sum_ts_netto_card'];
                                $totalSale += $totalSale_temp;
                                $totalSaleNetto += $totalSaleNetto_temp;
                            @endphp
                            <tr>
                                <td style="text-align:center;">{{ $key + 1 }}</td>
                                <td>{{ $total_sale['code'] }} - {{ $total_sale['name'] }}</td>
                                <td style="text-align:right;">Rp. {{ number_format($total_sale['sum_ts_bank_in']) }}</td>
                                <td style="text-align:right;">Rp. {{ number_format($total_sale['sum_ts_debit']) }}</td>
                                <td style="text-align:right;">Rp. {{ number_format($total_sale['sum_ts_netto_debit']) }}</td>
                                <td style="text-align:right;">Rp. {{ number_format($total_sale['sum_ts_card']) }}</td>
                                <td style="text-align:right;">Rp. {{ number_format($total_sale['sum_ts_netto_card']) }}</td>
                                <td style="text-align:right;">Rp. {{ number_format($totalSale_temp) }}</td>
                                <td style="text-align:right;">Rp. {{ number_format($totalSaleNetto_temp) }}</td>
                            </tr>
                        @endforeach
                        <tr class="text-right">
                            <th colspan="2">TOTAL SALES</th>
                            <th>Rp. {{ number_format($total_sales->sum('sum_ts_bank_in')) }}</th>
                            <th>Rp. {{ number_format($total_sales->sum('sum_ts_debit')) }}</th>
                            <th>Rp. {{ number_format($total_sales->sum('sum_ts_netto_debit')) }}</th>
                            <th>Rp. {{ number_format($total_sales->sum('sum_ts_card')) }}</th>
                            <th>Rp. {{ number_format($total_sales->sum('sum_ts_netto_card')) }}</th>
                            <th>Rp. {{ number_format($totalSale) }}</th>
                            <th>Rp. {{ number_format($totalSaleNetto) }}</th>
                        </tr>
                    </tbody>

                </table>
                </p>

            </div>

        </div>


    </div>
    </div>
    <script>
        function myFunction() {
            var printContents = document.getElementById("section-to-print").innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;

            return true;
        }
    </script>
</body>

</html>
