@extends('auth.layouts')
@section('content')
<style>
    a {
    -webkit-transition: .3s all ease !important;
    -o-transition: .3s all ease !important;
    transition: .3s all ease !important;
    color: #e3b04b !important;
}
a:hover {
    color: #0056b3 !important;
    text-decoration: none !important;
}
</style>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center mb-5">
                <h2 class="heading-section">Login #04</h2>
            </div>
        </div>
        @if(Session::has('error'))
        <div class="alert alert-danger">{{Session::get('error')}}</div>
        @endif
        @if(Session::has('success'))
        <div class="alert alert-success">{{Session::get('success')}}</div>
        @endif

        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-10">
                <div class="wrap d-md-flex">
                    <div class="img" style="background-image: url(auth/images/bg-1.jpg);">
                    </div>
                    <div class="login-wrap p-4 p-md-5">
                        <div class="d-flex">
                            <div class="w-100">
                                <h3 class="mb-4">Sign In</h3>
                            </div>

                        </div>

                        <form method="post" class="signin-form create-user" action="{{ route('login.custom') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="label" for="name">Email</label>
                                <input type="text" class="form-control" placeholder="Enter Your Email" name="email"
                                    required>
                                @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                            <div class="form-group mb-3">
                                <label class="label" for="password">Password</label>
                                <input type="password" class="form-control" placeholder="Enter Password" name="password"
                                    required>
                                @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <button type="submit" class="form-control btn btn-primary rounded submit px-3">Sign
                                    In</button>
                            </div>
                            <div class="form-group d-md-flex">
                                <div class="w-50 text-left">
                                    <label class="checkbox-wrap checkbox-primary mb-0">Remember Me
                                        <input type="checkbox" checked>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="w-50 text-md-right">
                                    <a href="{{ route('forgotpasspage') }}">Forgot Password</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<style>
    .displaynone{
        display:none;
    }
</style>
@push('scripts')
<script>
    $(document).ready(function(){
        setTimeout(() => {
            $('.alert').addClass('displaynone');
        }, 3000);
    })
</script>
@endpush
