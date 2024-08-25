@extends('layout.app')
@section('content')
    <?php
    $userRole = Auth::user()->role;
    ?>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-dark">Customers</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            @include('common.alert')
            <div class="row">
                <div class="col-2 ">
                    <button class="btn btn-sm py-2 btn-secondary" onclick="history.back()">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i>
                        Back</button>

                </div>
                <div class="col-8"></div>
                <div class="col-2 text-right">


                    <!-- Button trigger modal -->
                    @if ($userRole == 1 || $userRole == 0)
                        <button type="button" class="btn btn-sm py-2 btn-primary" data-toggle="modal"
                            data-target="#exampleModal">
                            <i class="fa fa-plus" aria-hidden="true"></i> New Customer
                        </button>
                    @endif
                    
                    
                    <!--Add New Customer Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-dark h4" id="exampleModalLabel">Manage Customer details</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">

                                    <form action="{{ url('customers/info') }}" class="forms-sample create-user"
                                        method="POST" enctype="multipart/form-data" id="createCustomerForm">
                                        @csrf
                                        <div class="form-group">
                                            <div class="text-left">
                                                <label class="text-warning" for="exampleInputPassword1">Name
                                                    :</label>
                                            </div>
                                            <input type="text" class="form-control" name="name"
                                                placeholder="Customer Name" id="customerName">
                                        </div>
                                        <div class="form-group">
                                            <div class="text-left">
                                                <label class="text-warning" for="exampleInputPassword1">Contact
                                                    :</label>
                                            </div>
                                            <input type="number" class="form-control" name="contact"
                                                placeholder="Enter Contact Number">
                                        </div>
                                        <div class="form-group">
                                            <div class="text-left text-warning">
                                                <label class="text-warning" for="exampleInputPassword1">Address :</label>
                                            </div>
                                            <textarea class="form-control addressdata" name="address" id="" cols="30" rows="2"
                                                placeholder="Address"></textarea>
                                        </div>

                                        <div class="form-group">
                                            <div class="text-left">
                                                <label class="text-warning" for="exampleInputEmail1">Status :</label>
                                            </div>
                                            <select class="form-control " name="status">
                                                <option value="" selected disabled>Select Status</option>
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>

                                            </select>
                                        </div>


                                        <div class="modal-footer">
                                            <button type="submit" name="submit" class="btn btn-primary " id="submitBtn">Add</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Customer Name</th>
                                <th>Contact</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Customer Name</th>
                                <th>Contact</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($data as $customer)
                                <tr id="tr_{{$customer->id}}">
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo $customer['name']; ?></td>
                                    <td><?php echo $customer['contact']; ?></td>
                                    <td><?php echo $customer['address']; ?></td>
                                    <td>
                                        @if ($customer->status == 1)
                                            <a href="@if ($userRole == 1 || $userRole == 0) {{ url('customers/status/' . $customer->id) }} @endif"
                                                class="btn btn-sm btn-success">Active</a>
                                        @else
                                            <a href="@if ($userRole == 1 || $userRole == 0) {{ url('customers/status/' . $customer->id) }} @endif"
                                                class="btn btn-sm btn-secondary">Inactive</a>
                                        @endif
                                    </td>

                                    <td class="d-flex justify-content-center">
                                        <button id="{{ $customer->id }}" type="button"
                                            class="btn m-1 btn-sm btn-warning costomer-product-info"><i class="fa fa-bars"
                                                aria-hidden="true"></i></button>
                                        @if ($userRole == 1 || $userRole == 0)
                                            <button id="{{ $customer->id }}" type="button"
                                                class="btn m-1 btn-sm btn-info editdata1">
                                                <i class="fa fa-pencil-square" aria-hidden="true"></i>
                                            </button>

                                            <button data-id="{{ url('customers/delete/' . $customer->id) }}"
                                                class="btn btn-sm m-1 btn-danger brand_delete_btn"><i class="fa fa-trash"
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


        <!-- Edit customers Modal -->
        <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark h4" id="exampleModalLabel">Edit Customer details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <form action="{{ url('customers/update') }}" class="forms-sample create-user" method="POST"
                            enctype="multipart/form-data" id="updateCustomerForm">
                            @csrf
                            <div class="form-group">
                                <input type="hidden" name="id" class="id">
                                <div class="text-left">
                                    <label class="text-warning" for="exampleInputPassword1">Name
                                        :</label>
                                </div>
                                <input type="text" class="form-control namedata" name="name"
                                    placeholder="Customer Name" id="customerName">
                            </div>
                            <div class="form-group">
                                <div class="text-left">
                                    <label class="text-warning" for="exampleInputPassword1">Contact
                                        :</label>
                                </div>
                                <input type="number" class="form-control contactdata" name="contact"
                                    placeholder="Enter Contact Number">
                            </div>
                            <div class="form-group">
                                <div class="text-left text-warning">
                                    <label class="text-warning" for="exampleInputPassword1">Address :</label>
                                </div>
                                <textarea class="form-control addressdata" name="address" id="" cols="30" rows="2"></textarea>
                            </div>

                            <div class="form-group">
                                <div class="text-left">
                                    <label class="text-warning" for="exampleInputEmail1">Status :</label>
                                </div>
                                <select class="form-control statusdata" name="status">
                                    <option value="" selected disabled>Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>

                                </select>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" name="submit" class="btn btn-primary " id="updateBtn">Update</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>


        <!-- Customer Orders modal -->
        <div class="modal fade " id="exampleModal12" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark h4" id="exampleModalLabel">Orders</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center" id="dataTableModal" width="100%"
                                cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Order No.</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Order No.</th>
                                    </tr>
                                </tfoot>

                                <tbody class="customer_data_tbody">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('common.script_alert')
    @endsection
    @section('script')
    
    
        <script>
        
            // customer data edit script
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            
            $(document).ready(function() {
        $('#name').blur(function() {
            var user_id = $(this).val();
            $.ajax({
                url: '/checkUserId',
                type: 'POST',
                data: {
                    user_id: user_id
                },
                success: function(response) {
                    if (response.exists) {
                        alert('User ID already exists!');
                        // Optionally, disable form submission or handle validation here
                    }
                }
            });
        });
    });
    
     document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('createCustomerForm');
            var submitButton = document.getElementById('submitBtn');
            var formInputs = form.querySelectorAll('input, select');

            form.addEventListener('submit', function() {
                submitButton.disabled = true;
            });

            formInputs.forEach(function(input) {
                input.addEventListener('input', function() {
                    submitButton.disabled = false;
                });
            });
        });
        
        
     document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('updateCustomerForm');
            var submitButton = document.getElementById('updateBtn');
            var formInputs = form.querySelectorAll('input, select');

            form.addEventListener('submit', function() {
                submitButton.disabled = true;
            });

            formInputs.forEach(function(input) {
                input.addEventListener('input', function() {
                    submitButton.disabled = false;
                });
            });
        });
    
             
            $('.editdata1').click(function() {
                let id = Number($(this).attr('id'));
                let url = "{{ url('customers/data') }}";
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        id: id,
                    },
                    success: function(response) {
                        $('#exampleModal1').modal('show');
                        $('.namedata').val(response.name);
                        $('.contactdata').val(response.contact);
                        $('.addressdata').val(response.address);
                        $('.statusdata').val(response.status);
                        $('.id').val(response.id);
                    }

                });
            })

            // costomer-product-info script
            $('.costomer-product-info').click(function() {
                let id = Number($(this).attr('id'));
                let url = "{{ url('customers/data/info') }}";
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        id: id,
                    },
                    success: function(response) {
                        if (response.length != 0) {
                             if ($.fn.DataTable.isDataTable('#dataTableModal')) {
                               $('#dataTableModal').DataTable().destroy();
                            }
                            $('.customer_data_tbody').empty(); // Clear previous data
                            response.forEach((data, index) => {
                            let row = $('<tr>');
                            row.append('<td>' + (index + 1) +
                                '</td>'); // Incrementing index for row number
                            row.append('<td class="date">' + formatDate(data.date) + '</td>');
                            row.append('<td class="order_no" data-order_no = '+data.order_no+'>' + '<button class="btn btn-md btn-light">' + data.order_no +
                                '</button>'+'</td>'); // Format date
                            $('.customer_data_tbody').append(row);
                        });
                            $('#exampleModal12').find('.modal-dialog').addClass('modal-lg');
                            $('#exampleModal12').modal('show');
                             $('#dataTableModal').DataTable({
                              "destroy": true // Allow reinitialization
                });
                        } else {
                            alert('No data found'); // Provide a meaningful alert message
                        }
                       

                       
                    }
                });
            });

            

            // Function to format date
            function formatDate(dateString) {
                // Assuming dateString is in ISO 8601 format (e.g., "2024-05-07T12:30:45Z")
                let date = new Date(dateString);
                // Extract day, month, and year components
                let day = date.getDate();
                let month = date.getMonth() + 1; // Months are zero-based, so we add 1
                let year = date.getFullYear();
                // Pad single-digit day and month with leading zeros if needed
                day = day < 10 ? '0' + day : day;
                month = month < 10 ? '0' + month : month;
                // Construct formatted date string
                return day + '/' + month + '/' + year;
            }
            
            
            $(document).on('click', '.order_no', function () {
    let htm = $(this);
    let id = htm.data('order_no');
    let url = "{{ url('customers/data/product') }}";

    $.ajax({
        type: "POST",
        url: url,
        data: {
            id: id,
            _token: "{{ csrf_token() }}" // Include CSRF token if needed
        },
        success: function (response) {
            if (response.status == 200) {
                let str = '<div class="hello product_id_table '+id+'"><table class="table table-bordered text-center" width="100%" cellspacing="0"><thead><tr><th>Main-SKU</th><th>Sub-SKU</th><th>Size</th></tr></thead><tbody>';
                response.data.forEach(data => {
                    str += '<tr><td>'+ data.sku +'</td><td>'+ data.sub_sku +'</td><td>'+ data.qty +'</td></tr>';
                });
                str += '</tbody></table></div>';
                
                // Toggle the visibility of the product_id_table
                if ($('.product_id_table').hasClass(id)) {
                    $(`.${id}`).remove();
                } else {
                    $('.product_id_table').remove();
                    htm.append(str);
                }
            } else {
                alert("Not Found");
            }
        },
        error: function (xhr, status, error) {
            console.error(xhr.responseText);
            alert("An error occurred. Please try again.");
        }
    });
});

        </script>
    @endsection
