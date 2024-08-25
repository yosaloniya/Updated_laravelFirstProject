@extends('layout.app')
{{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
@section('content')
    <div class="row">
        <div class="col-md-9 grid-margin stretch-card mx-auto">
            <div class="card">
                <div class="card-body main-row-alert">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title text-dark">Add New Product</h4>
                        </div>
                        <div class="col-4 text-right">
                            <button class="btn btn-sm btn-light" onclick="history.back()">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    @include('common.alert')
                    <hr class="bg-warning">
                    <div class="form-div">
                        <form class="forms-sample create-user pt-2" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="form-group col-sm-3">
                                    <label for="exampleInputPassword1">Product Name :</label>
                                    <input type="text" class="form-control p-name" name="name"
                                        placeholder="Enter Product Name">
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="exampleInputPassword1">Main-SKU :</label>
                                    <input type="text" class="form-control main-sku" data-index="1" name="sku"
                                        placeholder="Enter product Main-Sku">
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="exampleInputPassword1">Select Category :</label>
                                    <select class="form-control select2" name="category_id">
                                        <option value="" selected disabled>Select Category</option>
                                        @foreach ($category as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach

                                    </select>
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="exampleInputPassword1">Supplier :</label>
                                    <select class="form-control select2" name="sup_id" required>
                                        <option value="" selected disabled>Select Supplier</option>
                                        @foreach ($supplier as $sup)
                                            <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label for="exampleInputPassword1">Image :</label>
                                    <input type="file" class="form-control" name="image" placeholder="Upload File"
                                        required>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="exampleInputPassword1">Description :</label>
                                    <input type="text" class="form-control desc-description" name="description"
                                        placeholder="Description">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="exampleInputEmail1">Status :</label>
                                    <select class="form-control form-status" name="status" id="status">
                                        <option value="" selected disabled>Select Status</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="add-more text-center mt-3"><button type="button"
                            class="btn btn-danger multiform-add-btn mr-2 addData">Add More</button></div>
                    <button class="submitForm btn btn-primary mr-2 ">Add</button>
                    <a href="{{ url('products') }}" class="btn btn-light">Cancel</a>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    @include('common.script_alert')
@endsection

@section('script')
    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function select() {
                $('.select2').select2({});
            }
            
            


            $('.addData').click(function() {

                let str =
                    '<div class="addSection">\n\
                    <hr class="bg-danger"><form class="forms-sample create-user pt-2" method="POST" enctype="multipart/form-data">\n\
                                    @csrf\n\
                                    <div class="row">\n\
                                    <div class="col-12 text-right">\n\
                    <button class="btn btn-sm btn-danger remove-section">X</button>\n\
                    </div>\n\
                                        <div class="form-group col-sm-3">\n\
                                            <label for="exampleInputPassword1">Product Name :</label>\n\
                                            <input type="text" class="form-control" name="name" placeholder="Enter Product Name">\n\
                                            </div>\n\
                                            <div class="form-group col-sm-3">\n\
                                                <label for="exampleInputPassword1">Main-SKU :</label>\n\
                                                <input type="text" class="form-control main-sku" data-index="' + Math
                    .random() + '" name="sku" placeholder="Enter product Main-Sku">\n\
                                                </div>\n\
                                                <div class="form-group col-sm-3">\n\
                                                    <label for="exampleInputPassword1">Select Category :</label>\n\
                                                    <select class="form-control select2" name="category_id">\n\
                                                        <option value="" selected disabled>Select Category</option>\n\
                                                        @foreach ($category as $cat)\n\
                                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>\n\
                                                        @endforeach\n\
                                                    </select>\n\
                                                </div>\n\
                                                  <div class="form-group col-sm-3">\n\
                                            <label for="exampleInputPassword1">Supplier :</label>\n\
                                            <select class="form-control select2" name="sup_id" required>\n\
                                                <option value="" selected disabled>Select Supplier</option>\n\
                                                @foreach ($supplier as $sup)\n\
                                                    <option value="{{ $sup->id }}">{{ $sup->name }}</option>\n\
                                                @endforeach\n\
                                            </select>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="row">\n\
                                        <div class="form-group col-sm-4">\n\
                                            <label for="exampleInputPassword1">Image :</label>\n\
                                            <input type="file" class="form-control" name="image" placeholder="Upload File"\n\
                                                required>\n\
                                        </div>\n\
                                        <div class="form-group col-sm-4">\n\
                                            <label for="exampleInputPassword1">Description :</label>\n\
                                            <input type="text" class="form-control" name="description" placeholder="Description">\n\
                                        </div>\n\
                                        <div class="form-group col-sm-4">\n\
                                            <label for="exampleInputEmail1">Status :</label>\n\
                                            <select class="form-control " name="status" id="status">\n\
                                                <option value="" selected disabled>Select Status</option>\n\
                                                <option value="1">Active</option>\n\
                                                <option value="0">Inactive</option>\n\
                                            </select>\n\
                                        </div>\n\
                                    </div>\n\
                                </form>\n\
                                </div>';
                $('.form-div').append(str);
                select();


            });
            $(document).on('click', '.remove-section', function() {
        $(this).closest('.addSection').remove();
    });

            let duplicateFound = false;
            $(document).on('keyup', '.main-sku', function() {
                let currentValue = $(this).val().toLowerCase();
                let html = $(this);
                let ind = $(this).data('index');
                let i = 0;
                $('.main-sku').each(function() {
                    if ($(this).val().toLowerCase() === currentValue && $(this).data('index') !==
                        ind) {
                        duplicateFound = true;
                        html.addClass('border-danger');
                        return false; // Exit each loop
                    } else {
                        duplicateFound = false;
                        html.removeClass('border-danger');
                    }
                });
                let url = "{{ url('product/check') }}";
                if (!duplicateFound) {
                    $.ajax({
                        type: "post",
                        url: url,
                        data: {
                            main_sku: currentValue
                        },
                        // dataType: "dataType",
                        success: function(response) {
                            if (response == 201) {
                                html.addClass('border-danger');
                                duplicateFound = true;
                            } else {
                                html.removeClass('border-danger');
                                duplicateFound = false;
                            }
                        }
                    });
                }
            });
            
                  // Disable submit button after click
            function disableSubmitButton() {
                $('.submitForm').prop('disabled', true);
            }
            
            // Enable submit button
            function enableSubmitButton() {
                $('.submitForm').prop('disabled', false);
            }
            
             // Call enableSubmitButton on any input change
            $(document).on('input change', 'input, select', function() {
                enableSubmitButton();
            });

            $('.submitForm').click(function(e) {
                e.preventDefault(); // Prevent the default form submission
                disableSubmitButton(); // Disable the button after click
                let ajaxRequests = [];
                if ($('.main-sku').hasClass('border-danger')) {
                    alert('This main-sku is already exists');
                    enableSubmitButton(); // Re-enable the button if there's a duplicate SKU
                } else {

                    $('form').each(function(index, form) {
                        let formData = new FormData();

                        let image = $(form).find('input[type="file"]')[0].files[0];
                        let category_id = $(form).find('select[name="category_id"]').val();
                        let sup_id = $(form).find('select[name="sup_id"]').val();
                        let description = $(form).find('input[name="description"]').val();
                        let status = $(form).find('select[name="status"]').val();
                        let sku = $(form).find('input[name="sku"]').val();
                        let name = $(form).find('input[name="name"]').val();

                        formData.append('image', image);
                        formData.append('status', status);
                        formData.append('sku', sku);
                        formData.append('name', name);
                        formData.append('category_id', category_id);
                        formData.append('description', description);
                        formData.append('sup_id', sup_id);
                        let id = 24;
                        let url = "{{ url('product/save_multiple') }}";
                        ajaxRequests.push(
                            $.ajax({
                                url: url,
                                method: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                            })
                        )
                    });


                    Promise.all(ajaxRequests)
                        .then(function(responses) {
                            if (responses[0] == 'success') {
                                let errorMessage = 
                            `<div class="alert alert-success fade-in alert-dismissible alertmsgnew" role="alert">\n\
                                <button type="button" class="close" data-dismiss="alert">\n\
                                    <i class="fa fa-times"></i>\n\
                                </button>\n\
                                <strong>Success ! </strong> Product saved successfully.\n\
                                </div>`;
                                
                                        $(".main-row-alert").prepend(errorMessage);
                                        setTimeout(() => {
                                            $('.alertmsgnew').fadeOut('fast');
                                            window.location.href = "/products";
                                        }, 1500);
                            } else {
                                alert('All fields are required.');
                                enableSubmitButton(); // Re-enable the button if there's an error
                            }
                        })
                        .catch(function(error) {
                            enableSubmitButton(); // Re-enable the button if there's an error
                        });
                }

            }); 


        });
    </script>
@endsection
