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
                    Total Sale
                </p>
                <br>

                <h5>
                    Date: {{ date("d/m/Y", strtotime($startDate)) }} - {{ date("d/m/Y", strtotime($endDate)) }}</h5>
                </h5>
                <div>
                    Total : {{ $countOrderReports }} data
                </div>

                <p class="normal">
                <table border="1" width="100%">
                    <thead>
                        <tr>
                            <th class="col-md-1" style="width: 10%; text-align:center;">No</th>
                            <th class="col-md-4" style="width: 30%; text-align:center;">Branch</th>
                            <th class="col-md-2" style="width: 20%; text-align:center;">Sales Until Yesterday</th>
                            <th class="col-md-2" style="width: 20%; text-align:center;">Sales Today</th>
                            <th class="col-md-2" style="width: 20%; text-align:center;">Total Sales</th>
                        </tr>
                    </thead>
                    <tbody name="collection">
                        @php
                            $total_sale_untilYesterday = 0;
                            $total_sale_today = 0;
                            $totalSales = 0;
                        @endphp
                        @foreach ($order_reports as $key => $order_report)
                            <tr>
                                <td style="text-align:center;">{{ $key + 1 }}</td>
                                <td>{{ $order_report['code'] }} - {{ $order_report['name'] }}</td>
                                <td style="text-align:right;">Rp. {{ number_format($order_report['total_sale_untilYesterday']) }}</td>
                                <td style="text-align:right;">Rp. {{ number_format($order_report['total_sale_today']) }}</td>
                                <td style="text-align:right;">Rp.
                                    {{ number_format($order_report['total_sale_untilYesterday'] + $order_report['total_sale_today']) }}</td>
                            </tr>
                            @php
                                $total_sale_untilYesterday += $order_report['total_sale_untilYesterday'];
                                $total_sale_today += $order_report['total_sale_today'];
                                // $totalSales += ($order_report['total_sale_untilYesterday'] + $order_report['total_sale_today']);
                            @endphp
                        @endforeach
                        <tr class="text-right">
                            <th colspan="2">TOTAL SALES</th>
                            <th>Rp. {{ number_format($total_sale_untilYesterday) }}</th>
                            <th>Rp. {{ number_format($total_sale_today) }}</th>
                            <th>Rp. {{ number_format($total_sale_untilYesterday + $total_sale_today) }}</th>
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
