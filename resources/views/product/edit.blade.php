@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-md-9 grid-margin stretch-card mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-8">
                            <h4 class="card-title text-dark">Edit Product details</h4>
                        </div>
                        <div class="col-4 text-right">
                            <button class="btn btn-sm btn-light" onclick="history.back()">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    @include('common.alert')
                    <hr class="bg-warning">
                    <form class="forms-sample create-user pt-2" method="POST" enctype="multipart/form-data" id="createBrandForm">
                        @csrf
                        <div class="row">
                            <div class="form-group col-sm-3">
                                <label for="exampleInputPassword1">Product Name :</label>
                                <input type="text" value="{{ $data->name }}" class="form-control" name="name"
                                    placeholder="Enter Product Name">
                            </div>
                            <div class="form-group col-sm-3">
                                <label for="exampleInputPassword1">Main-SKU :</label>
                                <input type="text" value="{{ $data->sku }}" class="form-control" name="sku"
                                    placeholder="Enter product Main-Sku">
                            </div>
                            <div class="form-group col-sm-3">
                                <label for="exampleInputPassword1">Select Category :</label>
                                <select class="form-control select2" name="category_id">
                                    <option value="" selected disabled>Select Category</option>
                                    @foreach ($category as $cat)
                                        <option value="{{ $cat->id }}"<?php echo $data['category_id'] == $cat['id'] ? 'selected' : ''; ?>>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                                <div class="form-group col-sm-3">
                                    <label for="exampleInputPassword1">Supplier :</label>
                                    <select class="form-control select2" name="sup_id" required>
                                        <option value="" selected disabled>Select Supplier</option>
                                        @foreach ($supplier as $sup)
                                            <option value="{{ $sup->id }}" {{$data->sup_id == $sup->id ? "selected" : ""}}>{{ $sup->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="exampleInputPassword1">Image :</label>
                                <input type="hidden" value="{{ $data->image }}" name="img">
                                <input type="file" class="form-control" name="image" placeholder="Upload File">
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="exampleInputPassword1">Description :</label>
                                <input type="text" value="{{ $data->description }}" class="form-control"
                                    name="description" placeholder="Description">
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="exampleInputEmail1">Status :</label>
                                <select class="form-control " name="status" id="status">
                                    <option value="" selected disabled>Select Status</option>
                                    <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <img src="{{ asset('uploads/' . $data->image) }}" width="50px" alt="">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mr-2 mt-4" id="submitBtn">Update</button>
                        <a href="{{ url('products') }}" class="btn btn-light mt-4">Cancel</a>
                    </form>
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
        function select() {
            $('.select2').select2({});
        }
        
        
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('createBrandForm');
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
    </script>

    
@endsection
