<!doctype html>
<html lang="en">

<head>
    <title>Login 04</title>
    <link rel="shortcut icon" href="{{asset('img/thecozycreations.png')}}"type="image/x-icon">
    <meta charset="utf-8">
    {{-- <meta http-equiv="refresh" content="300"> --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta content="Default page" name="description">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{asset('auth/css/style.css')}}">
     <!-- <script nonce="79ed4f8a-9838-469b-85dd-12a25e4f3726">
        try {
            (function(w, d) {
                ! function(k, l, m, n) {
                    k[m] = k[m] || {};
                    k[m].executed = [];
                    k.zaraz = {
                        deferred: [],
                        listeners: []
                    };
                    k.zaraz.q = [];
                    k.zaraz._f = function(o) {
                        return async function() {
                            var p = Array.prototype.slice.call(arguments);
                            k.zaraz.q.push({
                                m: o,
                                a: p
                            })
                        }
                    };
                    for (const q of ["track", "set", "debug"]) k.zaraz[q] = k.zaraz._f(q);
                    k.zaraz.init = () => {
                        var r = l.getElementsByTagName(n)[0],
                            s = l.createElement(n),
                            t = l.getElementsByTagName("title")[0];
                        t && (k[m].t = l.getElementsByTagName("title")[0].text);
                        k[m].x = Math.random();
                        k[m].w = k.screen.width;
                        k[m].h = k.screen.height;
                        k[m].j = k.innerHeight;
                        k[m].e = k.innerWidth;
                        k[m].l = k.location.href;
                        k[m].r = l.referrer;
                        k[m].k = k.screen.colorDepth;
                        k[m].n = l.characterSet;
                        k[m].o = (new Date).getTimezoneOffset();
                        if (k.dataLayer)
                            for (const x of Object.entries(Object.entries(dataLayer).reduce(((y, z) => ({ ...y[1],
                                    ...z[1]
                                })), {}))) zaraz.set(x[0], x[1], {
                                scope: "page"
                            });
                        k[m].q = [];
                        for (; k.zaraz.q.length;) {
                            const A = k.zaraz.q.shift();
                            k[m].q.push(A)
                        }
                        s.defer = !0;
                        for (const B of [localStorage, sessionStorage]) Object.keys(B || {}).filter((D => D.startsWith("_zaraz_"))).forEach((C => {
                            try {
                                k[m]["z_" + C.slice(7)] = JSON.parse(B.getItem(C))
                            } catch {
                                k[m]["z_" + C.slice(7)] = B.getItem(C)
                            }
                        }));
                        s.referrerPolicy = "origin";
                        s.src = "/cdn-cgi/zaraz/s.js?z=" + btoa(encodeURIComponent(JSON.stringify(k[m])));
                        r.parentNode.insertBefore(s, r)
                    };
                    ["complete", "interactive"].includes(l.readyState) ? zaraz.init() : k.addEventListener("DOMContentLoaded", zaraz.init)
                }(w, d, "zarazData", "script");
            })(window, document)
        } catch (e) {
            throw fetch("/cdn-cgi/zaraz/t"), e;
        };
    </script> -->
    <style>
        body{
            overflow:;
        }
    </style>
</head>

<body>
    <section class="ftco-section">

    @yield('content')
    </section>
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}
    <script src="{{asset('auth/js/jquery.min.js')}}"></script>
    <script src="{{asset('auth/js/popper.js')}}"></script>
    <script src="{{asset('auth/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('auth/js/main.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
    <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            window.addEventListener('load', function() {
    $(".create-user").validate({
        rules: {
            name: {
                required: true,
                maxlength: 30,
            },
            email: {
                required: true,
                email: true,
                maxlength: 50
            },
            password: {
                required: true,
                minlength: 5
            },
            confirm_password: {
                required: true,
                equalTo: "#password"
            }
            
        },
        messages: {
            name: {
                required: "Name is required",
                maxlength: "Name cannot be more than 30 characters"
            },
            email: {
                required: "Email is required",
                email: "Email must be a valid email address",
                maxlength: "Email cannot be more than 30 characters",
            },
            password: {
                required: "Password is required",
                minlength: "Password must be at least 5 characters"
            },
            confirm_password: {
                required: "Confirm password is required",
                equalTo: "Password and confirm password should same"
            }
          
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
});
            </script>
    
    {{-- <script defer src="https://static.cloudflareinsights.com/beacon.min.js/v84a3a4012de94ce1a686ba8c167c359c1696973893317" integrity="sha512-euoFGowhlaLqXsPWQ48qSkBSCFs3DPRyiwVu3FjR96cMPx+Fr+gpWRhIafcHwqwCqWS42RZhIudOvEI+Ckf6MA==" data-cf-beacon='{"rayId":"86bd94d38cb284e9","version":"2024.3.0","token":"cd0b4b3a733644fc843ef0b185f98241"}' crossorigin="anonymous"></script> --}}
    @stack('scripts')
</body>

</html>