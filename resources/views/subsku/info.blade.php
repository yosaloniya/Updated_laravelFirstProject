@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-md-9 grid-margin stretch-card mx-auto">
            <div class="card">
                <div class="card-body main-row-alert">
                    <div class="row">

                        <div class="col-8">
                            <h4 class="card-title text-dark">Add New Sub-SKU</h4>
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
                                <div class="form-group col-sm-4">
                                    <label for="exampleInputPassword1">Sub-SKU :</label>
                                    <input type="text" class="form-control sub-sku" data-index="1" name="sku"
                                        placeholder="Enter Sub-Sku">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="exampleInputPassword1">Supplier :</label>
                                    <select class="form-control select2" name="sup_id" required>
                                        <option value="" selected disabled>Select Supplier</option>
                                        @foreach ($supplier as $sup)
                                            <option value="{{ $sup->id }}"
                                                {{ $sup->id == $data['sup_id'] ? 'selected' : '' }}>{{ $sup->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="exampleInputPassword1">Size(mtr.) :</label>
                                    <input type="number" class="form-control qty" name="qty"
                                        placeholder="Enter Size(mtr.)">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label for="exampleInputPassword1">Location :</label>
                                    <input type="text" class="form-control" name="location" placeholder="Add Location"
                                        required>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="exampleInputPassword1">Description :</label>
                                    <input type="text" class="form-control" name="description" placeholder="Description">
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="exampleInputEmail1">Status :</label>
                                    <select class="form-control " name="status" id="status">
                                        <option value="" selected disabled>Select Status</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="add-more text-center"><button type="button" class="btn btn-danger multiform-add-btn">Add
                            More</button></div>
                    <button type="submit" class="btn btn-primary mr-2 submitForm">Add</button>
                    <a href="{{ url('products') }}" class="btn btn-light">Cancel</a>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" class="main_sku_id" value="{{ $data['id'] }}">
    </div>
    </div>
    @include('common.script_alert')
@endsection
 <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"-->
 <!--   integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="-->
 <!--   crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('.multiform-add-btn').click(function() {
                let str =
                    '<div class="addSection">\n\
                    <hr class="bg-danger"><form class="forms-sample create-user pt-2" method="POST" enctype="multipart/form-data">\n\
                            @csrf\n\
                            <div class="row">\n\
                            <div class="col-12 text-right">\n\
                    <button class="btn btn-sm btn-danger remove-section">X</button>\n\
                    </div>\n\
                                <div class="form-group col-sm-4">\n\
                                    <label for="exampleInputPassword1">Sub-SKU :</label>\n\
                                    <input type="text" class="form-control sub-sku" name="sku" data-index="' + Math
                    .random() + '" placeholder="Enter Sub-Sku">\n\
                                </div>\n\
                                <div class="form-group col-sm-4">\n\
                                    <label for="exampleInputPassword1">Supplier :</label>\n\
                                    <select class="form-control select2" name="sup_id" required>\n\
                                        <option value="" selected disabled>Select Supplier</option>\n\
                                        @foreach ($supplier as $sup)\n\
                                            <option value="{{ $sup->id }}" {{ $sup->id == $data['sup_id'] ? 'selected' : '' }}>{{ $sup->name }}</option>\n\
                                        @endforeach\n\
                                    </select>\n\
                                </div>\n\
                                <div class="form-group col-sm-4">\n\
                                    <label for="exampleInputPassword1">Size(mtr.) :</label>\n\
                                    <input type="number" class="form-control qty" name="qty" placeholder="Enter Size(mtr.)">\n\
                                </div>\n\
                            </div>\n\
                            <div class="row">\n\
                                <div class="form-group col-sm-4">\n\
                                    <label for="exampleInputPassword1">Location :</label>\n\
                                    <input type="text" class="form-control" name="location" placeholder="Add Location"\n\
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
            $(document).on('keyup', '.sub-sku', function() {
                let currentValue = $(this).val().toLowerCase();
                let html = $(this);
                let ind = $(this).data('index');
                let i = 0;
                $('.sub-sku').each(function() {
                    console.log(currentValue, i++);
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
                let url = "{{ url('subsku/checksame') }}";
                let main_sku_id = $('.main_sku_id').val();
                if (!duplicateFound) {
                    $.ajax({
                        type: "post",
                        url: url,
                        data: {
                            main_sku: main_sku_id,
                            sub_sku: currentValue
                        },
                        // dataType: "dataType",
                        success: function(response) {
                            console.log(response)
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

            $('.submitForm').click(function(e) {
                let ajaxRequests = [];
                if ($('.sub-sku').hasClass('border-danger')) {
                    alert('Sub-sku is already exists')
                } else {

                    let formDataArr = [];
                    let ajaxRequests = [];
                    let loc = location.href;
                    let arrLoc = loc.split('/');
                    var id = arrLoc.pop();
                    $('form').each(function(index, form) {
                        let formData = new FormData();

                        let sku = $(form).find('input[name="sku"]').val();
                        let sup_id = $(form).find('select[name="sup_id"]').val();
                        let qty = $(form).find('input[name="qty"]').val();
                        let location = $(form).find('input[name="location"]').val();
                        let description = $(form).find('input[name="description"]').val();
                        let status = $(form).find('select[name="status"]').val();

                        formData.append('sup_id', sup_id);
                        formData.append('status', status);
                        formData.append('sku', sku);
                        formData.append('location', location);
                        formData.append('qty', qty);
                        formData.append('description', description);
                        formData.append('p_id', id);
                        let url = "{{ url('subsku/info') }}";
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
                            console.log(responses);
                            if (responses[0] == '200') {
                                let errorMessage = `
                            <div class="alert alert-success fade-in alert-dismissible alertmsgnew" role="alert">\n\
                                <button type="button" class="close" data-dismiss="alert">\n\
                                    <i class="fa fa-times"></i>\n\
                                </button>\n\
                                <strong>Success ! </strong> Subsku saved Successfully.\n\
                                </div>`;
                                        $(".main-row-alert").prepend(errorMessage);
                                        setTimeout(() => {
                                            $('.alertmsgnew').fadeOut('fast');
                                            location.href = '/subsku/' + id;
                                        }, 1500);
                            } else if (responses[0] == '201') {
                                alert('All fields are required.');
                            } else {
                                alert('something went wrong');
                            }
                        })
                        .catch(function(error) {
                            // Handle error responses
                            console.error('Error submitting forms:', error);
                        });
                }

            });

        });
    </script>
@endsection
