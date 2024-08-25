
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
        <div class="container overflow-hidden">
            <div class="row justify-content-center mt-5">
                <div class="col-md-6 text-center mb-5">
                    <h2 class="heading-section">Forgot Password</h2>
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
                                    <h3 class="mb-4">Forgot Password</h3>
                                </div>
                                
                            </div>
                            @if(Session::has('error'))
            <div class="alert alert-danger">{{Session::get('error')}}</div>
            @endif
                            
                            <form method="post" class="signin-form create-user" enctype="multipart/form-data">
                            @csrf
                                <div class="form-group mb-3">
                                    <label class="label" for="name">Email</label>
                                    <input type="text" class="form-control" placeholder="Enter Your Email" name="email">
                                    @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                                <div class="login text-right text-dark mt-1 mb-4">
                                    <a href="{{ route('login') }}">Login</a>
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