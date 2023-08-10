@extends('frontend.master_dashboard')
@section('main')
<div class="page-header breadcrumb-wrap">
<div class="container">
    <div class="breadcrumb">
        <a href="index.html" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>

        <span></span> Cart
    </div>
</div>
</div>
<div class="container mb-80 mt-50">
<div class="row">
    <div class="col-lg-8 mb-40">
        <h1 class="heading-2 mb-10">Your Cart</h1>
        <div class="d-flex justify-content-between">
            <h4 class="text-body">There are <span class="text-brand" id="myCartQty">3</span> products in your cart
            </h4>

        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive shopping-summery">
            <table class="table table-wishlist">
                <thead>
                    <tr class="main-heading">
                        <th class="custome-checkbox start pl-30">

                        </th>
                        <th scope="col" colspan="2">Product</th>
                        <th scope="col">Unit Price</th>
                        <th scope="col">Colore</th>
                        <th scope="col">Size</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Subtotal</th>
                        <th scope="col" class="end">Remove</th>
                    </tr>
                </thead>
                <tbody id="cartPage">
                    <!-- Load Data From BackEnd Api -->
                    <!-- Load Data From BackEnd Api -->
                </tbody>
            </table>
        </div>
        <div class="row mt-50">

            <div class="col-lg-5">
            @if (Session::has('coupon'))

                @else

                    <div class="p-40" id="couponField">
                        <h4 class="mb-10">Apply Coupon</h4>
                        <p class="mb-30"><span class="font-lg text-muted">Using A Promo Code?</p>
                        <form action="#">
                            <div class="d-flex justify-content-between">
                                <input class="font-medium mr-15 coupon" id="coupon_name"
                                    placeholder="Enter Your Coupon">
                                <a type="submit" onclick="applyCoupon()" class="btn"><i
                                        class="fi-rs-label mr-10"></i>Apply</a>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>




                <div class="col-lg-7">
                    <div class="divider-2 mb-30"></div>
                    <div class="border p-md-4 cart-totals ml-30">
                        <div class="table-responsive">
                            <table class="table no-border">
                                <tbody id="couponCalFiels">

                                </tbody>
                            </table>
                        </div>
                        <a href="{{ route('checkout')}}" class="btn mb-20 w-100">Proceed To CheckOut<i
                                class="fi-rs-sign-out ml-15"></i></a>
                    </div>
                </div>



            </div>
        </div>

    </div>

</div>
@endsection
@section('script')
<script type="text/javascript">
function applyCoupon() {
    var coupon_name = $('#coupon_name').val();

    $.ajax({
        type: "POST",
        url: "/coupon-apply",
        data: {
            coupon_name: coupon_name
        },
        dataType: "json",
        success: function(data) {
            couponCalculation();

            if (data.validity == true) {
                $('#couponField').hide();
            }

            // Sweet Alert Massage
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                icon: 'success',
                showConfirmButton: false,
                timer: 3000
            });

            if ($.isEmptyObject(data.error)) {
                Toast.fire({
                    type: 'success',
                    title: data.success,
                })
            } else {
                Toast.fire({
                    icon: 'error',
                    type: 'error',
                    title: data.error,
                })
            }
            // Sweet Alert Massage End
        }
    });
};


function couponCalculation() {
    
  $.ajax({
        type: "GET",
        url: "/coupon-calculation",
        dataType: "json",
        success: function (data) {
            
            if (data.total) {
             $('#couponCalFiels').html(
                `
                <tr>
                    <td class="cart_total_label">
                        <h6 class="text-muted">Subtotal</h6>
                    </td>
                    <td class="cart_total_amount">
                        <h4 class="text-brand text-end">₹${data.total}</h4>
                    </td>
                </tr>

                <tr>
                    <td class="cart_total_label">
                        <h6 class="text-muted">Total</h6>
                    </td>
                    <td class="cart_total_amount">
                        <h4 class="text-brand text-end">₹${data.total}</h4>
                    </td>
                </tr>
                `
             );
            }else{
                $('#couponCalFiels').html(
                `
                <tr>
                    <td class="cart_total_label">
                        <h6 class="text-muted">Subtotal</h6>
                    </td>
                    <td class="cart_total_amount">
                        <h4 class="text-brand text-end">₹${data.subtotal}</h4>
                    </td>
                </tr>
                <tr>
                    <td class="cart_total_label">
                        <h6 class="text-muted">Coupon</h6>
                    </td>
                    <td class="cart_total_amount">
                        <h4 class="text-brand text-end">${data.coupon_name}<a type='submit' onclick='couponRemove()' class='ml-5'><i class='fi-rs-trash'></i></a></h4>
                    </td>
                </tr>
                <tr>
                    <td class="cart_total_label">
                        <h6 class="text-muted">Coupon Discount</h6>
                    </td>
                    <td class="cart_total_amount">
                        <h4 class="text-brand text-end">${data.coupon_discount}%</h4>
                    </td>
                </tr>
           

                <tr>
                    <td class="cart_total_label">
                        <h6 class="text-muted">Total</h6>
                    </td>
                    <td class="cart_total_amount">
                        <h4 class="text-brand text-end">₹${data.total_amount}</h4>
                    </td>
                </tr>
                `
             );

            }
        }
    });
}
couponCalculation();

function couponRemove() {
        
        $.ajax({
            url: "/coupon-remove",
            type: "GET",
            dataType: 'json',
            success:function(data){
                couponCalculation();
                $('#couponField').show();
             
               

            // Sweet Alert Massage
            const Toast = Swal.mixin({
                toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 3000
            });

            if ($.isEmptyObject(data.error)) {
                Toast.fire({
                  type: 'success',
                  icon: 'success',
                  title: data.success,
                })
            }else{
                Toast.fire({
                  type: 'error',
                  icon: 'error',
                  title: data.error,
                })
            }
            // Sweet Alert Massage End
            }
        })
    }

</script>
@endsection
