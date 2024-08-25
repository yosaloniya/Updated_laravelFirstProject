@extends('layout.app')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }
        .productImage {
            cursor: pointer;
            width: 50px;
            transition: width 0.3s ease;
            display: block;
            margin: auto;
        }
    </style>
    <?php
    $userRole = Auth::user()->role;
    ?>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-dark">Products</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            @include('common.alert')
            <div class="row">
                <div class="col-1">
                    <button class="btn btn-sm btn-secondary py-2" onclick="history.back()">
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
                <div class="col-2 text-center">
                    <button class="btn btn-sm btn-danger stockbtn" data-toggle="modal" data-target="#exampleModal12345">
                        Low Stock <br><i class="low-stock-input-tag"></i></button>
                </div>
                <div class="col-1"></div>
                <div class="col-4 text-right">
                    @if ($userRole == 1 || $userRole == 0)
                        <button class="btn btn-sm btn-success py-2" data-toggle="modal" data-target="#importExcelData">
                            <i class="fa fa-arrow-down" aria-hidden="true"></i>
                            Import</button>
                        <button class="btn btn-sm btn-secondary mx-2 py-2" data-toggle="modal"
                            data-target="#ExportDataExcelFile">
                            <i class="fa fa-arrow-up" aria-hidden="true"></i>
                            Export</button>
                        <button class="btn btn-sm btn-primary py-2" data-toggle="modal" data-target="#exampleModal123">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                            Add Product</button>
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
                                            <form action="{{ url('products/import') }}" method="POST"
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
                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                        <script>
                                            // Timeout alert for error message
                                            setTimeout(function() {
                                                $('.alert-success').fadeOut('slow');
                                            }, {{ session('timeout', 2000) }});
                                        </script>
                                    @endif
                                    @if (session('error'))
                                        {{-- <div class="alert alert-danger">{{ session('error') }}</div> --}}
                                        <script>
                                            // Timeout alert for error message
                                            setTimeout(function() {
                                                $('.alert-danger').fadeOut('slow');
                                            }, {{ session('timeout', 150000) }});
                                        </script>
                                    @endif
                                    <form action="{{ url('products/export') }}" method="GET">
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
                                            <input type="date" name="start_date" class="form-control"
                                                id="start_date">
                                        </div>
                                        <div class="form-group">
                                            <label for="end_date">End Date</label>
                                            <input type="date" name="end_date" class="form-control" id="end_date">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Export</button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!--  Add Existing Product Modal -->
                <div class="modal fade" id="exampleModal123" tabindex="-1" role="dialog"
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
                                        <a href="{{ url('products/info') }}" class="btn py-2 btn-warning">
                                            <i class="fa fa-plus" aria-hidden="true"></i>Add New Product</a>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <a href="{{ url('supplierproducts/info') }}" class="btn py-2 btn-warning">
                                            <i class="fa fa-plus" aria-hidden="true"></i>Add Existing Product</a>
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
                            <th>Brand</th>
                            <th>Category</th>
                            <th>Product Name</th>
                            <th>Main-SKU</th>
                            <th>Image</th>
                            <th>Supplier Name</th>
                            <th>Description</th>
                            <th>Total-Size(mtr)</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Brand</th>
                            <th>Category</th>
                            <th>Product Name</th>
                            <th>Main-SKU</th>
                            <th>Image</th>
                            <th>Supplier Name</th>
                            <th>Description</th>
                            <th>Total-Size(mtr)</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>

                    <tbody>
                        @inject('alldata', 'App\Http\Controllers\MasterController')
                        <?php $i = 1;
                        $lowStockArr = []; ?>
                        @foreach ($data as $product)
                            <tr id="tr_{{$product->id}}">
                                <td><?php echo $i++; ?></td>
                                <td><?php echo date('d-m-Y', strtotime($product->date)); ?></td>
                                <td><?php echo $alldata::getbrandname1($product['category_id']); ?></td>
                                <td><?php echo $alldata::getcategoryname($product['category_id']); ?></td>
                                <td><?php echo $product['name']; ?></td>
                                <td><span type="button" class="btn btn-sm btn-light skubtn text-danger"
                                        id="{{ $product->id }}">
                                        <?php echo $product['sku']; ?></span>
                                </td>
                                <td>
                                    <img class="productImage" src="<?php echo 'uploads/' . $product['image']??''; ?>" alt="">
                                    
                                </td>
                                <td><?php echo $alldata::getsupplierName($product['sup_id']); ?></td>
                                <td><?php echo $product['description']; ?></td>
                                <td><?php
                                
                                $sub_sku = $alldata::getsubskuqty($product['id']);
                                echo $sub_sku + $product['qty'];
                                if ($sub_sku + $product['qty'] < 25 && $product->status == 1) {
                                    $lowStockArr[$product['sku']] = [$sub_sku + $product['qty'], $product->id,$product['image']];
                                }
                                ?>
                                </td>
                                <td>
                                    @if ($product->status == 1)
                                        <a href="@if ($userRole == 1 || $userRole == 0) {{ url('products/status/' . $product->id) }} @endif"
                                            class="btn btn-sm btn-success">Active</a>
                                    @else
                                        <a href="@if ($userRole == 1 || $userRole == 0) {{ url('products/status/' . $product->id) }} @endif"
                                            class="btn btn-sm btn-secondary">Inactive</a>
                                    @endif
                                </td>
                                <td class="d-flex justify-content-center">
                                    <a href="{{ url('subsku/' . $product->id) }}" class="btn m-1 btn-sm btn-warning"><i
                                            class="fa fa-bars" aria-hidden="true"></i></a>
                                    @if ($userRole == 1 || $userRole == 0)
                                        <a href="{{ url('products/edit/' . $product->id) }}"
                                            class="btn m-1 btn-sm btn-info"><i class="fa fa-pencil-square"
                                                aria-hidden="true"></i></a>
                                        <button data-id="{{ url('products/delete/' . $product->id) }}"
                                            class="btn m-1 btn-sm btn-danger brand_delete_btn"><i class="fa fa-trash"
                                                aria-hidden="true"></i></button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--Subsku details Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark h4" id="exampleModalLabel">Product Subsku's</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table text-center"  id="subsku_dataTableModal" width="100%" cellspacing="0" data-page-length='25'>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Main-SKU</th>
                                    <th>Sub-SKU</th>
                                    <th>Location</th>
                                    <th>Size(mtr.)</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody class="alldata">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Low Stock Product Modal-->
    <div class="modal fade" id="exampleModal12345" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark h4" id="exampleModalLabel">Low stock Products</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table text-center"  id="lowstock_dataTableModal" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Main-SKU</th>
                                    <th>Image</th>
                                    <th>Size(mtr.)</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody class="lowstock_data">
                                <?php 
                                      $i=1;
                                    foreach ($lowStockArr as $sku => $qty) {
                                        ?>

                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $sku }}</td>
                                    <td><img class="productImage" src="{{'uploads/' . $qty[2]}}"></td>
                                    <td>{{ $qty[0] }}</td>
                                    <td>
                                        @if ($product->status == 1)
                                        
                                        
                                            <a href="{{ url('products/status/' . $qty[1]) }}"
                                                class="btn btn-sm btn-success">Active</a>
                                        @else
                                            <a href="{{ url('products/status/' . $qty[1]) }}"
                                                class="btn btn-sm btn-secondary">Inactive</a>
                                        @endif
                                    </td>
                                </tr>

                                <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <input class="low-stock-input" type="number" hidden value="<?php echo count($lowStockArr); ?>">
        </div>
    </div>
    @include('common.script_alert')
@endsection

@section('script')
    <script>
    
    
    
   $(document).ready(function() {
        // Initialize Select2 when the modal is shown
        $('#exampleModal12345').find('.modal-dialog').addClass('modal-lg');
          $('.expselect2').select2({
        dropdownParent: $('#ExportDataExcelFile')
    });
    
    // Initialize DataTable
    $('#lowstock_dataTableModal').DataTable({
        // DataTable options
    });

    // Reinitialize DataTable when the modal is shown
    $('#exampleModal12345').on('shown.bs.modal', function () {
        // Ensure DataTable is reinitialized if needed
        $('#lowstock_dataTableModal').DataTable().draw();
    });
    });
    
       document.addEventListener('DOMContentLoaded', function() {
            // Select all image elements with the class 'productImage'
            const productImages = document.querySelectorAll('.productImage');

            // Add an event listener to each image element
            productImages.forEach(function(image) {
                image.addEventListener('click', function() {
                    // Toggle the width of the clicked image between 50px and 500px
                    if (this.style.width === '50px') {
                        this.style.width = '500px';
                    } else {
                        this.style.width = '50px';
                    }
                });
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

            let url = "/products?";
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

      $('.skubtn').click(function() {
    let id = Number($(this).attr('id'));
    let url = "{{ url('sku/data') }}";
    let mainText = $(this).text();
    $.ajax({
        url: url,
        method: 'POST',
        data: {
            id: id,
        },
        success: function(response) {
            $('#exampleModal').modal('show');
            $('#exampleModal').find('.modal-dialog').addClass('modal-xl');
            $('#subsku_dataTableModal').DataTable().destroy(); // Destroy existing instance

            let arr = [];
            response.forEach((val, index) => {
                // Format date to dd-mm-yyyy
                let date = new Date(val.date);
                let formattedDate = `${date.getDate().toString().padStart(2, '0')}-${(date.getMonth() + 1).toString().padStart(2, '0')}-${date.getFullYear()}`;

                let statusButton;
                if (val.status == 1) {
                    statusButton = '<a href="{{ url('subsku/status/') }}/' + val.id + '" class="btn btn-sm btn-success alertmsg">Active</a>';
                } else {
                    statusButton = '<a href="{{ url('subsku/status/') }}/' + val.id + '" class="btn btn-sm btn-secondary alertmsg">Inactive</a>';
                }

                let str = `<tr><td>${index + 1}</td><td>${formattedDate}</td><td>${mainText}</td><td>${val.sku}</td><td>${val.location}</td><td>${val.qty}</td><td>${statusButton}</td></tr>`;
                arr.push(str);
            });

            $('.alldata').html(arr);
            
            // Initialize or reinitialize DataTable
            $('#subsku_dataTableModal').DataTable({
                // DataTable options
                paging: true,
                searching: true,
                ordering: true
            });
        }
    });
});


        $(document).ready(function() {
            let low = $('.low-stock-input').val();
            $('.low-stock-input-tag').text(low + ' Products');
        });
    </script>
@endsection
