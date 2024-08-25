@extends('layout.app')
<!--<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">-->
@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-dark">History</h1>

    <!-- DataTables Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row align-items-center">
                <div class="col-1">
                    <button class="btn btn-sm py-2 btn-secondary" onclick="history.back()">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i> Back
                    </button>
                </div>
                <div class="col-3 d-flex justify-content-center">
                    <input type="text" id="start-date" class="form-control form-control-sm mr-2"
                        placeholder="Start Date" value="<?php echo $selected['start_date']??''; ?>">
                    <span class="align-self-center text-dark">-</span>
                    <input type="text" id="end-date" class="form-control form-control-sm ml-2" placeholder="End Date" value="<?php echo $selected['end_date']??''; ?>">
                </div>
                <div class="col-1">
                    <button class="btn btn-sm btn-secondary apply">
                        Apply
                    </button>
                </div>
                <div class="col-1"></div>
                <div class="col-2">
                    <select name="user" id="user" class="form-control form-control-sm">
                        <option value=""selected disabled>User</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->user_id }}" <?php echo $selected['user']==$user->user_id?'selected':'' ?>>{{ $user->user_id }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-2">
                    <select name="type" id="type" class="form-control form-control-sm">
                        <option value="0" selected disabled>Type</option>
                        <option value="1" <?php echo $selected['type']==1?'selected':'' ?>>Add</option>
                        <option value="2" <?php echo $selected['type']==2?'selected':'' ?>>Edit</option>
                        <option value="3" <?php echo $selected['type']==3?'selected':'' ?>>Delete</option>
                    </select>
                </div>
                <div class="col-2">
                    <select name="destination" id="destination" class="form-control form-control-sm">
                        <option value="0" selected disabled>Destination</option>
                        <option value="1" <?php echo $selected['dest']==1?'selected':'' ?>>Brand</option>
                        <option value="2" <?php echo $selected['dest']==2?'selected':'' ?>>Category</option>
                        <option value="3" <?php echo $selected['dest']==3?'selected':'' ?>>Products</option>
                        <option value="4" <?php echo $selected['dest']==4?'selected':'' ?>>Returns</option>
                        <option value="5" <?php echo $selected['dest']==5?'selected':'' ?>>Sales</option>
                        <option value="6" <?php echo $selected['dest']==6?'selected':'' ?>>Customers</option>
                        <option value="7" <?php echo $selected['dest']==7?'selected':'' ?>>Supplier-Products</option>
                        <option value="8" <?php echo $selected['dest']==8?'selected':'' ?>>Suppliers</option>
                        <option value="9" <?php echo $selected['dest']==9?'selected':'' ?>>Sub-sku</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0" data-page-length="50">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>User-Id</th>
                            <th>Item</th>
                            <th>Type</th>
                            <th>Destination</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>User-Id</th>
                            <th>Item</th>
                            <th>Type</th>
                            <th>Destination</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($data as $sub)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>
                                    <?php 
                                        if($selected['type']==1) {
                                            echo date('d-m-Y H:i:s', strtotime($sub->created_at ?? ""));
                                        } else if($selected['type']==2) {
                                            echo date('d-m-Y H:i:s', strtotime($sub->updated_at ?? ""));
                                        } else {
                                            echo date('d-m-Y H:i:s', strtotime($sub->deleted_at ?? ""));
                                        }
                                    ?>
                                </td>
                                <td>{{ $sub->user_id ?? "" }}</td>
                                <td>{{ $sub->item_detail ?? "" }}</td>
                                <td>
                                    <?php 
                                        if($selected['type']==1) {
                                            echo 'added';
                                        } else if($selected['type']==2) {
                                            echo 'edited';
                                        } else {
                                            echo 'deleted';
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                        if($selected['dest']==1) {
                                            echo 'Brand';
                                        } else if($selected['dest']==2) {
                                            echo 'Category';
                                        } else if($selected['dest']==3) {
                                            echo 'Products';
                                        } else if($selected['dest']==4) {
                                            echo 'Returns';
                                        } else if($selected['dest']==5) {
                                            echo 'Sales';
                                        } else if($selected['dest']==6) {
                                            echo 'Customers';
                                        } else if($selected['dest']==7) {
                                            echo 'Supplier products';
                                        } else if($selected['dest']==8) {
                                            echo 'Suppliers';
                                        } else if($selected['dest']==9) {
                                            echo 'Subsku';
                                        }
                                    ?>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('script')
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> --}}

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(function() {
            $("#start-date, #end-date").datepicker({
                dateFormat: "dd/mm/yy"
            });
        });


        var urlString = location.href;
        const url = new URL(urlString);
        const params = new URLSearchParams(url.search);
        const destination = params.get('destination');
        var type = params.get('type');
        const start_date = params.get('start_date');
        const end_date = params.get('end_date');

        document.getElementById('destination').addEventListener('change', function() {
            var destinationId = this.value;
            let typeId = document.querySelector('#type').value;
            if (destinationId) {
                let url = '/history?';
                url+='destination='+destinationId;
                if(type) {
                    url+='&type=' + type;
                }
                if(start_date) {
                    url+='&start_date=' + start_date;
                }
                if(end_date) {
                    url+='&end_date=' + end_date;
                }
                window.location.href = url;
            }
        });

        $(document).on('click', '.apply', function () {
            let startDate = $('#start-date').val();
            let endDate = $('#end-date').val();

            let validStartDate = validDate(startDate);
            let validEndDate = validDate(endDate);
            console.log(validEndDate, validStartDate);

            let url = '/history?';
            url+='destination='+destination;
            if(type) {
                url+='&type=' + type;
            }
            if(!validStartDate && validEndDate) {
                url+='&start_date=' + startDate;
            }
            if(validStartDate && !validEndDate) {
                url+='&end_date=' + endDate;
            }
            if(!validStartDate && !validEndDate) {
                url += '&start_date=' + startDate + '&end_date=' + endDate;
            }
            location.href = url;
        });

        function validDate(date) { 
            if(date == '') {
                return true;
            }
            let dateArr = date.split('/');
            if(dateArr.length != 3) {
                return true;
            } else{
                return false;
            }
        }

        document.getElementById('type').addEventListener('change', function() {
            var typeId = this.value;
            let destinationId = document.querySelector('#destination').value;
            let url = location.href;
            if (typeId) {
                let url = '/history?';
                url+='destination='+destinationId;
                url+='&type=' + typeId;
                if(start_date) {
                    url+='&start_date=' + start_date;
                }
                if(end_date) {
                    url+='&end_date=' + end_date;
                }
                window.location.href = url;
             
            }
        });

        document.getElementById('user').addEventListener('change', function() {
            var userId = this.value;
            let destinationId = document.querySelector('#destination').value;
            let url = '/history?';
            url+='destination='+destinationId;
            if(type) {
                url+='&type=' + type;
            }
            if(start_date) {
                url+='&start_date=' + start_date;
            }
            if(end_date) {
                url+='&end_date=' + end_date;
            }
            if(userId) {
                url+='&user=' + userId;
            }
            window.location.href = url;
        });
    </script>
@endsection