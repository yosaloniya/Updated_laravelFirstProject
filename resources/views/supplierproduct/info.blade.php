@extends('layout.app')
@section('content')
    <style>
        .select2-container {
            width: 100% !important;
            text-align: left;
        }
    </style>
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card mx-auto sales-form">
            <div class="card">
                <div class="card-body main-row-alert">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title text-dark">Existing Product</h4>
                        </div>
                        <div class="col-4 text-right">
                            <button class="btn btn-sm btn-light" onclick="history.back()">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <hr class="bg-warning">
                    <form class="forms-sample create-user pt-2" method="POST" enctype="multipart/form-data" id="productForm">
                        @csrf
                        <div class="chooseproduct">
                            <div class="row productdata" id="">
                                <div class="form-group col-sm-6">
                                    <label for="sku">Choose SKU :</label>
                                    <select class="form-control sku-select select2" name="sku" id="sku">
                                        <option value="" selected disabled>Select SKU</option>
                                        @foreach ($product as $pr)
                                            <option value="{{ $pr->id }}" data-sku="{{ $pr->sku }}">
                                                {{ $pr->sku }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group asd col-sm-6">
                                    <label for="sub_sku">Choose Sub_SKU :</label>
                                    <select class="form-control sub-sku select2" name="sub_sku" id="sub_sku">
                                        <option value="" selected disabled>Select Sub_SKU</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="supplier">Supplier Name :</label>
                                    <select class="form-control select2 sup-id" name="sup_id" id="supplier">
                                        <option value="" selected disabled>Select Supplier</option>
                                        @foreach ($supplier as $sup)
                                            <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="date">Date :</label>
                                    <input type="text" class="form-control" name="date" placeholder="Enter Date" id="date">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="size">Size(mtr.) :</label>
                                    <input type="number" class="form-control qty" name="qty" placeholder="Enter Size" id="size">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="exampleInputPassword1">Location :</label>
                                    <input type="text" class="form-control location" name="location" placeholder="Add Location"
                                        required>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="exampleInputEmail1">Status :</label>
                                    <select class="form-control status" name="status" id="status">
                                        <option value="" selected disabled>Select Status</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-12">
                                    <label for="exampleInputPassword1">Description :</label>
                                    <input type="text" class="form-control description" name="description" placeholder="Description">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mr-2" id="submitBtn">Add</button>
                        <a href="{{ url('products') }}" class="btn btn-light">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
    
    
        function select() {
            $('.select2').select2({});
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('productForm');
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

        $(document).ready(function() {
            // Set the current date in the desired format (dd/mm/yyyy)
            var today = new Date();
            var day = String(today.getDate()).padStart(2, '0');
            var month = String(today.getMonth() + 1).padStart(2, '0'); // Months are zero-based
            var year = today.getFullYear();
            var formattedDate = day + '/' + month + '/' + year;
            document.getElementById('date').value = formattedDate;
            

            $(document).on("change", ".sku-select", function() {
                let id = $(this).val();
                if (id != 0) {
                    let vrb = $(this);
                    $(this).parent().siblings().children('.sub-sku').empty();
                    let url = "{{ url('product/subskuInactive') }}";
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: { id: id },
                        success: function(data) {
                            if (data) {
                                if (data == 201) {
                                    $('.sub-sku').html("<option value='Not Found' disabled selected>Not Found</option>");
                                } else {
                                    let optd = "<option value='0' selected disabled>Select Sub_SKU</option>";
                                    vrb.parent().siblings().children('.sub-sku').append(optd);
                                    data.forEach(sub => {
                                        let opt = "<option value=" + sub.id + ">" + sub.sku + "</option>";
                                        vrb.parent().siblings().children('.sub-sku').append(opt);
                                    });
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
            
            // $(document).on('change', '.productdata .sub-sku', function() {
            //     let element = $(this);
            //     var id = $(this).val();
            //     let url = "{{ url('product/price') }}";
            //     let d = $(this);
            //     $.ajax({
            //         url: url,
            //         method: 'POST',
            //         data: {
            //             id: id,
            //         },
            //         success: function(data) {

            //             let sku = document.('.productdata').find('.sku-select').val();

            //             document.('.productdata').find('.location').val(data[0].location);
            //         },
            //         error: function(xhr, status, error) {
            //             console.error(xhr.responseText);
            //         }
            //     });
            // });

            $('#productForm').validate({
                rules: {
                    sku: { required: true },
                    sub_sku: { required: true },
                    sup_id: { required: true },
                    qty: { required: true, number: true, min: 1 }
                },
                messages: {
                    sku: { required: "Please select a SKU" },
                    sub_sku: { required: "Please select a Sub SKU" },
                    sup_id: { required: "Please select a Supplier" },
                    qty: { required: "Please enter the size", number: "Please enter a valid number", min: "Size must be greater than 0" }
                }
            });

            $('.sku-select, .sub-sku, .sup-id').on('change', function() {
                $(this).valid(); // Trigger validation on Select2 change
            });

            $('#productForm').submit(function(e) {
                e.preventDefault();
                if ($(this).valid()) {
                    let dateValue = $('#date').val();
                    let parts = dateValue.split('/');
                    if (parts.length === 3) {
                        var formattedForSubmission = parts[2] + '-' + parts[1] + '-' + parts[0];
                        $('<input>').attr({
                            type: 'hidden',
                            name: 'date_formatted',
                            value: formattedForSubmission
                        }).appendTo(this);
                    } else {
                        alert('Invalid date format. Please use dd/mm/yyyy.');
                        return false; // Prevent form submission if the date format is incorrect
                    }

                    let id = $('.sku-select').val();
                    let sup_id = $('.sup-id').val();
                    let qty = $('.qty').val();
                    let location = $('.location').val();
                    let description = $('.description').val();
                    let status = $('.status').val();
                    let sub_sku = $('.sub-sku option:selected').text();
                    sub_sku = sub_sku ? sub_sku : "Not Found";
                    let sku = $('.sku-select option:selected').data("sku");
                    let url = "{{ url('supplierproducts/save') }}";

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {
                            id: id,
                            sup_id: sup_id,
                            sku: sku,
                            sub_sku: sub_sku,
                            date: formattedForSubmission,
                            qty: qty,
                            location: location,
                            description: description,
                            status: status
                        },
                        success: function(response) {
                            if (response == "success") {
                                let successMessage = `
                                    <div class="alert alert-success fade-in alert-dismissible alertmsgnew" role="alert">
                                        <button type="button" class="close" data-dismiss="alert">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        <strong>Success!</strong> Product Quantity Updated Successfully.
                                    </div>`;
                                $(".main-row-alert").prepend(successMessage);
                                setTimeout(() => {
                                    $('.alertmsgnew').fadeOut('fast');
                                    window.location.href = "/supplierproducts";
                                }, 1500);
                            } else {
                                let errorMessage = `
                                    <div class="alert alert-danger fade-in alert-dismissible alertmsgnew" role="alert">
                                        <button type="button" class="close" data-dismiss="alert">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        <strong>Error!</strong> Product Quantity not Updated!
                                    </div>`;
                                $(".main-row-alert").prepend(errorMessage);
                                setTimeout(() => {
                                    $('.alertmsgnew').fadeOut('fast');
                                }, 2000);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>
@endsection
