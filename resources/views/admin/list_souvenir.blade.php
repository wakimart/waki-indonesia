<?php
$menu_item_page = "souvenir";
$menu_item_second = "list_souvenir";
?>
@extends("admin.layouts.template")

@section("style")
<style type="text/css">
    .center {
        text-align: center;
    }

    .right {
        text-align: right;
    }
</style>
@endsection

@section("content")
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">List Souvenir</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#"
                            aria-expanded="false">
                            Souvenir
                        </a>
                    </li>
                    <li class="breadcrumb-item active"
                        aria-current="page">
                        List Souvenir
                    </li>
                </ol>
            </nav>
        </div>
        <div class="col-12 grid-margin stretch-card" style="padding: 0;">
            <div class="card">
                <div class="card-body">
                    <h5 style="margin-bottom: 0.5em;">
                        Total: <?php echo $countSouvenir; ?> data
                    </h5>
                    <div class="table-responsive"
                        style="border: 1px solid #ebedf2;">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Souvenir</th>
                                    <th class="center">Edit</th>
                                    <th class="center">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($souvenir as $value): ?>
                                    <tr>
                                        <td>
                                            <?php echo $i; ?>
                                        </td>
                                        <td>
                                            <?php echo $value->name; ?>
                                        </td>
                                        <td class="center">
                                            <a href="<?php echo route("edit_souvenir", ["id" => $value->id]); ?>">
                                                <i class="mdi mdi-border-color" style="font-size: 24px; color: #fed713;"></i>
                                            </a>
                                        </td>
                                        <td class="center">
                                            <a class="btn-delete"
                                                data-toggle="modal"
                                                href="#deleteDoModal"
                                                onclick="submitDelete(this)"
                                                data-id="<?php echo $value->id; ?>">
                                                <i class="mdi mdi-delete" style="font-size: 24px; color: #fe7c96;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php $i++; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <br>
                        <?php echo $souvenir->appends($url)->links(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade"
    id="deleteDoModal"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 style="text-align:center;">
                    Are you sure you want to delete this?
                </h5>
            </div>
            <div class="modal-footer">
                <form id="frmDelete"
                    method="POST"
                    action="<?php echo route("delete_souvenir"); ?>">
                    @csrf
                    <input type="hidden" name="id" id="id-delete" />
                    <button type="submit" class="btn btn-gradient-danger mr-2" onclick="submitDelete()">
                        Yes
                    </button>
                </form>
                <button class="btn btn-light">No</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
function submitDelete(e) {
    document.getElementById("id-delete").value = e.dataset.id;
}
</script>
@endsection
