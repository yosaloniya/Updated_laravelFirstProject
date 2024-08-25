
@extends('auth.layouts')
@section('content')
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center mb-5">
                    <h2 class="heading-section">Change Password</h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12 col-lg-10">
                    <div class="wrap d-md-flex">
                    <div class="img" style="background-image: url({{asset('auth/images/bg-1.jpg')}});">
                        </div>
                        <div class="login-wrap p-4 p-md-5">
                            <div class="d-flex">
                                <div class="w-100">
                                    <h3 class="mb-4">Change Password</h3>
                                </div>
                                
                            </div>
                            
                            <form method="post" class="signin-form create-user" enctype="multipart/form-data">
                            @csrf
                                <div class="form-group mb-3">
                                    <input type="text" class="form-control" placeholder="New Password" name="password" id="password">
                                   
                                </div>
                                <div class="form-group mb-3">
                                    <input type="password" class="form-control" placeholder="Confirm Password" name="confirm_password">
                                    
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="form-control btn btn-primary rounded submit px-3">Submit</button>
                                </div>
                              
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endsection
