@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-md-9 grid-margin stretch-card mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-8">
                            <h4 class="card-title text-dark">Add New Category</h4>
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
                            <div class="form-group col-sm-6">
                                <label for="exampleInputPassword1">Select Brand :</label>
                                <select class="form-control select2" name="brand_id">
                                    <option value="" selected disabled>Select Brand</option>
                                    @foreach ($brand as $br)
                                        <option value="{{ $br->id }}">{{ $br->brand }}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="exampleInputPassword1">Category Name :</label>
                                <input type="text" class="form-control" name="name" placeholder="Enter Category Name">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="exampleInputPassword1">Image :</label>
                                <input type="file" class="form-control" name="image" placeholder="Image" required>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="exampleInputEmail1">Status :</label>
                                <select class="form-control " name="status" id="status">
                                    <option value="" selected disabled>Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Description :</label>
                            <input type="text" class="form-control" name="description" placeholder="Description">
                        </div>
                        
                        <button type="submit" class="btn btn-primary mr-2" id="submitBtn">Add</button>
                        <a href="{{ url('category') }}" class="btn btn-light">Cancel</a>
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
