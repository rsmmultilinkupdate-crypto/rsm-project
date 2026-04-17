@extends('layouts.newheader')

@section('pageTitle', $meta_title)

@section('pageDescription', $meta_description)

@section('pageKeyword', $meta_keywords)

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
    <div class="row" id="checkid">
        <div class="col" id="<?php echo $meta_title; ?>">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('product/categorylist') }}">Product</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{$cat_name}}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <h2 class="collectuion-title">{{$cat_name}}</h2>
    </div>

   
           <div class="row">
                @foreach ($cat_list as $sub)
               <div class="col-lg-3 col-sm-6 col-xs-12">
                  <div class="prodcut-box">
                     <div class="product-image"><a href="{{ url('') }}/{!! !empty($sub->name) ? 'product/catproducts' : 'product/viewdetail' !!}/{{ $sub->slug}}"><img src="{{asset('storage/'.$sub->image.'')}}" alt="{!! !empty($sub->name) ? $sub->name : $sub->title !!}" /></a></div>
                    <div class="product-tittle">
                      <a href="{{ url('') }}/{!! !empty($sub->name) ? 'product/catproducts' : 'product/viewdetail' !!}/{{ $sub->slug}}">{!! !empty($sub->name) ? $sub->name : $sub->title !!}</a>
                    </div>
                  </div>
                </div>
                @endforeach
            </div>

</div>
</section>
@endsection

