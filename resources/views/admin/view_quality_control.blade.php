<?php
    $menu_item_page = "order";
?>
@extends('admin.layouts.template')

@section('style')
    <link href="{{asset('css/admin/filepond/filepond.min.css')}}" rel="stylesheet" />
    <link href="{{asset('css/admin/filepond/filepond-plugin-image-preview.min.css')}}" rel="stylesheet" />
    <style type="text/css">
        
    </style>
@endsection

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <h3 class="text-center">Quality Control</h3>
            <form action="{{route('save_quality_control')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="total" value="{{count($stockInOut->stockInOutProduct)}}">
                @php
                    // Have all the products been checked?
                    $check = true;
                @endphp
                @foreach($stockInOut->stockInOutProduct as $index => $siop)
                    <div class="card mt-4 form-group">
                        <h5 class="card-header">{{$siop->product->code}} - {{$siop->product->name}}</h5>
                        <div class="card-body">
                            @if(is_null($siop->quality_control_id))
                                @php $check = false; @endphp
                                <input type="hidden" name="siop_id_{{$index}}" value="{{$siop->id}}">
                                <label class="card-title" for="serialNumber{{$index}}">Serial Number</label>
                                <input class="form-control set-required" type="text" name="serial_number_{{$index}}" id="serialNumber-{{$index}}">

                                <label class="card-title mt-3" for="evidence{{$index}}">Evidence</label>
                                <input type="file" class="filepond set-required" multiple name="evidence_{{$index}}[]" id="evidence-{{$index}}" data-allow-reorder="true" data-max-files="5"/>
                                <br>

                                <label class="card-title mt-3" for="description{{$index}}">Description</label>
                                <textarea name="description_{{$index}}" class="form-control set-required" rows=4 id="description-{{$index}}"></textarea>
                            @else
                                <label class="card-title">Serial Number</label>
                                <input class="form-control" type="text" value="{{$siop->qualityControl->serial_number}}" disabled>

                                <label class="card-title mt-3">Evidence</label>
                                <div>
                                    @php
                                        $files = json_decode($siop->qualityControl->evidence, true);
                                    @endphp
                                    @foreach($files as $file)
                                        @if(substr($file, -3) == 'mp4')
                                            <video controls="controls" autoplay style="width: 100%; height: auto; margin: 1em 0">
                                                <source src="{{$file}}" type="video/mp4"/>
                                            </video>
                                        @else
                                            <img src="{{$file}}" alt="quality-control-image-of-{{$siop->product->code}}" style="width: 100%; height: auto; margin: 1em 0"> 
                                        @endif
                                    @endforeach
                                </div>

                                <label class="card-title mt-3">Description</label>
                                <textarea class="form-control" rows=4 disabled>{{$siop->qualityControl->description}}</textarea>
                            @endif
                        </div>
                    </div>

                @endforeach
                @if(!$check)
                    <button type="submit" class="btn btn-gradient-success" style="margin:3em auto; display:block">Submit</button>
                @endif
            </form>
        </div>
    </div>
@endsection

@section('script')
    <!-- filepond -->
    <script src="{{asset('js/admin/filepond/filepond.min.js')}}"></script>
    <script src="{{asset('js/admin/filepond/filepond-plugin-image-preview.min.js')}}"></script>
    <script src="{{asset('js/admin/filepond/filepond-plugin-file-validate-size.min.js')}}"></script>
    <script src="{{asset('js/admin/filepond/filepond-plugin-file-validate-type.min.js')}}"></script>
    <script src="{{asset('js/admin/filepond/filepond-plugin-file-encode.min.js')}}"></script>

    <script>
        // Register the plugin
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateSize,
            FilePondPluginFileValidateType,
            FilePondPluginFileEncode
        );
        // Get a reference to the file input element
        const inputElements = document.querySelectorAll('input.filepond');
        // loop over input elements
        Array.from(inputElements).forEach(inputElement => {            
            // create a FilePond instance at the input element location
            FilePond.create(inputElement, {
                labelIdle: 'Upload here <br> max 5 files @5MB',
                maxFileSize: '5MB',
                acceptedFileTypes: ['image/*', 'video/quicktime', 'video/mp4']
            });
        })

        // set required
        $('.set-required').on("input change", function(){
            var index = parseInt($(this).attr('id').split('-')[1])
            $("#serialNumber-"+index).prop('required',true);
            FilePond.find($('#evidence-'+index)[0]).required = true;
            $("#description-"+index).prop('required',true);
        });
    </script>
@endsection