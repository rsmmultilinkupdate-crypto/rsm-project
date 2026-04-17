@extends('layouts.newheader')
@section('pageTitle', $meta_title)

@section('pageDescription', $meta_description)

@section('pageKeyword', $meta_keywords)

@section('content')

 <style>
   .easyzoom-flyout img {
    max-width: inherit;
}

    /* Popup Open button */ 
.open-button{
    color:#FFF;
    padding:10px;
    text-decoration:none;
    border-radius:3px;
}
 

.popup {
    position:fixed;
    top:0px;
    left:0px;
    background:rgba(0,0,0,0.75);
    width:100%;
    height:100%;
    display:none;
    z-index: 9;
}
 
/* Popup inner div */
.popup-content {
    width: 500px;
    margin: 0 auto;
    box-sizing: border-box;
    padding: 40px;
    margin-top: 20px;
    box-shadow: 0px 2px 6px rgba(0,0,0,1);
    border-radius: 3px;
    background: #fff;
    position: relative;
}
 
/* Popup close button */
.close-button {
    width: 25px;
    height: 25px;
    position: absolute;
    top: -10px;
    right: -10px;
    border-radius: 20px;
    background: rgba(0,0,0,0.8);
    font-size: 20px;
    text-align: center;
    color: #fff;
    text-decoration:none;
}
 
.close-button:hover {
    background: rgba(0,0,0,1);
}
 
@media screen and (max-width: 720px) {
.popup-content {
    width:90%;
    } 
}
  </style>
 <link rel="stylesheet" href="{{ asset('/css2/easyzoom.css') }}" >
<section>
    <div class="container">
		<div class="row py-5">

			<div class="col-lg-5 ">

				<div class="easyzoom easyzoom--overlay" >
				    <a href="{{asset('storage/'.$product_details->image.'')}}">
				        	<img src="{{asset('storage/'.$product_details->image.'')}}" width="640" height="360" alt="{{ $product_details->title}}" loading="lazy" >  
					</a>
				</div>
				<div class="en-btn my-3 text-center">
					<button class="enquire-btn Click-here">Enquire Now</button>
				</div>
				@include('enquire-now')
				<script src="{{ asset('js/enquire-now.js') }}"></script> 
			</div>

			<div class="col-lg-7">
				<div class="product-disp">
					<h1 class="product-name">{{ $product_details->title }}</h1>
					<div class="product-dispc">{!! $product_details->description  !!}</div>
				</div>
			</div>

		</div>
    </div>
</section>


    <script>
        $(".Click-here").on('click', function() {
		  $(".custom-model-main").addClass('model-open');
		}); 
		$(".close-btn, .bg-overlay").click(function(){
		  $(".custom-model-main").removeClass('model-open');
		});


    </script>
    

	<script src="{{ asset('/css2/easyzoom.js') }}"></script>
    <script>
		var $easyzoom = $('.easyzoom').easyZoom();
	</script>

@stop
