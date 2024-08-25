@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card mx-auto">
            <div class="card">
                <div class="card-body">
                    <span>
                        <div class="row">

                            <div class="col-8">
                                <h4 class="card-title text-dark">Edit Brand Details</h4>
                            </div>
                            <div class="col-4 text-right">
                                <button class="btn btn-sm btn-light" onclick="history.back()">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                        @include('common.alert')
                        <hr class="bg-warning">
                        <form class="forms-sample create-user pt-2" id="create-user" method="POST"
                            enctype="multipart/form-data" id="createBrandForm">
                            @csrf
                            <div class="form-group">
                                <label for="exampleInputPassword1">Brand Name :</label>
                                <input type="text" class="form-control" value="{{ $data->brand }}" name="brand"
                                    placeholder="Enter Brand Name">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Status :</label>
                                <select class="form-control " name="status" id="status">
                                    <option id="1" value="" selected disabled>Select</option>

                                    <option id="1" value="1" <?php echo $data->status == 1 ? 'selected' : ''; ?>>Active</option>
                                    <option id="1" value="0" <?php echo $data->status == 0 ? 'selected' : ''; ?>>Inactive</option>

                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Description :</label>
                                <input type="text" class="form-control" value="{{ $data->description }}"
                                    name="description" placeholder="Description">
                            </div>



                            <button type="submit" class="btn btn-sm btn-primary mr-2" id="submitBtn">Update</button>
                            <a href="{{ url('brand') }}" class="btn btn-sm btn-secondary">Cancel</a>
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
