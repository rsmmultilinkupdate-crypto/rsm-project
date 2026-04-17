@extends('layouts.newheader')


@section('content')
<style>
    .col-lg-3.col-sm-6.col-xs-12 {
    margin-bottom: 40PX;
}
ol.breadcrumb{
     float:left;
}
.breadcrumb li {
    font-size: 14px;
    color: #000;
    font-weight: 600;
}

</style>
<section class="py-5">
<div class="container">
    <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Product</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="container">
    
    <div class="row">

 <!--       <div class="col-12 col-sm-3">
            <div class="card bg-light mb-3">
                <div class="card-header bg-primary text-white text-uppercase"><i class="fa fa-list"></i> Categories</div>
                <ul class="list-group category_block">
                	
                    <li class="list-group-item"><a href="{{ url('product/categorylist') }}">View All</a></li>
                </ul>
            </div>
        </div>-->
        <div class="col">
        <h2 class="collectuion-title">Products</h2>
            <div class="row row-30" style="z-index: 10;">
                @foreach ($cat_general as $sub)
                <div class="col-lg-3 col-sm-6 col-xs-12">
                  <div class="prodcut-box">
                 <!--    <div class="product-counter-inner novi-background">
                      <h5 class="title">{!! !empty($sub->name) ? $sub->name : $sub->title !!}</h5>
                    </div> -->
                    <div class="product-image"><a href="{{ url('') }}/{!! !empty($sub->name) ? 'product/catproducts' : 'product/viewdetail' !!}/{{ $sub->slug}}"><img src="{{asset('storage/'.$sub->image.'')}}" alt="{!! !empty($sub->name) ? $sub->name : $sub->title !!}"/></a></div>
                    <div class="product-tittle">
                      <a href="{{ url('') }}/{!! !empty($sub->name) ? 'product/catproducts' : 'product/viewdetail' !!}/{{ $sub->slug}}">{!! !empty($sub->name) ? $sub->name : $sub->title !!}</a>
                    </div>

                 </div>
                </div>
                @endforeach
            </div>

              <h2 class="collectuion-title">PRODUCTS BY TRADE NAME</h2>
            <div class="row row-30" style="z-index: 10;">
                @foreach ($cat_trade as $sub)
                <div class="col-lg-3 col-sm-6 col-xs-12">
                  <div class="prodcut-box">
                <!--     <div class="product-counter-inner novi-background">
                      <h5 class="title">{!! !empty($sub->name) ? $sub->name : $sub->title !!}</h5>
                    </div> -->
                    <div class="product-image"><a href="{{ url('') }}/{!! !empty($sub->name) ? 'product/catproducts' : 'product/viewdetail' !!}/{{ $sub->slug}}"><img src="{{asset('storage/'.$sub->image.'')}}" alt="{!! !empty($sub->name) ? $sub->name : $sub->title !!}" /></a></div>
                    <div class="product-tittle">
                      <a href="{{ url('') }}/{!! !empty($sub->name) ? 'product/catproducts' : 'product/viewdetail' !!}/{{ $sub->slug}}">{!! !empty($sub->name) ? $sub->name : $sub->title !!}</a>
                    </div>

                  </div>
                </div>
                @endforeach
            </div>
        </div>



    </div>
</div>
</section>
@endsection