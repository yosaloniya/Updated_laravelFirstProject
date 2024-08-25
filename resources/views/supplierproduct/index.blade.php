@extends('layout.app')
@section('content')
<?php
    $userRole = Auth::user()->role;
    ?>
<style>
    .select2-container {
            width: 100% !important;
            text-align: left;
        }
</style>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-dark">Supplier Products</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            @include('common.alert')
            @if (session('errors'))
    <div class="alert alert-danger">
        <ul>
            @foreach (session('errors') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
            <div class="row">
                <div class="col-1 ">
                    <button class="btn btn-sm py-2 btn-secondary" onclick="history.back()">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i>
                        Back</button>

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
      <div class="col-3"></div>
                <div class="col-4 text-right">

                    @if ($userRole == 1 || $userRole == 0)
                    <button class="btn btn-sm btn-success py-2" data-toggle="modal" data-target="#importExcelData">
                        <i class="fa fa-arrow-down" aria-hidden="true"></i>
                        Import</button>
                    <button class="btn btn-sm btn-secondary mx-2 py-2" data-toggle="modal"
                        data-target="#ExportDataExcelFile">
                        <i class="fa fa-arrow-up" aria-hidden="true"></i>
                        Export</button>
                        <!-- Button trigger modal -->
                        <a href="{{ url('supplierproducts/info') }}" class="btn btn-sm py-2 btn-primary">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                            Add Existing Products</a>
                    @endif
                </div>

 <!--  Import Excel file modal -->
 <div class="modal fade" id="importExcelData" tabindex="-1" role="dialog"
 aria-labelledby="exampleModalLabel" aria-hidden="true">
 <div class="modal-dialog" role="document">
     <div class="modal-content">
         <div class="modal-header">
             <h5 class="modal-title text-dark h4" id="exampleModalLabel">Import Products </h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
             </button>
         </div>
         <div class="modal-body">

             <div class="row">
                 <div class="col-sm-12">
                     <div class="container">
                         <form action="{{ url('supplierproducts/import') }}" method="POST"
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



<!--  Export products data modal -->
<div class="modal fade" id="ExportDataExcelFile" tabindex="-1" role="dialog"
 aria-labelledby="exampleModalLabel" aria-hidden="true">
 <div class="modal-dialog" role="document">
     <div class="modal-content">
         <div class="modal-header">
             <h5 class="modal-title text-dark h4" id="exampleModalLabel">Export Products</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
             </button>
         </div>
         <div class="modal-body">
             <div class="container">
                 <form action="{{ url('supplierproducts/export') }}" method="GET">
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
                         <label for="start_date">Start Date</label>
                         <input type="date" name="start_date" class="form-control" id="startdate">
                     </div>
                     <div class="form-group">
                         <label for="end_date">End Date</label>
                         <input type="date" name="end_date" class="form-control" id="enddate">
                     </div>
                     <button type="submit" class="btn btn-primary">Export</button>
                 </form>
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
                                <th>Main-SKU</th>
                                <th>Sub-SKU</th>
                                <th>Supplier Name</th>
                                <th>Size</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Main-SKU</th>
                                <th>Sub-SKU</th>
                                <th>Supplier Name</th>
                                <th>Size</th>
                                <th>Description</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @inject('alldata', 'App\Http\Controllers\MasterController')
                            <?php $i = 1; ?>
                            @foreach ($data as $spr)
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo date('d-m-Y', strtotime($spr->date)); ?></td>
                                    <td><?php echo $spr['sku']; ?></td>
                                    <td><?php echo $spr['sub_sku']; ?></td>
                                    <td><?php echo $alldata::getsupplierName($spr['sup_id']); ?></td>
                                    <td><?php echo $spr['qty']; ?></td>
                                    <td><?php echo $spr['description']; ?></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @include('common.script_alert')
    @endsection
    @section('script')

    <!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
    <!--        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->
    
        <script>
            
                $(document).ready(function() {
                   // Initialize Select2 when the modal is shown
                   $('.expselect2').select2({
                   dropdownParent: $('#ExportDataExcelFile')
                   });
                });
            
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
    
                $(function() {
                    $("#start-date, #end-date").datepicker({
                        dateFormat: "dd/mm/yy"
                    });
                });
    
                $(document).on('click', '.apply', function() {
                    let startDate = $('#start-date').val();
                    let endDate = $('#end-date').val();
    
                    let url = "/supplierproducts?";
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
                </script>
    @endsection
