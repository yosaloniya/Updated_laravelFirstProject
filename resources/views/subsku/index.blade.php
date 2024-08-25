@extends('layout.app')
@section('content')
<?php
    $userRole = Auth::user()->role;
    ?>
<style>
    .select2-container{
        width: 100% !important;
    }
</style>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-dark">Product SKU's</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            @include('common.alert')
            <div class="row">
                <div class="col-1">
                    <a href="{{ url('products') }}" class="btn btn-sm btn-secondary py-2">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i>
                        Back</a>
                </div>
                <div class="col-3 d-flex">
                    <input type="text" id="start-date" class="form-control form-control-sm mr-2" placeholder="Start Date"
                        value="<?php echo $selected['start_date'] ?? ''; ?>">
                    <span class="mt-1 text-dark">-</span>
                    <input type="text" id="end-date" class="form-control form-control-sm ml-2" placeholder="End Date"
                        value="<?php echo $selected['end_date'] ?? ''; ?>">
                </div>
                <div class="col-1">
                    <button class="btn btn-sm btn-secondary apply">
                        Apply
                    </button>
                </div>
                <div class="col-3 text-center">
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6 mainskushow form-control form-control-sm">
                            @foreach ($skus as $sku)
                                {{ $sku->sku }}
                            @endforeach
                        </div>
                        <div class="col-3"></div>
                    </div>

                </div>
                <div class="col-4 text-right">
                    @if ($userRole == 1 || $userRole == 0)
                    <button class="btn btn-sm btn-success py-2" data-toggle="modal" data-target="#importExcelData">
                        <i class="fa fa-arrow-down" aria-hidden="true"></i>
                        Import</button>
                    <button class="btn btn-sm btn-secondary mx-2 py-2" data-toggle="modal"
                        data-target="#ExportDataExcelFile">
                        <i class="fa fa-arrow-up" aria-hidden="true"></i>
                        Export</button>
                    <button href="{{ url('subsku/info/' . $id) }}" class="btn btn-sm btn-primary py-2" data-toggle="modal"
                        data-target="#exampleModal1234">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        Add Sub-SKU</button>
@endif
                </div>

                <!--  Export Excel file modal -->
                <div class="modal fade" id="ExportDataExcelFile" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-dark h4" id="exampleModalLabel">Export Subsku data</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="container">

                                    <form id="exportForm" action="{{ url('subsku/export/excel') }}" method="GET">
                                        @csrf
                                        <div class="form-group">
                                            <label for="supplier">Supplier</label>
                                            <select name="sup_id" class="form-control expselect2 w-100" id="supplier">
                                                <option value=""selected disabled>Select Supplier</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="sku_name">Main SKU</label>
                                            <select name="product_id" class="form-control expselect2 w-100" id="sku_name">
                                                <option value=""selected disabled>Select Main SKU</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->sku }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="start_date">Start Date</label>
                                            <input type="date" name="start_date" class="form-control" id="start_date">
                                        </div>
                                        <div class="form-group">
                                            <label for="end_date">End Date</label>
                                            <input type="date" name="end_date" class="form-control" id="end_date">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Export</button>
                                    </form>
                                </div>
                                {{-- <script>
                                        $(document).ready(function() {
                                            $('#exportForm').on('submit', function(e) {
                                                e.preventDefault();

                                                $.ajax({
                                                    url: '{{ url("subsku/export") }}',
                                                    method: 'GET',
                                                    data: $(this).serialize(),
                                                    success: function(response) {
                                                        // Trigger file download
                                                        window.location.href = response.file;
                                                    },
                                                    error: function(response) {
                                                        alert('Error exporting data');
                                                    }
                                                });
                                            });
                                        });
                                    </script> --}}



                            </div>
                        </div>
                    </div>
                </div>


                <!--  Import Excel file modal -->
                <div class="modal fade" id="importExcelData" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-dark h4" id="exampleModalLabel">Import Data with Excel file
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="container">
                                            <form action="{{ url('subsku/import') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="input-group">
                                                    <input type="file" name="import_file" class="form-control">
                                                    <button class="btn btn-sm btn-primary" type="submit">Import</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--  Add Existing Product Modal -->
                <div class="modal fade" id="exampleModal1234" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-dark h4" id="exampleModalLabel">Add More Products</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <div class="row">
                                    <div class="col-sm-6">
                                        <a href="{{ url('subsku/info/' . $id) }}" class="btn btn-sm btn-warning">
                                            <i class="fa fa-plus" aria-hidden="true"></i>Add New SKU</a>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <a href="{{ url('supplierproducts/info') }}" class="btn btn-sm btn-warning">
                                            <i class="fa fa-plus" aria-hidden="true"></i>Add Existing SKU</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0" data-page-length='50'>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Sub-SKU</th>
                            <th>Supplier</th>
                            <th>Size(mtr.)</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Sub-SKU</th>
                            <th>Supplier</th>
                            <th>Size(mtr.)</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Description</th>
                            @if ($userRole == 1 || $userRole == 0)
                            <th>Action</th>
                            @endif
                        </tr>
                    </tfoot>
                    <tbody>
                        @inject('alldata', 'App\Http\Controllers\MasterController')
                        <?php $i = 1; ?>
                        @foreach ($data as $subsku)
                            <tr id="tr_{{$subsku->id}}">
                                <td><?php echo $i++; ?></td>
                                <td><?php echo date('d-m-Y', strtotime($subsku->date)); ?></td>
                                <td><?php echo $subsku['sku']; ?></td>
                                <td><?php echo $alldata::getsupplierName($subsku['sup_id']); ?></td>
                                <td><?php echo $subsku['qty']; ?></td>
                                <td><?php echo $subsku['location']; ?></td>
                                <td>
                                    @if ($subsku->status == 1)
                                        <a href="@if ($userRole == 1 || $userRole == 0) {{ url('subsku/status/' . $subsku->id) }} @endif"
                                            class="btn btn-sm btn-success alertmsg">Active</a>
                                    @else
                                        <a href="@if ($userRole == 1 || $userRole == 0) {{ url('subsku/status/' . $subsku->id) }} @endif"
                                            class="btn btn-sm btn-secondary alertmsg">Inactive</a>
                                    @endif
                                </td>
                                <td><?php echo $subsku['description']; ?></td>
                                @if ($userRole == 1 || $userRole == 0)
                                <td>
                                    <a href="{{ url('subsku/edit/' . $subsku->id) }}" class="btn btn-sm btn-info"><i
                                            class="fa fa-pencil-square" aria-hidden="true"></i></a>
                                    <button data-id="{{ url('subsku/delete/' . $subsku->id) }}"
                                        class="btn btn-sm btn-danger brand_delete_btn"><i class="fa fa-trash"
                                            aria-hidden="true"></i></button>
                                </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <input type="hidden" id="product_id" value="<?php echo $selected['product_id']; ?>">
        </div>
    </div>
    @include('common.script_alert')
@endsection
@section('script')
<!--    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
<!--<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->
    <script>
        $(document).ready(function() {
            
               $('.expselect2').select2({
        dropdownParent: $('#ExportDataExcelFile')
    });

            $(function() {
                $("#start-date, #end-date").datepicker({
                    dateFormat: "dd/mm/yy"
                });
            });

            $(document).on('click', '.apply', function() {
                let startDate = $('#start-date').val();
                let endDate = $('#end-date').val();
                let product_id = $('#product_id').val();
                let url = "/subsku/" + product_id + "/?";
                let validStartDate = validDate(startDate);
                let validEndDate = validDate(endDate);
                if (!validStartDate && validEndDate) {
                    url += 'start_date=' + startDate;
                }
                if (validStartDate && !validEndDate) {
                    url += 'end_date=' + endDate;
                }
                if (!validStartDate && !validEndDate) {
                    url += 'start_date=' + startDate + '&end_date=' + endDate;
                }
                location.href = url;
            });

            function validDate(date) {
                if (date == '') {
                    return true;
                }
                let dateArr = date.split('/');
                if (dateArr.length != 3) {
                    return true;
                } else {
                    return false;
                }
            }


            // $.ajax({
            //     type: "post",
            //     url: "skuname",
            //     data: "data",
            //     dataType: "dataType",
            //     success: function (response) {

            //     }
            // });
        });
    </script>
@endsection
