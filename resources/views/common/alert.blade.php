{{-- Message --}}
@if (Session::has('success'))
    <div class="alert alert-success fade-in alert-dismissible alertmsgnew" role="alert">
        <button type="button" class="close" data-dismiss="alert">
            <i class="fa fa-times"></i>
        </button>
        <strong>Success !</strong> {{ session('success') }}
    </div>
@endif

@if (Session::has('error'))
    <div class="alert alert-danger fade-in alert-dismissible alertmsgnew" role="alert">
        <button type="button" class="close" data-dismiss="alert">
            <i class="fa fa-times"></i>
        </button>
        <?php
            $errors = session('error');
            if(is_array($errors)) {
                ?>
                <ul>
                <?php
               
                foreach ($errors as $err) {
                    ?>
                       <li> <strong>Error !</strong> {{$err}} </li>
                    <?php
                }
                ?>
                </ul>
                <?php
            } else if (strpos($errors, "###")!=false) {
                $errors = explode('###', $errors);
                ?>
                <ul>
                <?php
                foreach ($errors as $err) {
                    ?>
                       <li> <strong>Error !</strong> {{$err}} </li>
                    <?php
                }
                ?>
                </ul>
                <?php
            } else {
                ?>
                <ul>
               <li> <strong>Error !</strong> {{ session('error') }} </li>
                </ul>
                <?php
            }
        ?>
    </div>
@endif
