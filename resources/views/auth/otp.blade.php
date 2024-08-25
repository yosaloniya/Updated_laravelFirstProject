@extends('auth.layouts')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center mb-5">
                <h2 class="heading-section">Forgot Password</h2>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-10">
                <div class="wrap d-md-flex">
                    <div class="img" style="background-image: url({{ asset('auth/images/bg-1.jpg') }});">
                    </div>
                    <div class="login-wrap p-4 p-md-5">
                        <div class="d-flex">
                            <div class="w-100">
                                <h3 class="mb-4">Forgot Password</h3>
                            </div>

                        </div>
                        @if (Session::has('success'))
                            <div class="alert alert-success">{{ Session::get('success') }}</div>
                        @endif
                        @if (Session::has('error'))
                            <div class="alert alert-danger">{{ Session::get('error') }}</div>
                        @endif
                        <form method="post" class="signin-form" action="#">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="label" for="name">OTP</label>
                                <input type="number" class="form-control" placeholder="Enter OTP" name="otp"
                                    id="numberInput" oninput="limitLength(this, 4)">

                            </div>
                            <div class="login text-right text-dark mt-1 mb-4">
                                <a href="{{ route('resend-otp', ['id' => $userId]) }}">Resend OTP</a>
                            </div>
                            <div class="form-group">
                                <button type="submit"
                                    class="form-control btn btn-primary rounded submit px-3">Submit</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<style>
    .displaynone {
        display: none;
    }
</style>
@push('scripts')
    <script>
        // Reload the page after 60 seconds (1000 milliseconds * 60 seconds)
        setTimeout(function() {
            location.reload();
        }, 300000); // 60,000 milliseconds = 1 minute

        $(document).ready(function() {
            setTimeout(() => {
                $('.alert').addClass('displaynone');
            }, 3000);
        })

        function limitLength(element, maxLength) {
            if (element.value.length > maxLength) {
                element.value = element.value.slice(0, maxLength); // Truncate the input value
            }
        }
    </script>
@endpush
