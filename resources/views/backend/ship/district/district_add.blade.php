@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Add District</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Add District</li>
                    </ol>
                </nav>
            </div>

        </div>
        <!--end breadcrumb-->
        <div class="container">
            <div class="main-body">
                <div class="row">

                    <div class="col-lg-10">
                        <div class="card">
                            <div class="card-body">

                    <form id="myForm" action="{{ route('store.district')}}" method="post" >
                        @csrf

                       
						 <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">State Name</h6>
                            </div>
                            <div class="form-group col-sm-9 text-secondary">
                                
                            <select name="state_id" class="form-select" id="inputProductType">
								<option></option>
								@foreach($state as $state)
								<option value="{{ $state->id }}">{{ $state->state_name }}</option>
								 @endforeach
							</select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">District Name</h6>
                            </div>
                            <div class="form-group col-sm-9 text-secondary">
                                <input type="text" name="districts_name" class="form-control">
                                 
                            </div>
                        </div>
                    

                        <div class="row">
                            <div class="col-sm-3"></div>
                            <div class=" col-sm-9 text-secondary">
                                <input type="submit" class="btn btn-primary px-4" value="Save Changes" />
                            </div>
                        </div>
                    </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
{{-- Validation  --}}
<script type="text/javascript">
    $(document).ready(function (){
        $('#myForm').validate({
            rules: {
                division_id: {
                    required : true,
                },districts_name: {
                    required : true,
                },
                
            },
            messages :{
                division_id: {
                    required : 'Please Enter Division Name',
                },districts_name: {
                    required : 'Please Enter District Name',
                },
                
            },
            errorElement : 'span', 
            errorPlacement: function (error,element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight : function(element, errorClass, validClass){
                $(element).addClass('is-invalid');
            },
            unhighlight : function(element, errorClass, validClass){
                $(element).removeClass('is-invalid');
            },
        });
    });
    
</script>
{{-- Validation End  --}}






@endsection
