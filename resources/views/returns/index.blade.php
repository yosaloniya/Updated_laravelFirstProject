@extends('layout.app')
@section('content')
<!--<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />-->

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
    <h1 class="h3 mb-2 text-dark">Returns</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            @include('common.alert')
             @if(Session::has('errors'))
            <div class="alert alert-danger">
                <ul>
                    @foreach(Session::get('errors') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
            <div class="row">
                <div class="col-1 ">
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
                        <button type="button" class="btn btn-sm btn-primary py-2" data-toggle="modal"
                            data-target="#exampleModal">
                            <i class="fa fa-plus" aria-hidden="true"></i> New Return
                        </button>
                    @endif
                </div>

                <!--  Import Excel file modal -->
                <div class="modal fade" id="importExcelData" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                            <form action="{{ url('/returns/import') }}" method="POST"
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
                                <h5 class="modal-title text-dark h4" id="exampleModalLabel">Export Returns data</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="container">

                                    <form id="exportForm" action="{{ url('returns/export') }}" method="GET">
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

                <!--  Add New Return Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-dark h4" id="exampleModalLabel">Manage Returns details</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="message" id="message"></div>
                            <div class="modal-body">

                                <form action="{{ url('returns/save') }}" class="forms-sample create-user" method="POST"
                                    enctype="multipart/form-data" id="createReturnForm">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <div class="text-left">
                                                <label class="text-warning" for="mainSku">Main-SKU
                                                    :</label>
                                            </div>
                                            <select class="form-control select2 main-sku" name="m_sku" id="mainSku">
                                                <option value="">Select Main-SKU</option>
                                                @foreach ($sku as $msku)
                                                    <option value="{{ $msku->id }}">{{ $msku->sku }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <div class="text-left">
                                                <label class="text-warning" for="subSku">Sub-sku
                                                    :</label>
                                            </div>
                                            <input type="text" class="form-control s_sku" name="s_sku"
                                                placeholder="Enter diffrent Sub-SKU" id="subSku">
                                            <input type="hidden" value="return" class="form-control" name="sup_id"
                                                placeholder="Enter diffrent Sub-SKU">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <div class="text-left">
                                                <label class="text-warning" for="size">Size
                                                    :</label>
                                            </div>
                                            <input type="number" class="form-control size" name="size"
                                                placeholder="Enter Size" id="size">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <div class="text-left">
                                                <label class="text-warning" for="customerName">Customer Name
                                                    :</label>
                                            </div>
                                            <select class="form-control select2 customer_id" name="customer_id"
                                                id="customerName">
                                                <option value="" selected disabled>Choose Customer</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <div class="text-left">
                                                <label class="text-warning" for="date">Date
                                                    :</label>
                                            </div>
                                            <input type="date" class="form-control date" name="date"
                                                placeholder="Enter Date" id="date">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <div class="text-left">
                                                <label class="text-warning" for="location">Location
                                                    :</label>
                                            </div>
                                            <input type="text" class="form-control location" name="location"
                                                placeholder="Location" id="location">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="submit"
                                            class="btn btn-primary add-return" id="submitBtn">Add</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- Returns Table --}}
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center dataTable" id="dataTable" width="100%" cellspacing="0" data-page-length='50'>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Main-SKU</th>
                            <th>Sub-SKU</th>
                            <th>Size</th>
                            <th>Customer Name</th>
                            <th>Location</th>
                            @if ($userRole == 1 || $userRole == 0)
                                <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Main-SKU</th>
                            <th>Sub-SKU</th>
                            <th>Size</th>
                            <th>Customer Name</th>
                            <th>Location</th>
                            @if ($userRole == 1 || $userRole == 0)
                                <th>Action</th>
                            @endif
                        </tr>
                    </tfoot>
                    <tbody class="editdddd">
                        @inject('alldata', 'App\Http\Controllers\MasterController')
                        <?php $i = 1; ?>
                        @foreach ($data as $return)
                            <tr id="tr_{{$return->id}}">
                                <td><?php echo $i++; ?></td>
                                <td><?php echo date('d-m-Y', strtotime($return->date)); ?></td>
                                <td><?php echo $alldata::getproductnamemainsku($return['m_sku']); ?></td>
                                <td><?php echo $return['s_sku']; ?></td>
                                <td><?php echo $return['size']; ?></td>
                                <td><?php echo $alldata::getcustomername($return['customer_id']); ?></td>
                                <td><?php echo $return['location']; ?></td>
                                @if ($userRole == 1 || $userRole == 0)
                                    <td class="d-flex justify-content-center">
                                        <button id="{{ $return->id }}" type="button"
                                            class="btn m-1 btn-sm btn-info editdata1">
                                            <i class="fa fa-pencil-square" aria-hidden="true"></i>
                                        </button>

                                        <button data-id="{{ url('returns/delete/' . $return->id) }}"
                                            class="btn m-1 btn-sm btn-danger brand_delete_btn"><i class="fa fa-trash"
                                                aria-hidden="true" id="delete-return"></i></button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- Edit returns Modal -->
    <div class="modal fade" id="EditReturnModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark h4" id="exampleModalLabel">Edit Returns
                        details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="message" id="messageEdit"></div>
                <div class="modal-body">
                    <form class="forms-sample create-user" method="POST" enctype="multipart/form-data"
                        id="editReturnForm">
                        @csrf
                        <div class="row">
                            <input type="hidden" value="{{ $return->id ?? "" }}" class="form-control id" name="id">
                            <div class="form-group col-sm-6">
                                <div class="text-left">
                                    <label class="text-warning" for="mainSku">Main-SKU
                                        :</label>
                                </div>
                                <select class="form-control select22 main-sku-edit" name="m_sku" id="mainSkuedit"
                                    disabled>
                                    <option value="">Select Main-SKU</option>
                                    @foreach ($sku as $msku)
                                        <option value="{{ $msku->id }}">{{ $msku->sku }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-6">
                                <div class="text-left">
                                    <label class="text-warning" for="subSku">Sub-sku
                                        :</label>
                                </div>
                                <input type="text" class="form-control subSku-edit" name="s_sku"
                                    placeholder="Enter diffrent Sub-SKU" disabled>
                                <input type="hidden" value="Not Found" class="form-control" name="sup_id"
                                    placeholder="Enter diffrent Sub-SKU">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <div class="text-left">
                                    <label class="text-warning" for="size">Size
                                        :</label>
                                </div>
                                <input type="number" class="form-control size-edit" name="size"
                                    placeholder="Enter Size" id="sizeEdit">
                            </div>
                            <div class="form-group col-sm-6">
                                <div class="text-left">
                                    <label class="text-warning" for="customerName">Customer Name
                                        :</label>
                                </div>
                                <select class="form-control select22 customer_id-edit" name="customer_id"
                                    id="customerNameEdit">
                                    <option value="" selected disabled>Choose Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <div class="text-left">
                                    <label class="text-warning" for="date">Date
                                        :</label>
                                </div>
                                <input type="date" class="form-control date-edit" name="date"
                                    placeholder="Enter Date" id="dateEdit">
                            </div>
                            <div class="form-group col-sm-6">
                                <div class="text-left">
                                    <label class="text-warning" for="location">Location
                                        :</label>
                                </div>
                                <input type="text" class="form-control location-edit" name="location"
                                    placeholder="Location" id="locationEdit">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="submit" class="btn btn-primary update-return" id="updateBtn">Update</button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>


    @include('common.script_alert')
@endsection
@section('script')

    <script>
    
    
    
      $(document).ready(function(){
          
    
          
    $('.select2').select2({
        dropdownParent: $('#exampleModal')
    });
         $('.expselect2').select2({
        dropdownParent: $('#ExportDataExcelFile')
    });
   
      })  

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

            let url = "/returns?";
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
        return true;  // Allow empty date (optional)
    }
    let dateArr = date.split('/');
    if (dateArr.length !== 3) {
        return true;  // Invalid format if not exactly three parts
    }
    let day = parseInt(dateArr[0], 10);
    let month = parseInt(dateArr[1], 10);
    let year = parseInt(dateArr[2], 10);

    // Check if parts are valid integers
    if (isNaN(day) || isNaN(month) || isNaN(year)) {
        return true;
    }

    // Check day range based on month and leap year (not fully implemented here)
    if (day < 1 || day > 31 || month < 1 || month > 12) {
        return true;
    }

    // Additional validation logic can be added here if needed

    return false;  // Date format is valid
}

        $(document).on('keyup', '.s_sku', function() {
            let html = $(this);
            let main_sku = $('.main-sku').val();
            let currentValue = html.val();
            let url = "{{ url('returns/checkexist') }}";
            $.ajax({
                type: "post",
                url: url,
                data: {
                    main_sku: main_sku,
                    sub_sku: currentValue
                },
                // dataType: "dataType",
                success: function(response) {
                    console.log(response)
                    if (response == 201) {
                        html.addClass('border-danger');
                    } else {
                        html.removeClass('border-danger');
                    }
                }
            });
        });



        $(document).on('keyup', '.s_sku', function() {
            let html = $(this);
            let main_sku = $('.main-sku').val();
            let currentValue = html.val();
            let url = "{{ url('returns/checkexist') }}";
            $.ajax({
                type: "post",
                url: url,
                data: {
                    main_sku: main_sku,
                    sub_sku: currentValue
                },
                // dataType: "dataType",
                success: function(response) {
                    console.log(response)
                    if (response == 201) {
                        html.addClass('border-danger');
                    } else {
                        html.removeClass('border-danger');
                    }
                }
            });
        });
        // Set the current date in the desired format (dd/mm/yyyy)
        var today = new Date();
        var day = String(today.getDate()).padStart(2, '0');
        var month = String(today.getMonth() + 1).padStart(2, '0'); // Months are zero-based
        var year = today.getFullYear();
        var formattedDate = day + '/' + month + '/' + year;
        document.getElementById('date').value = formattedDate;

      // Disable submit button after click
            function disableSubmitButton() {
                $('.add-return').prop('disabled', true);
            }
            
            // Enable submit button
            function enableSubmitButton() {
                $('.add-return').prop('disabled', false);
            }
            
             // Call enableSubmitButton on any input change
            $(document).on('input change', 'input, select', function() {
                enableSubmitButton();
            });
            
            
            
            function disableUpdateButton() {
                $('.update-return').prop('disabled', true);
            }
            
            // Enable submit button
            function enableUpdateButton() {
                $('.update-return').prop('disabled', false);
            }
            
             // Call enableSubmitButton on any input change
            $(document).on('input change', 'input, select', function() {
                enableUpdateButton();
            });

       $(document).on('click', '.add-return', function(e) {
    e.preventDefault();
    disableSubmitButton();

    // Perform validation checks
    let mainSku = $('.main-sku').val();
    let sSku = $('.s_sku').val();
    let qty = $('.size').val();
    let customerId = $('.customer_id').val();
    let date = $('.date').val();
    let location = $('.location').val();
    let hasError = false;

    // Clear previous error states
    $('.form-control').removeClass('border-danger');
    $('#message').html('');

    // Check if fields are empty
    if (!mainSku || !sSku || !qty || !customerId || !date || !location) {
        alert('Please fill out all fields.');
        hasError = true;
    }

    // Validate quantity (e.g., should be a positive number)
    if (qty && isNaN(qty) || qty <= 0) {
        $('.size').addClass('border-danger');
        alert('Please enter a valid quantity.');
        hasError = true;
    }

    // Validate date format (example: YYYY-MM-DD)
    if (date && !/^\d{4}-\d{2}-\d{2}$/.test(date)) {
        $('.date').addClass('border-danger');
        alert('Please enter a valid date (YYYY-MM-DD).');
        hasError = true;
    }

    // Check if 's_sku' has class 'border-danger'
    if ($('.s_sku').hasClass('border-danger')) {
        alert('Subsku already exists.');
        enableSubmitButton();
        return;
    }

    // If there are validation errors, stop here
    if (hasError) {
        enableSubmitButton();
        return;
    }

    // If validation passed, proceed with AJAX request
    let form_data = {
        m_sku: mainSku,
        s_sku: sSku,
        qty: qty,
        customer_id: customerId,
        date: date,
        location: location
    };
    let url = "{{ url('returns/save') }}";

    $.ajax({
        type: "post",
        url: url,
        data: form_data,
        success: function(response) {
            if (response.success) {
                $('#message').html(
                    '<div class="alert alert-success">' + response.message + '</div>'
                );
                setTimeout(function() {
                    window.location.href = '/returns'; // Redirect to a specific page
                }, 1500);
            } else {
                $('#message').html('<div class="alert alert-danger">' + response.message + '</div>');
                setTimeout(function() {
                    window.location.href = '/returns'; // Redirect to a specific page
                }, 300000);
                enableSubmitButton();
            }
        },
        error: function(response) {
            $('#message').html('<div class="alert alert-danger"> An unexpected error occurred: ' + response.responseText + '</div>');
            setTimeout(function() {
                window.location.href = '/returns'; // Redirect to a specific page
            }, 300000);
            enableSubmitButton();
        }
    });
});


        $(document).on('click', '.update-return', function(e) {
            e.preventDefault();
            disableUpdateButton();
            let form_data = {
                _token: $('meta[name="csrf-token"]').attr('content'), // Add CSRF token
                id: $('.id').val(), // Ensure the ID is included
                product_id: $('.main-sku-edit').val(),
                m_sku: $('.main-sku-edit').val(),
                s_sku: $('.subSku-edit').val(),
                size: $('.size-edit').val(),
                qty: $('.size-edit').val(),
                customer_id: $('.customer_id-edit').val(),
                date: $('.date-edit').val(),
                location: $('.location-edit').val()
            };
            $.ajax({
                type: "post",
                url: "{{ url('returns/edit') }}",
                data: form_data,
                success: function(response) {
                        if(response.success){
                        $('#messageEdit').html(
                            '<div class="alert alert-success">' + response.message + '</div>'
                        );
                        setTimeout(function() {
                            window.location.href = '/returns'; // Redirect to a specific page
                        }, 1500);
                        }else{
                        $('#messageEdit').html('<div class="alert alert-danger">' + response.message + '</div>');
                        setTimeout(function() {
                            window.location.href = '/returns'; // Redirect to a specific page
                        }, 300000);
                        enableUpdateButton();
                            
                        }
                      },
                    error: function(response) {
                        $('#messageEdit').html('<div class="alert alert-danger"> An unexpected error occurred: ' + response.message + '</div>');
                        setTimeout(function() {
                            window.location.href = '/returns'; // Redirect to a specific page
                        }, 300000);
                        enableUpdateButton();
                    }
            });
        });

        $(document).ready(function() {
           
            // Populate the form with the current data when the modal is shown
            $(".dataTable").on("click", ".editdata1", function(){
                let returnId = $(this).attr('id');
              
                $.ajax({
                    url: "{{ url('returns') }}/" + returnId + "/edit",
                    method: 'GET',
                    success: function(data) {
                        let date = data.date.split(' ')[0];
                        // Assuming `data` contains the return data
                        $('#editReturnForm .id').val(data.id);
                        $('#editReturnForm .main-sku-edit').val(data.m_sku);
                        $('#editReturnForm .subSku-edit').val(data.s_sku);
                        $('#editReturnForm .size-edit').val(data.size);
                        $('#editReturnForm .customer_id-edit').val(data.customer_id);
                        $('#editReturnForm .date-edit').val(date);
                        $('#editReturnForm .location-edit').val(data.location);
                        $('#EditReturnModal').modal('show');
                        $('.select22').select2({
                          dropdownParent: $('#EditReturnModal')
                          });
                    },
                    error: function(xhr) {
                        let errorMsg = 'An error occurred: ' + xhr.status + ' ' + xhr
                        .statusText;
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMsg += '\n' + xhr.responseJSON.error;
                        }
                        alert(errorMsg);
                    }
                });
            });
        });

       
    </script>
@endsection
