@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-8">
                            <h4 class="card-title text-dark">Add New Brand</h4>
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
                        <div class="form-group">
                            <label for="exampleInputPassword1">Brand Name :</label>
                            <input type="text" class="form-control" name="brand" placeholder="Enter Brand Name">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Status :</label>
                            <select class="form-control " name="status" id="status">
                                <option value="" selected disabled>Select Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>

                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Description :</label>
                            <input type="text" class="form-control" name="description" placeholder="Description">
                        </div>



                        <button type="submit" class="btn btn-primary mr-2" id="submitBtn">Add</button>
                        <a href="{{ url('brand') }}" class="btn btn-light">Cancel</a>
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
