<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="shortcut icon" href="{{ asset('/img/thecozycreations.png') }}"type="image/x-icon">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> The Cozycreations</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    <!-- Custom styles for this template-->
    <link href="{{ asset('/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- Page level plugins -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}


</head>
<style>
    .select2-selection--single {
        height: 38.5px !important;
        border: 1px solid #d1d3e2 !important;
    }

    .select2-selection--multiple {
        min-width: 38.5px !important;
        border: 1px solid #d1d3e2 !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        padding-left: 20px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #87878e;
        line-height: 36px;
    }

    .sales-form {
        max-height: 100vh;
    }

    .error {
        font-size: 13px !important;
        color: red;
    }

    .is-invalid~.invalid-feedback,
    .is-invalid~.invalid-tooltip,
    .was-validated :invalid~.invalid-feedback,
    .was-validated :invalid~.invalid-tooltip {
        text-align: left;
    }
</style>
@php
    use Carbon\Carbon;
@endphp

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('layout.sidebar')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>


                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">




                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2  text-gray-600 small">{{ Auth::user()->name }}</span>
                                <img class="img-profile rounded-circle"
                                    src="{{ asset('uploads/' . Auth::user()->image) }}">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">

                                <button class="dropdown-item" href="#" data-toggle="modal"
                                    data-target="#profileModal">
                                    <i class="fa fa-user-circle-o fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </button>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    @yield('content')

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="{{ url('signout') }}">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile modal -->
    <div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Profile</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                @csrf
                <div class="modal-body">
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="">
                                <img src="{{ asset('uploads/' . Auth::user()->image) }}"
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
                                            {{ Auth::user()->user_id }}
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
                                            {{ Auth::user()->name }}
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
                                            {{ Auth::user()->fname }}
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
                                            {{ Auth::user()->email }}
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
                                            {{ Auth::user()->phone }}
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
                                            {{ Auth::user()->address }}
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
                                            @if (Auth::user()->sex == 'm')
                                                Male
                                            @elseif(Auth::user()->sex == 'f')
                                                Female
                                            @else
                                                Others
                                            @endif
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
                                            {{ Carbon::parse(Auth::user()->dob)->format('d/m/Y') }}
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
                                            {{ Carbon::parse(Auth::user()->doj)->format('d/m/Y') }}
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
                                            @if (Auth::user()->role == 1)
                                                Admin
                                            @elseif(Auth::user()->role == 0)
                                                User
                                            @else
                                                Employee
                                            @endif
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
                                            @if (Auth::user()->access == 'user')
                                                Users
                                            @elseif(Auth::user()->access == 'brand')
                                                Brand
                                            @elseif(Auth::user()->access == 'category')
                                                Category
                                            @elseif(Auth::user()->access == 'products')
                                                Products
                                            @elseif(Auth::user()->access == 'sales')
                                                Sales
                                            @elseif(Auth::user()->access == 'customers')
                                                Customers
                                            @elseif(Auth::user()->access == 'sup_p')
                                                Supplier Products
                                            @elseif(Auth::user()->access == 'suppliers')
                                                Suppliers
                                            @elseif(Auth::user()->access == 'returns')
                                                Returns
                                            @elseif(Auth::user()->access == 'history')
                                                History
                                            @else
                                                Users, Brand, Category, Products, Sales, Customers, Supplier Products,
                                                Suppliers, Returns, History, Employee
                                            @endif
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
                                            @if (Auth::user()->status == 1)
                                                Active
                                            @else
                                                Inactive
                                            @endif
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

</body>
<!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->

<!-- Bootstrap core JavaScript-->
<script src="{{ asset('/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Core plugin JavaScript-->
<script src="{{ asset('/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<!-- Custom scripts for all pages-->
<!--<script src="{{ asset('public/js/sb-admin-2.min.js') }}"></script>-->
<!--<script src="{{asset('public/js/jquery-3.7.1.min.js')}}"></script>-->
<!--<script src="{{asset('public/js/jquery-ui.min.js')}}"></script>-->

<!-- Page level plugins -->

<!-- Page level custom scripts -->
<script src="{{ asset('/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<!-- Page level custom scripts -->
<script src="{{ asset('/js/demo/datatables-demo.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<!--<script src="sweetalert2.all.min.js"></script>-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{-- History file Date format --}}
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<!--<script src="https://cdn.jsdelivr.net/jquery.validation/1.19.3/jquery.validate.min.js"></script>-->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!--<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>-->
<script>
    $(document).ready(function() {
        $.validator.addMethod("greaterThanZero", function(value, element) {
            return this.optional(element) || (value > 0);
        }, "Size cannot be 0.");
        $.validator.addMethod("selectRequired", function(value, element, arg) {
            return $(element).val().length > 0;
        }, "Please select at least one access option.");
         // Custom method to validate dd/mm/yyyy format
         $.validator.addMethod("dateDDMMYYYY", function(value, element) {
                // Check for dd/mm/yyyy format
                if (/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[012])\/(19|20)\d\d$/.test(value)) {
                    return true;
                }
                // Check for mm/dd/yyyy format and trigger alert
                if (/^(0[1-9]|1[012])\/(0[1-9]|[12][0-9]|3[01])\/(19|20)\d\d$/.test(value)) {
                    return false;
                }
                return false;
            }, "Please enter a date in the format dd/mm/yyyy.");
            
            $.validator.addMethod("singleSpace", function(value, element) {
                return this.optional(element) || !/  +/.test(value);
            }, "Only one space is allowed between words");
            
            $.validator.addMethod("noSpace", function(value, element) {
                return this.optional(element) || !/\s/.test(value);
            }, "No spaces are allowed");
            
        $(".create-user").validate({
            rules: {
                name: {
                    required: true,
                    maxlength: 30,
                    singleSpace: true
                },
                fname: {
                    required: true,
                    maxlength: 30,
                    singleSpace: true
                },
                phone: {
                    required: true,
                    maxlength: 10,
                    minlength: 10,
                    noSpace: true
                },
                role: {
                    required: true
                },
                sex: {
                    required: true
                },
                'access[]': {
                    selectRequired: true
                },
                email: {
                    required: true,
                    email: true,
                    maxlength: 50,
                    noSpace: true
                },
                password: {
                    required: true,
                    minlength: 8,
                    maxlength: 20,
                    noSpace: true
                },
                confirm_password: {
                    required: true,
                    equalTo: "#password",
                    noSpace: true
                },

                brand: {
                    required: true,
                    singleSpace: true
                },
                customer_id: {
                    required: true
                },
                sup_id: {
                    required: true
                },
                status: {
                    required: true
                },
                brand_id: {
                    required: true
                },

                category_id: {
                    required: true
                },
                sku: {
                    required: true,
                    noSpace: true
                },
                sub_sku: {
                    required: true,
                    noSpace: true
                },
                qty: {
                    required: true,
                    greaterThanZero: true,
                    noSpace: true
                },

                size: {
                    required: true,
                    greaterThanZero: true,
                    noSpace: true
                },
                address: {
                    required: true,
                    singleSpace: true
                },
                contact: {
                    required: true,
                    maxlength: 10,
                    minlength: 10,
                    noSpace: true
                },
                invoice_no: {
                    required: true,
                    noSpace: true
                },
                m_sku: {
                    required: true,
                    noSpace: true
                },
                s_sku: {
                    required: true,
                    noSpace: true
                },
                date: {
                    required: true,
                    // dateDDMMYYYY: true
                },
                location: {
                    required: true,
                    singleSpace: true
                },
                user_id: {
                    required: true,
                    noSpace: true
                },
                dob: {
                    required: true,
                    // date: true
                },
                doj: {
                    required: true,
                    // date: true
                },
            },
            messages: {
                name: {
                    required: "Name is required.",
                    maxlength: "Name cannot be more than 30 characters.",
                    singleSpace: "Only one space is allowed between words"
                },
                fname: {
                    required: "Father's Name is required.",
                    maxlength: "Fathers's Name cannot be more than 30 characters.",
                    singleSpace: "Only one space is allowed between words"
                },
                phone: {
                    required: "Please enter a Phone no..",
                    minlength: "Phone no. cannot be less than 10 characters",
                    maxlength: "Phone no. cannot be more than 10 characters",
                    noSpace: "No spaces are allowed"
                },
                role: {
                    required: "Please select a Role."
                },
                sex: {
                    required: "Please select a Gender."
                },
                'access[]': {
                    selectRequired: "Please select at least one Permission."
                },
                email: {
                    required: "Email is required.",
                    email: "Email must be a valid email address.",
                    maxlength: "Email cannot be more than 30 characters.",
                    noSpace: "No spaces are allowed"
                },
                password: {
                    required: "Password is required.",
                    minlength: "Password must be at least 8 characters.",
                    noSpace: "No spaces are allowed"
                },
                confirm_password: {
                    required: "Confirm password is required.",
                    equalTo: "Password and confirm password should same.",
                    noSpace: "No spaces are allowed"
                },

                status: {
                    required: "Please select status."
                },
                brand: {
                    required: "Please enter a brand name.",
                    singleSpace: "Only one space is allowed between words"
                },
                brand_id: {
                    required: "Please select a brand."
                },
                customer_id: {
                    required: "Please select a Customer."
                },
                sup_id: {
                    required: "Please select a Supplier."
                },
                category_id: {
                    required: "Please select a category."
                },
                sku: {
                    required: "SKU is required.",
                    noSpace: "No spaces are allowed"
                },
                sub_sku: {
                    required: "Sub-SKU is required.",
                    noSpace: "No spaces are allowed"
                },
                qty: {
                    required: "please enter a Size.",
                    greaterThanZero: "Size cannot be 0.",
                    noSpace: "No spaces are allowed"
                },

                size: {
                    required: "Please enter a size.",
                    greaterThanZero: "Size cannot be 0.",
                    noSpace: "No spaces are allowed"
                },
                address: {
                    required: "Please enter an address.",
                    singleSpace: "Only one space is allowed between words"
                },
                contact: {
                    required: "Please enter a contact no.",
                    minlength: "contact no. cannot be less than 10 characters.",
                    maxlength: "contact no. cannot be more than 10 characters.",
                    noSpace: "No spaces are allowed"
                },
                invoice_no: {
                    required: "invoice_no is required.",
                    noSpace: "No spaces are allowed"
                },
                m_sku: {
                    required: "select main-sku.",
                    noSpace: "No spaces are allowed"
                },
                s_sku: {
                    required: "sub-sku is required.",
                    noSpace: "No spaces are allowed"
                },
                date: {
                    required: "Please enter a date.",
                    // dateDDMMYYYY: "Invalid date format. Please use dd/mm/yyyy."
                },
                location: {
                    required: "Please enter a location.",
                    singleSpace: "Only one space is allowed between words"
                },
                user_id: {
                    required: "user id is required.",
                    noSpace: "No spaces are allowed"
                },
                dob: {
                    required: "Please enter the date of birth.",
                    date: "Please enter a valid date."
                },
                doj: {
                    required: "Please enter the date of joining.",
                    date: "Please enter a valid date.",
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

        $('.select2').select2();
        $('.select2').on('change', function() {
            $(this).valid();
        });
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        $(document).on('click', '.brand_delete_btn', function(e) {
    e.preventDefault();
    let deleteUrl = $(this).attr('data-id');

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
    });

    swalWithBootstrapButtons.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        swalWithBootstrapButtons.fire({
                            title: "Deleted!",
                            text: "Your file has been deleted.",
                            icon: "success"
                        });
                        $("#" + response.tr).slideUp('slow');
                    } else {
                        swalWithBootstrapButtons.fire({
                            title: "Failed!",
                            text: response.message,
                            icon: "error"
                        });
                    }
                },
                error: function() {
                    swalWithBootstrapButtons.fire({
                        title: "Error!",
                        text: "An error occurred while trying to delete the item.",
                        icon: "error"
                    });
                }
            });

        } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire({
                title: "Cancelled",
                text: "Your imaginary file is safe :)",
                icon: "error"
            });
        }
    });
});

    });
</script>
@yield('script')

</html>
