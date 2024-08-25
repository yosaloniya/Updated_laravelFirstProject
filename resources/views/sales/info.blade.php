@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card mx-auto sales-form">
            <div class="card">
                <div class="card-body main-row-alert">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title text-dark">New Order</h4>
                        </div>
                        <div class="col-4 text-right">
                            <button class="btn btn-sm btn-light" onclick="history.back()">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    @include('common.alert')
                    <hr class="bg-warning">
                    <form class="forms-sample create-user pt-2">
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="order_no">Order Number :</label>
                                <input type="text" value="<?php echo rand(1000000000, 99999999999); ?>" class="form-control order_no"
                                    name="order_no" placeholder="Enter Order No." id="order_no" required>
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="date">Date :</label>
                                <input type="text" class="form-control" name="date" placeholder="Enter Date"
                                    id="date">
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="customerName">Customer Name :</label>
                                <select class="form-control productselect customer_id" name="customer_id" id="customerName">
                                    <option value="" selected disabled>Choose Customer</option>
                                    @foreach ($customer as $cust)
                                        <option value="{{ $cust->id }}">{{ $cust->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="chooseproduct">
                            <div class="row productdata" id="">
                                <div class="form-group col-sm-3">
                                    <label for="skudata">Choose SKU :</label>
                                    <select class="form-control sku-select productselect sku-select" name="sku"
                                        id="skudata">
                                        <option value="" selected disabled>Select SKU</option>
                                        @foreach ($product as $pr)
                                            <option value="{{ $pr->id }}" data-name="{{ $pr->name }}"
                                                data-price="{{ $pr->t_price }}">{{ $pr->sku }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group asd col-sm-3">
                                    <label for="exampleInputPassword1">Choose Sub_SKU :</label>
                                    <select class="form-control sub-sku productselect subsku-select" name="sub_sku">
                                        <option value="" selected disabled>Select Sub_SKU</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-3 d-none">
                                    <label for="productName">Product Name :</label>
                                    <input type="text" class="form-control product-name" name="product_id" data-p_id=""
                                        placeholder="Enter Product Name" required disabled>
                                </div>
                                <div class="form-group col-sm-4">
                                    <div class="row">
                                        <div class="col-sm-6 location-div">
                                            <label for="location">Location :</label>
                                            <p class="form-control location location-box" id="location"></p>
                                        </div>
                                        <div class="col-sm-6 location-div">
                                            <label for="exampleInputPassword1">Total Stock :</label>
                                            <p class="form-control stock stock-box" data-qty="null" data-id="null"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-2">
                                    <label for="size">Size(mtr.) :</label>
                                    <div class="row">
                                        <div class="col-sm-12"><input type="number" class="form-control qty size-value"
                                                name="qty" placeholder="Enter Size" id="size"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        <div class="form-group col-sm-12">
                                    <label for="exampleInputPassword1">Description :</label>
                                    <input type="text" class="form-control desc-description" name="description"
                                        placeholder="Description">
                                </div>
                                </div>
                        <div class="addbtn">
                            <a class="addData btn btn-sm btn-warning"><i class="fa fa-plus"></i>Add More</a>
                        </div>

                        <button class="btn btn-primary mr-2 mt-5 addselasdata submitForm" type="submit">Add</button>
                        <a href="{{ url('orders') }}" class="btn btn-light mt-5">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
@endsection
@section('script')

    <script>
        function remove_section(element) {
    let el = $(element).closest(".productdata");
    el.prev(".hr-line").remove();
    $(element).closest(".productdata").remove();
}
        $(document).ready(function() {

            function calculatePrice() {
                let tPrice = 0;
                let qtytags = document.querySelectorAll(".qty");
                let pricetags = document.querySelectorAll(".tprice");
                let size = qtytags.length;
                for (let i = 0; i < size; i++) {
                    let qty = +qtytags[i].value || 0;
                    let price = +pricetags[i].value || 0;
                    tPrice += qty * price;
                }
                $(".total_price").val(tPrice);
            }
            var today = new Date();
            var day = String(today.getDate()).padStart(2, '0');
            var month = String(today.getMonth() + 1).padStart(2, '0'); // Months are zero-based
            var year = today.getFullYear();
            var formattedDate = day + '/' + month + '/' + year;
            document.getElementById('date').value = formattedDate;

            function select() {
                $('.productselect').select2({});
            }
            select();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $(document).ready(function() {

                $(document).on('keyup', '.size-value', function() {
                    let sizeInput = $(this);
                    let size = +sizeInput.val();
                    let TotalQty = +sizeInput.closest('.productdata').find('.stock-box').text();
                    if (size > TotalQty) {
                        sizeInput.addClass('is-invalid');
                    } else {
                        sizeInput.removeClass('is-invalid')
                    }
                });

                $(document).on("change", ".sku-select", function() {
                    let id = $(this).val();
                    if (id != 0) {
                        let vrb = $(this);
                        $(this).parent().siblings().children('.sub-sku').empty();
                        let url = "{{ url('product/subsku') }}";
                        $.ajax({
                            url: url,
                            method: 'POST',
                            data: {
                                id: id,
                            },
                            success: function(data) {
                                if (data) {
                                    if (data == 201) {
                                        let selectedOption = vrb.find(
                                            "option:selected");
                                        let val = selectedOption.data("name");
                                        let price = selectedOption.data("price");
                                        vrb.parent().siblings().children(
                                            '.product-name').val(val);
                                        // vrb.parent().siblings().children('.product-name').data("p_id", p_id);
                                        vrb.parent().siblings().children('.tprice').val(
                                            price);
                                        let optd =
                                            "<option value='Not Found'  selected >Not Found</option>";
                                        vrb.parent().siblings().children('.sub-sku')
                                            .append(
                                                optd);
                                    } else {
                                        let optd =
                                            "<option value='0'  selected disabled>Select Sub_SKU</option>";
                                        vrb.parent().siblings().children('.sub-sku')
                                            .append(optd);
                                        console.log($(this).parent().next().find(
                                            'select'));
                                        data.forEach(sub => {
                                            let opt = "<option value=" + sub
                                                .id +
                                                " data-pname=" + sub.name +
                                                " data-price=" +
                                                sub
                                                .t_price + " data-p_id=" + sub
                                                .id +
                                                " style='display:flex; justify-content: 'space-between''>" +
                                                sub.sku + " - (" + sub.qty +
                                                ")</option>";
                                            vrb.parent().siblings().children(
                                                    '.sub-sku')
                                                .append(
                                                    opt);
                                        });
                                    }
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });
                    }

                });


                $('.total_payment').on("keyup", function() {
                    let totalPayment = +$(this).val();
                    let price = +$(".total_price").val();
                    if (totalPayment <= price) {
                        $(".due_payment").val(price - totalPayment);
                    } else {
                        $(".total_payment").val(price);
                        $(".due_payment").val(0);
                    }

                });

                $(document).on('change', ".sub-sku", function() {
                    let selectedOption = $(this).find("option:selected");
                    let selectTag = $(this);
                    let val = selectedOption.data("pname");
                    if (!val) {
                        let url = "{{ url('product/getPrice') }}";
                        let id = selectTag.parent().siblings().children(".sku-select").val();
                        $.ajax({
                            url: url,
                            method: 'POST',
                            data: {
                                id: id,
                            },
                            success: function(data) {
                                console.log(data)
                                if (data) {
                                    console.log("sdfgh");
                                    selectTag.parent().siblings().children(
                                        ".product-name").val(data
                                        .name);
                                    selectTag.parent().siblings().children(".tprice")
                                        .val(data
                                            .t_price);
                                }
                                // d.closest('.productdata').find('.tprice').val(data);
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });
                    } else {

                        let price = selectedOption.data("price");
                        let qty = selectedOption.data("qty");
                        let p_id = selectedOption.data("p_id");
                        $(this).parent().siblings().children('.product-name').val(val);
                        $(this).parent().siblings().children('.product-name').data("p_id", p_id);
                        $(this).parent().siblings().children('.tprice').val(price);
                    }

                });
                       // Disable submit button after click
            function disableSubmitButton() {
                $('.submitForm').prop('disabled', true);
            }
            
            // Enable submit button
            function enableSubmitButton() {
                $('.submitForm').prop('disabled', false);
            }
            
             // Call enableSubmitButton on any input change
            $(document).on('input change', 'input, select', function() {
                enableSubmitButton();
            });
                
                $('form').submit(function(e) {
                    e.preventDefault();
                    disableSubmitButton();
                    if ($(this).valid()) {
                        let dateValue = $('#date').val();
                        let parts = dateValue.split('/');
                        if (parts.length === 3) {
                            var formattedForSubmission = parts[2] + '-' + parts[1] + '-' + parts[0];
                            $('<input>').attr({
                                type: 'hidden',
                                name: 'date_formatted',
                                value: formattedForSubmission
                            }).appendTo(this);
                        } else {
                            alert('Invalid date format. Please use dd/mm/yyyy.');
                            return false; // Prevent form submission if the date format is incorrect
                            enableSubmitButton();
                        }
                        let order_no = $('.order_no').val();
                        let customer_id = $('.customer_id').val();
                        let description = $('.desc-description').val();
                        let payment_status = $('.payment_status').val();
                        let total_payment = $('.total_payment').val();
                        let due_payment = $('.due_payment').val();
                        let status = $('.status').val();
                        let total_price = $('.total_price').val();
                        let product = [];
                        $('.productdata:last-child .productselect').change(function() {
                            var id = $(this).val();
                            let url = "{{ url('product/price') }}";
                            let d = $(this);
                            $.ajax({
                                url: url,
                                method: 'POST',
                                data: {
                                    id: id,
                                },
                                success: function(data) {
                                    d.closest('.productdata').find('.tprice')
                                        .val(
                                            data);
                                },
                                error: function(xhr, status, error) {
                                    console.error(xhr.responseText);
                                    enableSubmitButton();
                                }
                            });
                        });
                        // Loop through each product
                        var qty = 0;
                        $('.productdata').each(function() {
                            let sku = $(this).find('.sku-select').val();
                            let subSku = $(this).find('.sub-sku').val();
                            let p_id = $(this).find('.product-name').data("p_id");
                            let price = $(this).find('.tprice').val();
                            qty = $(this).find('.qty').val();

                            // Add product data to array
                            product.push({
                                sku: sku,
                                sub_sku: subSku,
                                p_id: p_id,
                                price: price,
                                qty: qty
                            });
                        });
                        if (qty == 0) {
                            alert("Size cannot be 0");
                            enableSubmitButton();
                        } else if (!customer_id) {
                            alert("Select a customer.");
                            enableSubmitButton();

                        } else {

                            // AJAX request
                            $.ajax({

                                url: "{{ url('orders/save') }}",
                                method: 'POST',
                                // headers: {

                                //     'Content-Type': 'application/json' // Set the Content-Type header here
                                // },

                                data: {
                                    order_no: order_no,
                                    customer_id: customer_id,
                                    description: description,
                                    // payment_status: payment_status,
                                    // total_payment: total_payment,
                                    // due_payment: due_payment,
                                    product: product,
                                    date: formattedForSubmission,
                                    // total_price: total_price
                                },
                                success: function(data) {
                                    if (data == "success") {

                                        let errorMessage = `
                            <div class="alert alert-success fade-in alert-dismissible alertmsgnew" role="alert">\n\
                                <button type="button" class="close" data-dismiss="alert">\n\
                                    <i class="fa fa-times"></i>\n\
                                </button>\n\
                                <strong>Success ! </strong> Order Successfully completed.\n\
                                </div>`;
                                        $(".main-row-alert").prepend(errorMessage);
                                        setTimeout(() => {
                                            $('.alertmsgnew').fadeOut('slow');
                                            window.location.href = "/orders";
                                        }, 1500);
                                    } else {

                                        let errorMessage = `
                                <div class="alert alert-danger fade-in alert-dismissible alertmsgnew" role="alert">
                                    <button type="button" class="close" data-dismiss="alert">
                                        <i class="fa fa-times"></i>
                                        </button>
                                <strong>Error ! </strong> Order not Completed!
                            </div>`;
                                        $(".main-row-alert").prepend(errorMessage);
                                        setTimeout(() => {
                                            $('.alertmsgnew').fadeOut('slow');
                                        }, 300000);
                                        enableSubmitButton();
                                    }

                                    // Handle success

                                },
                                error: function(xhr, status, error) {
                                    // Handle errors
                                    console.error(xhr.responseText);
                                }
                            });
                        }
                    }
                });

                // Add click event for adding more product fields
                $('.addData').click(function() {
                    // addKeyUpCode();

                    let str =
                        '<hr class="bg-secondary hr-line mt-3 mb-4"> <div class="row productdata"> <div class="form-group col-sm-3"> <label for="exampleInputPassword1">Choose SKU :</label>\n\
                                                                                                <select class="form-control sku-select productselect sku-select" name="sku" required><option value="" selected disabled>Select SKU</option>\n\
                                                                                                    @foreach ($product as $pr) <option value="{{ $pr->id }}" data-name="{{ $pr->name }}"\n\
                                                                                                data-price="{{ $pr->t_price }}">{{ $pr->sku }}</option> @endforeach </select> </div>\n\
                                                                                            <div class="form-group col-sm-3"> <label for="exampleInputPassword1">Choose Sub_SKU :</label>\n\
                                                                                                <select class="form-control sub-sku productselect subsku-select" name="sub_sku" required> <option value="" selected disabled>Select Sub_SKU</option>\n\
                                                                                                </select> </div>  <div class="form-group col-sm-3 d-none">\n\
                                                                            <label for="exampleInputPassword1">Product Name :</label>\n\
                                                                            <input type="text" class="form-control product-name" name="product_id" data-p_id=""\n\
                                                                                placeholder="Enter Product Name" required disabled>\n\
                                                                        </div><div class="form-group col-sm-4">\n\
                                                                             <div class="row">\n\
                                                                                 <div class="col-sm-6 location-div">\n\
                                                                                   <label for="exampleInputPassword1">Location :</label>\n\
                                                                                  <p class="form-control location-box"></p>\n\
                                                                                 </div>\n\
                                                                                 <div class="col-sm-6 location-div">\n\
                                                                                   <label for="exampleInputPassword1">Total Stock :</label>\n\
                                                                                  <p class="form-control stock-box" data-qty="null" data-id="null"></p>\n\
                                                                                 </div>\n\
                                                                             </div>\n\
                                                                        </div><div class="form-group col-sm-2"><label for="exampleInputPassword1">Size(mtr.) :</label>\n\
                                                                                    <div class="row"><div class="col-sm-10"><input type="number" class="form-control qty size-value" \n\
                                                                                        name="qty" placeholder="Enter Size"></div><div class="col-sm-2"><a class="btn btn-sm btn-light" \n\
                                                                                            onClick="remove_section(this)"><i class="fa fa-close"></i></a></div></div></div></div>';
                    $('.chooseproduct').append(str);
                    select(); // Reinitialize select2
                    $('.create-user').validate().form();
                    // Bind change event for new product select
                    $('.productdata:last-child .productselect').change(function() {
                        var id = $(this).val();
                        let url = "{{ url('product/price') }}";
                        let d = $(this);
                        $.ajax({
                            url: url,
                            method: 'POST',
                            data: {
                                id: id,
                            },
                            success: function(data) {
                                d.closest('.productdata').find('.tprice').val(
                                    data);
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });
                    });

                });
            });
            setTimeout(() => {
                $('.alertmsgnew').fadeOut('fast');
            }, 4000);
            // Function to remove product section
           

            $(document).on('change', '.productdata .subsku-select', function() {
                let element = $(this);
                var id = $(this).val();
                let url = "{{ url('product/price') }}";
                let d = $(this);
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        id: id,
                    },
                    success: function(data) {

                        let main_sku = d.closest('.productdata').find('.sku-select').val();
                        let alreadySaleQty = +d.closest('.productdata').find('.qty').val();
                        let stock_id = main_sku + '-' + id;
                        let qty_id = main_sku + '-' + id + 'qty';
                        let stockBoxes = $(`.${stock_id}`);
                        let qtyBoxes = $(`.${qty_id}`);
                        let RemainQty = data[0].qty;

                        if (stockBoxes.html()) {
                            const subSkues = $('.productdata .subsku-select');
                            const subSkuIndex = subSkues.index(element);
                            let lastSecond = stockBoxes;
                            let usedQty = qtyBoxes;
                            stockBoxes.each((inedx, stock) => {
                                if (inedx <= (subSkuIndex - 1)) {
                                    lastSecond = $(stock);
                                    usedQty = qtyBoxes.eq(inedx);
                                }
                            })

                            if (lastSecond.html() && usedQty.val()) {
                                let alreadySaleQty = usedQty.val();
                                RemainQty = +lastSecond.data('qty') - alreadySaleQty;
                            }
                        }

                        d.closest('.productdata').find('.stock-box').attr('data-qty',
                            RemainQty);
                        d.closest('.productdata').find('.stock-box').removeClass().addClass(
                            stock_id).addClass('form-control stock stock-box');
                        d.closest('.productdata').find('.qty').removeClass().addClass(qty_id)
                            .addClass('form-control qty size-value');
                        d.closest('.productdata').find('.location-box').text(data[0].location);
                        d.closest('.productdata').find('.stock-box').text(RemainQty);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
@endsection
