<?php
    $menu_item_page = "registerevent";
    $menu_item_second = "list_regispromo";
?>
@extends('admin.layouts.template')

@section('style')
<style>
  .center {
    text-align: center;
  }
</style>
@endsection

@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">List Home WAKi Di Rumah Aja</h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
              <li class="breadcrumb-item"><a data-toggle="collapse" href="#order-dd" aria-expanded="false" aria-controls="order-dd">Registration</a></li>
              <li class="breadcrumb-item active" aria-current="page">List Home WAKi Di Rumah Aja</li>
          </ol>
        </nav>
    </div>

    <div class="row">
      <div class="col-12 grid-margin stretch-card">
        @if(Auth::user()->roles[0]['slug'] != 'branch' && Auth::user()->roles[0]['slug'] != 'cso')
        <div class="col-xs-6 col-sm-3" style="padding: 0;display: inline-block;">
          <div class="form-group">
              <input class="form-control" id="search" name="search" placeholder="Search By Name or Phone or Email">
            <div class="validation"></div>
          </div>
        </div>
        @endif

        @if(Auth::user()->roles[0]['slug'] != 'branch' && Auth::user()->roles[0]['slug'] != 'cso' && Auth::user()->roles[0]['slug'] != 'area-manager')
          <div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
          <div class="col-xs-6 col-sm-6" style="padding: 0;display: inline-block;">
            <div class="form-group">
            <button id="btn-filter" type="button" class="btn btn-gradient-primary m-1" name="filter" value="-"><span class="mdi mdi-filter"></span> Apply Filter</button>
            </div>
          </div>
          </div>
        @endif

        <div class="col-sm-12 col-md-12" style="padding: 0; border: 1px solid #ebedf2;">
          <div class="col-xs-12 col-sm-11 col-md-6 table-responsive" id="calendarContainer" style="padding: 0; float: left;"></div>
          <div class="col-xs-12 col-sm-11 col-md-6" id="organizerContainer" style="padding: 0; float: left;"></div>
        </div>

      </div>
        <div class="col-12 grid-margin stretch-card">
          <div class="card">
              <div class="card-body">
                <h5 style="margin-bottom: 0.5em;">Total : {{ $countPromotions }} data</h5>
                <div class="table-responsive" style="border: 1px solid #ebedf2;">
                  <table class="table table-bordered">
                      <thead>
                        <tr>
                            <th> No. </th>
                            <th> Name </th>
                            <th> Address </th>
                            <th> Email </th>
                            <th> Phone </th>
                            <th class="center"> Detail/Edit/Delete </th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($promotions as $key => $promotion)
                        <tr>
                          <td>{{$key + 1}}</td>
                          <td>{{$promotion['first_name']}} {{$promotion['last_name']}}</td>
                          <td>{{$promotion['address']}}</td>
                          <td>{{$promotion['email']}}</td>
                          <td>{{$promotion['phone']}}</td>
                          <td class="center">
									          @can('edit-deliveryorder')  
                            <a href="{{ route('detail_regispromo', $promotion['id']) }}" target="_blank">
                                <i class="mdi mdi-eye mr-3" style="font-size: 24px; color:#636e72;"></i>
                            </a>
                            @endcan
									          @can('edit-deliveryorder')
                            <a href="{{ route('edit_regispromo', $promotion['id']) }}">
                                <i class="mdi mdi-border-color mr-3" style="font-size: 24px; color:#fed713;"></i>
                            </a>
                            @endcan
									          @can('delete-deliveryorder')
                            <a class="btn-delete" href="javascript:void(0)" onclick="submitDelete(`{{ route('delete_regispromo', $promotion['id']) }}`)">
                                <i class="mdi mdi-delete" style="font-size: 24px; color: #fe7c96;"></i>
                            </a>
                            @endcan
                          </td>                                            
                        </tr>
                        @endforeach
                      </tbody>
                  </table>
                  <br/>
                  {{ $promotions->links()}}
                </div>
              </div>
          </div>
        </div>
    </div>
  </div>
<!-- partial -->
  <!-- Modal Delete -->
  <div class="modal fade" id="deleteDoModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <h5 style="text-align:center;">Are You Sure to Delete This Data ?</h5>
              </div>
              <div class="modal-footer">
                <form name="frmDelete"
                    method="post"
                    action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="btn btn-gradient-danger mr-2">
                        Yes
                    </button>
                </form>
                <button type="button" data-dismiss="modal" class="btn btn-light">No</button>
              </div>
            </div>
        </div>
    </div>
    <!-- End Modal Delete -->
</div>
@endsection

@section('script')
<script>
  function submitDelete(url) {
      $('#deleteDoModal').modal('show');
      document.frmDelete.action = url;
  }

  $(document).on("click", "#btn-filter", function(e){
    var urlParamArray = new Array();
    var urlParamStr = "";
    if($('#search').val() != ""){
    urlParamArray.push("search=" + $('#search').val());
    }
    for (var i = 0; i < urlParamArray.length; i++) {
    if (i === 0) {
      urlParamStr += "?" + urlParamArray[i]
    } else {
      urlParamStr += "&" + urlParamArray[i]
    }
    }

    window.location.href = "{{route('list_regispromo')}}" + urlParamStr;
  });
</script>
@endsection
