<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8" />
    <title>Shubham eCommerce Online Store</title>
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="description" content="" />

 
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:title" content="" />
    <meta property="og:type" content="" />
    <meta property="og:url" content="" />
    <meta property="og:image" content="" />
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('frontend/assets/imgs/theme/favicon.svg') }}" />
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/plugins/animate.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/main.css?v=5.3') }}" />
  <!-- toastr  -->
    <link rel="stylesheet"  type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" >
  <!-- toastr  -->
</head>

<body>
    <!-- Modal -->

    <!-- Quick view -->
    @include('frontend.body.quickview')
    <!-- Header  -->
    @include('frontend.body.header')

   <!-- End Header  -->


    <main class="main">
        @yield('main')
    </main>

<!-- Footer  -->
    @include('frontend.body.footer')
   
    <!-- Preloader Start -->
    <div id="preloader-active">
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-inner position-relative">
                <div class="text-center">
                    <img src="{{ asset('frontend/assets/imgs/theme/loading.gif') }}" alt="" />
                </div>
            </div>
        </div>
    </div>
    <!-- Vendor JS-->

    <script src="{{asset('frontend/assets/js/vendor/modernizr-3.6.0.min.js')}}"></script>
    <script src="{{asset('frontend/assets/js/vendor/jquery-3.6.0.min.js')}}"></script>
    <script src="{{asset('frontend/assets/js/vendor/jquery-migrate-3.3.0.min.js')}}"></script>
    <script src="{{asset('frontend/assets/js/vendor/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('frontend/assets/js/plugins/slick.js')}}"></script>
    <script src="{{asset('frontend/assets/js/plugins/jquery.syotimer.min.js')}}"></script>
    <script src="{{asset('frontend/assets/js/plugins/waypoints.js')}}"></script>
    <script src="{{asset('frontend/assets/js/plugins/wow.js')}}"></script>
    <script src="{{asset('frontend/assets/js/plugins/perfect-scrollbar.js')}}"></script>
    <script src="{{asset('frontend/assets/js/plugins/magnific-popup.js')}}"></script>
    <script src="{{asset('frontend/assets/js/plugins/select2.min.js')}}"></script>
    <script src="{{asset('frontend/assets/js/plugins/counterup.js')}}"></script>
    <script src="{{asset('frontend/assets/js/plugins/jquery.countdown.min.js')}}"></script>
    <script src="{{asset('frontend/assets/js/plugins/images-loaded.js')}}"></script>
    <script src="{{asset('frontend/assets/js/plugins/isotope.js')}}"></script>
    <script src="{{asset('frontend/assets/js/plugins/scrollup.js')}}"></script>
    <script src="{{asset('frontend/assets/js/plugins/jquery.vticker-min.js')}}"></script>
    <script src="{{asset('frontend/assets/js/plugins/jquery.theia.sticky.js')}}"></script>
    <script src="{{asset('frontend/assets/js/plugins/jquery.elevatezoom.js')}}"></script>
    <!-- Template  JS -->
    <script src="{{asset('frontend/assets/js/main.js?v=5.3')}}"></script>
    <script src="{{asset('frontend/assets/js/shop.js?v=5.3')}}"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
 @if(Session::has('message'))
 var type = "{{ Session::get('alert-type','info') }}"
 switch(type){
    case 'info':
    toastr.info(" {{ Session::get('message') }} ");
    break;

    case 'success':
    toastr.success(" {{ Session::get('message') }} ");
    break;

    case 'warning':
    toastr.warning(" {{ Session::get('message') }} ");
    break;

    case 'error':
    toastr.error(" {{ Session::get('message') }} ");
    break; 
 }
 @endif 
</script>


<!-- Sweet Alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Sweet Alert -->

<!-- STAR ADD TO CART AND PRODUCT VIEW -->
<script type="text/javascript">


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    ///Start Product View With Models

    function productView(id){
        // alert(id);
         $.ajax({
            type: 'GET',
            url: '/product/view/modal/'+id,
            dataType: 'json',
            success:function(data){
                
                $('#pname').text(data.product.product_name);
                $('#pprice').text(data.product.selling_price);
                $('#pcode').text(data.product.product_code);
                $('#pcategory').text(data.product.category.category_name);
                $('#pbrand').text(data.product.brand.brand_name);

                $('#pimage').attr('src','/'+data.product.product_thambnail);


                $('#product_id').val(id);
                $('#qty').val(1);

                // Product Price
                if(data.product.discount_price == null){
                    $('#pprice').text('');
                    $('#oldprice').text('');
                    $('#pprice').text(data.product.selling_price);
                }else{
                    $('#pprice').text(data.product.selling_price - data.product.discount_price);
                    $('#oldprice').text(data.product.selling_price);
                }// End Product Price

                // Start Product Option

                if (data.product.product_qty > 0) {
                    $('#aviable').text('');
                    $('#stockout').text('');
                    $('#aviable').text(data.product.product_qty);
                    
                }else{
                    $('#aviable').text('');
                    $('#stockout').text('');
                    $('#stockout').text('Out Of Stock');
                }// End Product Option

                /// Size 
                $('select[name="size"]').empty();
                $.each(data.size,function(key,value){
                    $('select[name="size"]').append('<option value="'+value+' ">'+value+'</option>')
                    if (data.size == "") {
                        $('#sizeArea').hide();
                    }
                })// End  Size



                /// Color 
                $('select[name="color"]').empty();
                $.each(data.color,function(key,value){
                    $('select[name="color"]').append('<option value="'+value+' ">'+value+'</option>')
                    if (data.color == "") {
                        $('#colorArea').hide();
                    }
                })// End  Color

            }
         })

     } 

    // End Product View With Modal 

    /// Start Add To Cart Prodcut 

    function addToCart(){
        
     var product_name = $('#pname').text();  
     var id = $('#product_id').val();
     var color = $('#color option:selected').text();
     var size = $('#size option:selected').text();
     var quantity = $('#qty').val(); 
     $.ajax({
        type: "POST",
        dataType : 'json',
        data:{
            color:color, size:size, quantity:quantity,product_name:product_name
        },
        url: "/cart/data/store/"+id,
        success:function(data){
            miniCart();
            $('#closeModal').click();
            // console.log(data)


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
            }else{
                Toast.fire({
                  type: 'error',
                  title: data.error,
                })
            }
            // Sweet Alert Massage End

        }



     })


    }


    /// End Add To Cart Prodcut 
</script>

<!-- STAR ADD TO CART AND PRODUCT VIEW -->

<!-- START ADD TO MINI CAR AND REAMOVIE MINI CART AND PRODUCT DEATALS -->
<script type="text/javascript">
    function miniCart() {
        $.ajax({
            url: '/product/mini/cart',
            type: 'GET',
            dataType: 'json',
            success:function(response){
                // console.log(response);

                  $('span[id="cartTotal"]').text(response.cartTotal);  
                    $('#cartQty').text(response.cartQty);

                var miniCart = ""
                $.each(response.carts , function(key,value) {
                   
                  
                    
                    miniCart += `

                    <ul>
                        <li>
                            <div class="shopping-cart-img">
                                <a href="shop-product-right.html"><img alt="Nest" src="/${value.options.image}"style="width: 50px; height: 50px;" /></a>
                            </div>
                            <div class="shopping-cart-title" style="margin: -73px 74px 14px; width:146px;" >

                                <h4><a href="shop-product-right.html">${value.name.substring(0,20)}</a></h4>
                                <h4><span>${value.qty} × </span>${value.price}</h4>
                            </div>
                            <div class="shopping-cart-delete" style="margin:-85px 1px 0px;">
                                <a type="submit" id="${value.rowId}" onclick="miniCartRemove(this.id)">
                                <i class="fi-rs-cross-small"></i>
                                </a>
                            </div>
                        </li>
                    </ul>
                    <hr><br>`
                });
                $('#miniCart').html(miniCart);


            }
        })
       
        
    };
    miniCart();


    //Mini Cart Reamove Start

    function miniCartRemove(rowId) {
        $.ajax({
            url: '/minicart/product/remove/'+rowId,
            type: 'GET',
            dataType: 'json',
            success:function(data){
                miniCart();
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
            }else{
                Toast.fire({
                  type: 'error',
                  title: data.error,
                })
            }
            // Sweet Alert Massage End



            }
        })
     
        
    }
    miniCart();
    //Mini Cart Reamove End


    //Start Add To Cart Details

     function addToCartDetails(){
        
     var product_name = $('#dpname').text();  
     var id = $('#dproduct_id').val();
     var color = $('#dcolor option:selected').text();
     var size = $('#dsize option:selected').text();
     var quantity = $('#dqty').val(); 
     $.ajax({
        type: "POST",
        dataType : 'json',
        data:{
            color:color, size:size, quantity:quantity,product_name:product_name
        },
        url: "/dcart/data/store/"+id,
        success:function(data){
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
            }else{
                Toast.fire({
                  type: 'error',
                  title: data.error,
                })
            }
            // Sweet Alert Massage End

        }



     })


    }

    //End Add To Cart Details
</script>
<!-- END ADD TO MINI CAR AND REAMOVIE MINI CART AND PRODUCT DEATALS -->


<!-- START WISH LIST -->
<script type="text/javascript">
    function addToWishList(product_id) {
        $.ajax({
            url: '/add-to-wishlist/'+product_id,
            type: 'POST',
            dataType: 'json',
            success:function(data){
                wishlist()
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
                icon: 'error',
                  type: 'error',
                  title: data.error,
                })
            }
            // Sweet Alert Massage End
            }
        }).fail(function(){
            Swal.fire({
                toast: true,
              position: 'top-end',
              icon: 'error',
              title: 'At First Login Your Account',
              showConfirmButton: false,
              timer: 3000
            })
        })
        
        
    }
</script>
<!-- END WISH LIST -->

<!-- START LOAD WHISH LIST  DATA  -->
<script type="text/javascript">
    function wishlist() {
        $.ajax({
            url: '/get-wishlist-product/',
            type: 'GET',
            dataType: 'json', 
            success:function(response){
                $('#wishlistQty').text(response.wishqty)
            var rows = "";
            $.each(response.wishlist, function(key, val) {
                 /* iterate through array or object */

                rows+= 
            `
             <tr class="pt-30">
                                    
                <td class="image product-thumbnail pt-40"><img src="/${val.product.product_thambnail}" alt="#" /></td>
                <td class="product-des product-name">
                    <h6><a class="product-name mb-10" href="shop-product-right.html">${val.product.product_name}</a></h6>
                    <div class="product-rate-cover">
                        <div class="product-rate d-inline-block">
                            <div class="product-rating" style="width: 90%"></div>
                        </div>
                        <span class="font-small ml-5 text-muted"> (4.0)</span>
                    </div>
                </td>
                <td class="price" data-title="Price">
                 ${val.product.discount_price == null
                 ?
                 `<h3 class="text-brand">$${val.product.selling_price}</h3>`
                 :
                 `<h3 class="text-brand">$${val.product.selling_price - val.product.discount_price}</h3>`
                 }
                    
                </td>
                <td class="text-center detail-info" data-title="Stock">
                 ${val.product.product_qty == null
                 ?
                 `<span class="stock-status out-stock mb-0"> Out Stock </span>`
                 :
                 `<span class="stock-status in-stock mb-0"> In Stock </span>`
                 }
                    
                <td class="action text-center" data-title="Remove">
                    <a type="submit" id="${val.id}" onclick="wishListRemove(this.id)"  class="text-body"><i class="fi-rs-trash"></i></a>
                </td>
                </tr>
            `;


            }); // END EACH
             $('#wishlist_data').html(rows);

            
            }
        })    
    }

    // CALL getWishListData FUNCTION
    wishlist()
    // <!-- END LOAD WHISH LIST DATA -->

    // Remove Wishlist
function wishListRemove(id){

        $.ajax({
            url: "/wishlist-remove/"+id,
            type: "GET",
            dataType: 'json', 
            success:function(data){
             
                wishlist();
               
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




<!-- START PEODUCT COMPERE  -->
<script type="text/javascript">
    function addToCompare(compare_id) {
        $.ajax({
            url: '/add-to-compare/'+compare_id,
            type: 'POST',
            dataType: 'json',
            success:function(data){

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
<!-- END PEODUCT COMPERE  -->
</body>

</html> 
