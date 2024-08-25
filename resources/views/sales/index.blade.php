@extends('layout.app')
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
    <?php
    $userRole = Auth::user()->role;
    ?>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-dark">Orders</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @include('common.alert')

            <div class="row">
                <div class="col-1">
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
                        <a href="{{ url('orders/info') }}" class="btn btn-sm py-2 btn-primary">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                            New Order</a>
                    @endif
                </div>

            </div>

            <!--  Import Excel file modal -->
            <div class="modal fade" id="importExcelData" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-dark h4" id="exampleModalLabel">Import Data with Excel file</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="container">
                                        <form action="{{ url('orders/import') }}" method="POST"
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

            <!--  Export Excel file modal -->
            <div class="modal fade" id="ExportDataExcelFile" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-dark h4" id="exampleModalLabel">Export Sales data</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container">

                                <form id="exportForm" action="{{ url('orders/export') }}" method="GET">
                                    @csrf
                                    <div class="form-group">
                                        <label for="customer">Customer</label>
                                        <select name="customer_id" class="form-control expselect2 " id="customer">
                                            <option value=""selected disabled>Select Customer</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
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
                            <th>Order No.</th>
                            <th>Customer</th>
                            <th>Order details</th>
                            <th>Description</th>
                            @if ($userRole == 1 || $userRole == 0)
                                <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Order No.</th>
                            <th>Customer</th>
                            <th>Order details</th>
                            <th>Description</th>
                            @if ($userRole == 1 || $userRole == 0)
                                <th>Action</th>
                            @endif
                        </tr>
                    </tfoot>

                    <tbody>
                        @inject('alldata', 'App\Http\Controllers\MasterController')
                        <?php $i = 1; ?>

                        @foreach ($data as $sales)
                            <tr id="tr_{{$sales->id}}">
                                <td><?php echo $i++; ?></td>
                                <td><?php echo date('d-m-Y', strtotime($sales->date));?></td>
                                <td><?php echo $sales['order_no']; ?></td>
                                <td><?php echo $alldata::getcustomername($sales['customer_id']); ?></td>
                                <td>
                                    <p>

                                        <button class="btn btn-light button-click" type="button" style="height: 40px;">
                                            <span class="d-flex">
                                                <p class="click-here"></p> <i class="fa fa-angle-down ml-3"></i>
                                            </span>
                                        </button>
                                    </p>
                                    <div class="hello" style="display: none;">
                                        <table class="table table-bordered text-center" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Main-SKU</th>
                                                    <th>Sub-SKU</th>
                                                    <th>Size</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $TotalQty = 0; ?>
                                                @foreach ($sales->product_id as $orderdata)
                                                    <tr>
                                                        <td><?php echo $alldata::getproductnamemainsku($orderdata['sku']); ?></td>
                                                        <td><?php echo $alldata::getproductnamesubsku($orderdata['p_id']); ?></td>
                                                        <td><?php echo $orderdata['qty']; ?></td>
                                                    </tr>
                                                    <?php $TotalQty += $orderdata['qty']; ?>
                                                @endforeach
                                                <input class="qty-sku" type="number" hidden
                                                    value="<?php echo $TotalQty; ?>">
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                                <td><?php echo $sales['description']??''; ?> </td>
                                @if ($userRole == 1 || $userRole == 0)
                                    <td class="d-flex justify-content-center">
                                        <a href="{{ url('orders/edit/' . $sales->id) }}"
                                            class="btn m-1 btn-sm btn-info"><i class="fa fa-pencil-square"
                                                aria-hidden="true"></i></a>
                                        <button data-id="{{ url('orders/delete/' . $sales->id) }}"
                                            class="btn m-1 btn-sm btn-danger brand_delete_btn"><i class="fa fa-trash"
                                                aria-hidden="true"></i></button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>-->
@section('script')
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
                let url = "/orders?";
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
        })
        document.addEventListener("DOMContentLoaded", function() {
            const totalQtyInputs = document.querySelectorAll(".qty-sku");
            const totalQty = document.querySelectorAll(".click-here");

            let index = 0;
            totalQtyInputs.forEach(input => {
                totalQty[index].innerText = input.value;
                index++;
            });
        });

        $(document).on('click', '.button-click', function() {
            $('.hello').css('display','none');
            if($(this).parent().siblings().hasClass('d_act')) {
                $(this).parent().siblings().css('display', 'none');
                $(this).parent().siblings().removeClass('d_act');
            } else {
                $(this).parent().siblings().css('display', 'block');
                $(this).parent().siblings().addClass('d_act');
            }
        });
        </script>
@endsection
