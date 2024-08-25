@extends('layout.app')
@section('content')
    <style>
        .access-ul-li {
            list-style-type: none;
            width: 100%;
            padding: 10px;
        }

        .access-ul-li li {
            margin-top: 5px;
        }

        .access-btn {
            line-height: 0.7;
            border: 1px solid #d1d3e2;
        }

        .select2-container {
            width: 100% !important;
        }
    </style>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-dark">Users</h1>

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
                <div class="col-8 text-center">

                </div>
                <div class="col-2 text-right">



                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-sm py-2 btn-primary" data-toggle="modal"
                        data-target="#addUserModal">
                        <i class="fa fa-plus" aria-hidden="true"></i> New User
                    </button>

                    <!--Add New User Modal -->
                    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-dark h4" id="exampleModalLabel">Add New User</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ url('users/info') }}" class="forms-sample create-user" method="POST"
                                        enctype="multipart/form-data" id="createUserForm">
                                        @csrf
                                        <div class="row">
                                            <div class="form-group col-sm-6 text-left">
                                                <label class="text-warning" for="user_id">User Id:</label>
                                                <input type="text" class="form-control" name="user_id"
                                                    placeholder="Enter a unique User Id">
                                            </div>
                                            <div class="form-group col-sm-6 text-left">
                                                <label class="text-warning" for="name">Name:</label>
                                                <input type="text" class="form-control" name="name"
                                                    placeholder="Enter User's Name">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-12 text-left">
                                                <label class="text-warning" for="email">Email:</label>
                                                <input type="email" class="form-control" name="email"
                                                    placeholder="Enter Email">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-6 text-left">
                                                <label class="text-warning" for="fname">Father's Name:</label>
                                                <input type="text" class="form-control" name="fname"
                                                    placeholder="Enter Father's Name">
                                            </div>
                                            <div class="form-group col-sm-6 text-left">
                                                <label class="text-warning" for="phone">Phone:</label>
                                                <input type="number" class="form-control" name="phone"
                                                    placeholder="Enter Phone Number">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-6 text-left">
                                                <label class="text-warning" for="role">Role:</label>
                                                <select class="form-control" name="role" id="role">
                                                    <option value="" disabled selected>Choose Role</option>
                                                    <option value="1">Admin</option>
                                                    <option value="0">User</option>
                                                    <option value="-1">Employee</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-sm-6 text-left">
                                                <label class="text-warning" for="sex">Gender:</label>
                                                <select class="form-control" name="sex">
                                                    <option value="" disabled selected>Select Gender</option>
                                                    <option value="m">Male</option>
                                                    <option value="f">Female</option>
                                                    <option value="o">Others</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-12 text-left">
                                                <label class="text-warning" for="access">Permission:</label>
                                                <select class="form-control select2" name="access[]" id="access"
                                                    multiple="multiple">
                                                    <option value="user">Users</option>
                                                    <option value="brand">Brand</option>
                                                    <option value="category">Category</option>
                                                    <option value="products">Products</option>
                                                    <option value="sales">Sales</option>
                                                    <option value="customers">Customers</option>
                                                    <option value="sup_p">Supplier-Products</option>
                                                    <option value="suppliers">Suppliers</option>
                                                    <option value="returns">Returns</option>
                                                    <option value="history">History</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-6 text-left">
                                                <label class="text-warning" for="dob">D.O.B.:</label>
                                                <input type="date" class="form-control" name="dob" placeholder="Date of Birth">
                                            </div>
                                            <div class="form-group col-sm-6 text-left">
                                                <label class="text-warning" for="doj">D.O.J.:</label>
                                                <input type="date" class="form-control" name="doj" placeholder="Date of Joining">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-6 text-left">
                                                <label class="text-warning" for="image">Image:</label>
                                                <input type="file" class="form-control" name="image" placeholder="Upload File" required>
                                            </div>
                                            <div class="form-group col-sm-6 text-left">
                                                <label class="text-warning" for="status">Status:</label>
                                                <select class="form-control" name="status">
                                                    <option value="" disabled selected>Select Status</option>
                                                    <option value="1">Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-6 text-left">
                                                <label class="text-warning" for="password">Password:</label>
                                                <input type="password" class="form-control" name="password" placeholder="Enter Password" id="password">
                                            </div>
                                            <div class="form-group col-sm-6 text-left">
                                                <label class="text-warning" for="confirm_password">Confirm
                                                    Password:</label>
                                                <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password">
                                            </div>
                                        </div>
                                        <div class="form-group text-left">
                                            <label class="text-warning" for="address">Address:</label>
                                            <textarea class="form-control addressdata" name="address" cols="30" rows="3"></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="submit" class="btn btn-primary" id="submitBtn">Add</button>
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
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($data as $user)
                                <tr id="tr_{{$user->id}}">
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo $user['name']; ?></td>
                                    <td><?php echo $user['email']; ?></td>

                                    <td>
                                        @if ($user->role == 1)
                                            Admin
                                        @elseif($user->role == 0)
                                            User
                                        @else
                                            Employee
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->status == 1)
                                            <a href="{{ url('users/status/' . $user->id) }}"
                                                class="btn btn-sm btn-success">Active</a>
                                        @else
                                            <a href="{{ url('users/status/' . $user->id) }}"
                                                class="btn btn-sm btn-secondary">Inactive</a>
                                        @endif
                                    </td>
                                    <td class="d-flex justify-content-center">
                                        <button type="button" class="btn m-1 btn-sm btn-light usersdatabtn"
                                            id="{{ $user->id }}">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </button>


                                        <button id="{{ $user->id }}" type="button"
                                            class="btn m-1 btn-sm btn-info editdata">
                                            <i class="fa fa-pencil-square" aria-hidden="true"></i>
                                        </button>


                                        <button data-id="{{ url('users/delete/' . $user->id) }}"
                                            class="btn m-1 btn-sm btn-danger brand_delete_btn"><i class="fa fa-trash"
                                                aria-hidden="true"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--User information Modal -->
        <div class="modal fade" id="UserInformationModel" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark h4" id="exampleModalLabel">User information</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="">
                                    <img src=""
                                        class="imgget1 img-fluid ml-3  border border-success rounded-circle"
                                        width="100px" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="text-dark text-left">
                            <ul class="text-dark font-weight-bold mt-4">
                                <li>
                                    <div class="row">
                                        <div class="col-4">
                                            <p>
                                                User Id :
                                            </p>
                                        </div>
                                        <div class="col-8">
                                            <p class="useriddata1">

                                            </p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="row">
                                        <div class="col-4">
                                            <p>
                                                Name :
                                            </p>
                                        </div>
                                        <div class="col-8">
                                            <p class="namedata1">

                                            </p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="row">
                                        <div class="col-4">
                                            <p>
                                                Father's Name :
                                            </p>
                                        </div>
                                        <div class="col-8">
                                            <p class="fnamedata1">

                                            </p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="row">
                                        <div class="col-4">
                                            <p>
                                                Email Address :
                                            </p>
                                        </div>
                                        <div class="col-8">
                                            <p class="emaildata1">

                                            </p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="row">
                                        <div class="col-4">
                                            <p>
                                                Contact :
                                            </p>
                                        </div>
                                        <div class="col-8">
                                            <p class="phonedata1">

                                            </p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="row">
                                        <div class="col-4">
                                            <p>
                                                Address :
                                            </p>
                                        </div>
                                        <div class="col-8">
                                            <p class="addressdata1">

                                            </p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="row">
                                        <div class="col-4">
                                            <p>
                                                Gender :
                                            </p>
                                        </div>
                                        <div class="col-8">
                                            <p class="sexdata1">

                                            </p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="row">
                                        <div class="col-4">
                                            <p>
                                                D.O.B. :
                                            </p>
                                        </div>
                                        <div class="col-8">
                                            <p class="dobdata1">

                                            </p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="row">
                                        <div class="col-4">
                                            <p>
                                                D.O.J. :
                                            </p>
                                        </div>
                                        <div class="col-8">
                                            <p class="dojdata1">

                                            </p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="row">
                                        <div class="col-4">
                                            <p>
                                                Role :
                                            </p>
                                        </div>
                                        <div class="col-8">
                                            <p class="roledata1">

                                            </p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="row">
                                        <div class="col-4">
                                            <p>
                                                Permissions :
                                            </p>
                                        </div>
                                        <div class="col-8">
                                            <p class="permissiondata1">

                                            </p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="row">
                                        <div class="col-4">
                                            <p>
                                                Status :
                                            </p>
                                        </div>
                                        <div class="col-8">
                                            <p class="statusdata1">

                                            </p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        @php
            // Ensure $user->access is an array
            $accessArray = json_decode($user->access, true);

        @endphp

        <!--Users update Modal -->
        <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark h4" id="exampleModalLabel">Edit User
                            details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('users/update') }}" class="forms-sample create-user" method="POST"
                            enctype="multipart/form-data"  id="updateUserForm">
                            @csrf
                            <div class="row">
                                <input type="hidden" name="id" class="id">
                                <div class="form-group col-sm-6 text-left">
                                    <label class="text-warning" for="user_id">User Id:</label>
                                    <input type="text" class="form-control user_iddata" name="user_id" id="user_id" placeholder="Enter a unique User Id" disabled>
                                </div>
                                <div class="form-group col-sm-6">
                                    <div class="text-left">
                                        <label class="text-warning" for="name">Name :</label>
                                    </div>
                                    <input type="text" class="form-control namedata" name="name" id="name" placeholder="Enter User's Name">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <div class="text-left">
                                        <label class="text-warning" for="email">Email :</label>
                                    </div>
                                    <input type="text" class="form-control emaildata" name="email" id="email" placeholder="Enter Email">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6 text-left">
                                    <label class="text-warning" for="fname">Father's Name:</label>
                                    <input type="text" class="form-control fnamedata" name="fname" placeholder="Enter Father's Name">
                                </div>
                                <div class="form-group col-sm-6">
                                    <div class="text-left">
                                        <label class="text-warning" for="phone">Phone :</label>
                                    </div>
                                    <input type="number" class="form-control phonedata" name="phone" id="phone" placeholder="Enter Phone Number">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6 text-left">
                                    <label class="text-warning" for="role">Role:</label>
                                    <select class="form-control roledata" name="role" id="role_edit">
                                        <option value="" disabled selected>Choose Role</option>
                                        <option value="1" {{ $user->role == 1 ? 'selected' : '' }}>Admin</option>
                                        <option value="0" {{ $user->role == 0 ? 'selected' : '' }}>User</option>
                                        <option value="-1" {{ $user->role == -1 ? 'selected' : '' }}>Employee</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <div class="text-left">
                                        <label class="text-warning" for="gender">Gender :</label>
                                    </div>
                                    <select class="form-control sexdata" name="sex" id="gender">
                                        <option value="" selected disabled> Select Gender </option>
                                        <option value="m" {{ $user->sex == 'm' ? 'selected' : '' }}>Male</option>
                                        <option value="f" {{ $user->sex == 'f' ? 'selected' : '' }}>Female</option>
                                        <option value="o" {{ $user->sex == 'o' ? 'selected' : '' }}>Others</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 text-left">
                                    <label class="text-warning" for="access">Permission:</label>
                                    <select class="form-control select2 accessdata" name="access[]" id="access_edit"
                                        multiple="multiple">
                                        <option value="user">Users</option>
                                        <option value="brand">Brand</option>
                                        <option value="category">Category</option>
                                        <option value="products">Products</option>
                                        <option value="sales">Sales</option>
                                        <option value="customers">Customers</option>
                                        <option value="sup_p">Supplier-Products</option>
                                        <option value="suppliers">Suppliers</option>
                                        <option value="returns"> Returns</option>
                                        <option value="history">History</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6 text-left">
                                    <label class="text-warning" for="dob">D.O.B.:</label>
                                    <input type="date" class="form-control dobdata" name="dob" placeholder="Date of Birth">
                                </div>
                                <div class="form-group col-sm-6 text-left">
                                    <label class="text-warning" for="doj">D.O.J.:</label>
                                    <input type="date" class="form-control dojdata" name="doj" placeholder="Date of Joining">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6 text-left">
                                    <label class="text-warning" for="image">Image :</label>
                                    <input type="hidden" class="imagedata" name="img">
                                    <input type="file" class="form-control " name="image" id="image" placeholder="Upload File">
                                </div>
                                <div class="form-group col-sm-6 text-left">
                                    <label class="text-warning" for="status">Status:</label>
                                    <select class="form-control" name="status">
                                        <option value="" disabled selected>Select Status</option>
                                        <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="text-left text-warn text-lefting">
                                    <label class="text-warning" for="address">Address :</label>
                                </div>
                                <textarea class="form-control addressdata" name="address" id="" cols="30" rows="3" id="address"></textarea>
                            </div>

                            <div class="imgget my-3">
                                <img src="" class="img-fluid" width="70px" alt="">
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="submit" class="btn btn-primary" id="updateBtn">Update</button>
                            </div>

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
    
            document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('createUserForm');
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
            var form = document.getElementById('updateUserForm');
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
        
        
        $(document).ready(function() {
            

            // Initialize Select2
            $('.select2').select2();

            $('#addUserModal').on('shown.bs.modal', function() {
                $(this).find('.select2').select2();
            });
            // Automatically check all access checkboxes if role is admin
            $('#role').change(function() {
                if ($(this).val() == '1') {
                    $('#access option').prop('selected', true).trigger('change');
                } else {
                    $('#access option').prop('selected', false).trigger('change');
                }
            });

            // If "All" access is checked, check/uncheck all other access options
            $('#access').change(function() {
                var allSelected = $(this).val().includes('all');
                if (allSelected) {
                    $('#access option').prop('selected', true).trigger('change');
                }
            });
            $('#editUserModal').on('shown.bs.modal', function() {
                $(this).find('.select2').select2();
            });

            // Automatically check all access checkboxes if role is admin
            $('#role_edit').change(function() {
                if ($(this).val() == '1') {
                    $('#access_edit option').prop('selected', true).trigger('change');
                } else {
                    $('#access_edit option').prop('selected', false).trigger('change');
                }
            });

            // If "All" access is checked, check/uncheck all other access options
            $('#access_edit').change(function() {
                var allSelected = $(this).val().includes('all');
                if (allSelected) {
                    $('#access_edit option').prop('selected', true).trigger('change');
                }
            });
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.editdata').click(function() {
            let id = Number($(this).attr('id'));

            let url = "{{ url('users/data') }}";
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    id: id,
                },
                success: function(response) {
                    $('#editUserModal').modal('show');
                    $('.user_iddata').val(response.user_id);
                    $('.namedata').val(response.name);
                    $('.emaildata').val(response.email);
                    $('.phonedata').val(response.phone);
                    $('.imagedata').val(response.image);
                    let img = "{{ asset('uploads/') }}"
                    $('.imgget img').attr('src', img + "/" + response.image);
                    $('.fnamedata').val(response.fname);
                    $('.sexdata').val(response.sex);
                    $('.roledata').val(response.role);
                    $('.accessdata').val(response.access)
                    $('.accessdata').select2();
                    $('.dobdata').val(response.dob);
                    $('.dojdata').val(response.doj);
                    $('.addressdata').val(response.address);
                    $('.id').val(response.id);
                },

            });
        });

        function formatDatedata(dateString) {
            var date = new Date(dateString);
            var month = ('0' + (date.getMonth() + 1)).slice(-2); // Add leading zero if needed
            var day = ('0' + date.getDate()).slice(-2); // Add leading zero if needed
            var year = date.getFullYear();
            return month + '/' + day + '/' + year;
        }

        function formatDate(dateString) {
            var date = new Date(dateString);
            var day = date.getDate();
            var month = date.getMonth() + 1; // Months are zero-based
            var year = date.getFullYear();

            // Ensure day and month are two digits
            day = day < 10 ? '0' + day : day;
            month = month < 10 ? '0' + month : month;

            return day + '-' + month + '-' + year;
        }

        $('.usersdatabtn').click(function() {
            let id = Number($(this).attr('id'));
            let url = "{{ url('users/data') }}";
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    id: id,
                    _token: "{{ csrf_token() }}" // Ensure CSRF token is included for security
                },
                success: function(response) {
                    if (response.role == 1) {
                        $('.roledata1').html('Admin');
                    } else if (response.role == 0) {
                        $('.roledata1').html('User');
                    } else {
                        $('.roledata1').html('Employee');
                    }
                    if(response.access == "user"){
                        $('.permissiondata1').html('Users');

                    }else if(response.access == "brand"){
                        $('.permissiondata1').html('Brand');

                    }else if(response.access == "category"){
                        $('.permissiondata1').html('Category');

                    }else if(response.access == "products"){
                        $('.permissiondata1').html('Products');

                    }else if(response.access == "sales"){
                        $('.permissiondata1').html('Sales');

                    }else if(response.access == "customers"){
                        $('.permissiondata1').html('Customers');

                    }else if(response.access == "sup_p"){
                        $('.permissiondata1').html('Supplier Products');

                    }else if(response.access == "suppliers"){
                        $('.permissiondata1').html('Suppliers');

                    }else if(response.access == "returns"){
                        $('.permissiondata1').html('Returns');

                    }else if(response.access == "history"){
                        $('.permissiondata1').html('History');

                    }else{
                        $('.permissiondata1').html('Users, Brand, Category, Products, Sales, Customers, Supplier Products, Suppliers, Returns, History');
                    }
                    // $('.permissiondata1').html(response.access);
                    $('.useriddata1').html(response.user_id);
                    $('.namedata1').html(response.name);
                    $('.emaildata1').html(response.email);
                    $('.phonedata1').html(response.phone);

                    let img1 = "{{ asset('uploads/') }}";
                    $('.imgget1').attr('src', img1 + "/" + response.image);

                    $('.fnamedata1').html(response.fname);

                    if (response.sex == 'm') {
                        $('.sexdata1').html('Male');
                    } else if (response.sex == 'f') {
                        $('.sexdata1').html('Female');
                    } else {
                        $('.sexdata1').html('Others');
                    }

                    $('.addressdata1').html(response.address);

                    // Format and display the dates
                    $('.dobdata1').html(formatDate(response.dob));
                    $('.dojdata1').html(formatDate(response.doj));

                    if (response.status == 1) {
                        $('.statusdata1').html('Active');
                    } else {
                        $('.statusdata1').html('Inactive');
                    }

                    $('#UserInformationModel').modal('show');
                }
            });
        });
    </script>
@endsection
