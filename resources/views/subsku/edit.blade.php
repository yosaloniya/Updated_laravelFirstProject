@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-md-9 grid-margin stretch-card mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-8">
                            <h4 class="card-title text-dark">Edit SKU</h4>
                        </div>
                        <div class="col-4 text-right">
                            <button class="btn btn-sm btn-light" onclick="history.back()">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <hr class="bg-warning">
                    @include('common.alert')
                    <form class="forms-sample create-user-edit pt-2" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <input type="hidden" value="{{ $data->product_id }}" name="product_id">
                            <div class="form-group col-sm-6">
                                <label for="exampleInputPassword1">Sub-SKU :</label>
                                <input type="text" value="{{ $data->sku }}" class="form-control" name="sku"
                                    placeholder="Enter Sub-Sku">
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="exampleInputPassword1">Supplier :</label>
                                <select class="form-control select2" name="sup_id" required>
                                    <option value="" selected disabled>Select Supplier</option>
                                    @foreach ($supplier as $sup)
                                        <option value="{{ $sup->id }}"<?php echo $data['sup_id'] == $sup['id'] ? 'selected' : ''; ?>>{{ $sup->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="exampleInputPassword1">Size(mtr.) :</label>
                                <input type="number" value="{{ $data->qty }}" class="form-control" name="qty"
                                    placeholder="Enter Size(mtr.)">
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="exampleInputPassword1">Location :</label>
                                <input type="text" value="{{ $data->location }}" class="form-control" name="location"
                                    placeholder="Add Location" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="exampleInputPassword1">Description :</label>
                                <input type="text" value="{{ $data->description }}" class="form-control"
                                    name="description" placeholder="Description">
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="exampleInputEmail1">Status :</label>
                                <select class="form-control " name="status" id="status">
                                    <option value="" selected disabled>Select Status</option>
                                    <option value="1"{{ $data->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0"{{ $data->status == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mr-2">Update</button>
                        <button class="btn btn-light" onclick="history.back()">
                            Cancel
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    @include('common.script_alert')
    </div>
@endsection
@section('script')
<script>
    $(document).ready(function(){
       $(".create-user-edit").validate({
            rules: {
                sku: {
                    required: true,
                    noSpace: true
                },
                sup_id: {
                    required: true
                },
                status: {
                    required: true
                },
                qty: {
                    required: true,
                    noSpace: true
                },
                location: {
                    required: true,
                    singleSpace: true
                }
               
            },
            messages: {
               
                sku: {
                    required: "SKU is required.",
                    noSpace: "No spaces are allowed"
                },
                sup_id: {
                    required: "Please select a Supplier."
                },
                status: {
                    required: "Please select status."
                },
                qty: {
                    required: "please enter a Size.",
                    noSpace: "No spaces are allowed"
                },
                location: {
                    required: "Please enter a location.",
                    singleSpace: "Only one space is allowed between words"
                },
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid').removeClass("is-valid");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).addClass("is-valid").removeClass('is-invalid');
            }
        }); 
    });
</script>
@endsection
