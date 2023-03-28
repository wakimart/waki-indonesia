<?php
$menu_item_page = "theraphy_service";
$menu_item_second = "list_therapy_location";
?>
@extends("admin.layouts.template")

@section("style")
<style type="text/css">
    .center {text-align: center;}
    .right {text-align: right;}
</style>
@endsection

@section("content")
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">List Therapy Location</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#"
                            aria-expanded="false">
                            Therapy Location
                        </a>
                    </li>
                    <li class="breadcrumb-item active"
                        aria-current="page">
                        List Therapy Location
                    </li>
                </ol>
            </nav>
        </div>
        <div class="col-12 grid-margin stretch-card" style="padding: 0;">
            <div class="card">
                <div class="card-body">
                    <h5 style="margin-bottom: 0.5em;">
                        Total: <?php echo $countTherapyLocations; ?> data
                    </h5>
                    <div class="table-responsive"
                        style="border: 1px solid #ebedf2;">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Therapy Location</th>
                                    <th class="center">Edit</th>
                                    <th class="center">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($therapyLocations as $index => $value)
                                    <tr>
                                        <td>{{$index + 1}}</td>
                                        <td>{{$value->name}}</td>
                                        <td class="center">
                                            <a href="{{ route('edit_therapy_location', $value->id) }}">
                                                <i class="mdi mdi-border-color mr-3" style="font-size: 24px; color:#fed713;"></i>
                                            </a>
                                        </td>
                                        <td class="center">
                                            <button onclick="submitDelete(`{{ route('delete_therapy_location', $value->id) }}`)" class="btn-delete">
                                                <i class="mdi mdi-delete" style="font-size: 24px; color:#fe7c96;"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach                                
                            </tbody>
                        </table>
                        <br>
                        <?php echo $therapyLocations->appends($url)->links(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Delete -->
<div class="modal fade" id="deleteTherapyLocationModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 style="text-align:center;">Are You Sure to Delete this Therapy Location ?</h5>
            </div>
            <div class="modal-footer">
                <form name="frmDelete" method="post" action="">
                    {{csrf_field()}}
                    @method('DELETE')
                    <button type="submit" class="btn btn-gradient-danger mr-2">Yes</button>
                </form>
                <button class="btn btn-light" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Delete -->
@endsection
@section('script')
    <script>        
        function submitDelete(url) {
            $('#deleteTherapyLocationModal').modal('show');
            document.frmDelete.action = url;
        }
    </script>
@endsection