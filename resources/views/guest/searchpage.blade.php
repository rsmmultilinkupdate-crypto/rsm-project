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
  <!-- Breadcrumbs-->
        <div class="container">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Our Product</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row" bis_skin_checked="1">
            <h2 class="collectuion-title"></h2>
        </div>
      <!-- product catalog-->
      <section class="section section-lg bg-gray-lighter text-center search_page">

 
        <div class="container">
          <!-- <h3>Our Products</h3> -->
         <!--  <div class="divider divider-default"></div> -->
         @if(count($search) < 1 )
          <div class="col-md-12">
              <h3>No Product found.</h3>
          </div>
          @endif
          <div class="row">

            @foreach ($search as $sub)
            <div class="col-lg-3 col-sm-6 col-xs-12">
                  <div class="prodcut-box">
                <!-- <div class="product-counter-inner novi-background">
                  <h5 class="title">{{ $sub->title }}</h5>
                </div> -->
                <div class="product-image"><a href="{{ url('') }}/{!! !empty($sub->name) ? 'product/catproducts' : 'product/viewdetail' !!}/{{ $sub->slug}}"><img src="{{asset('storage/'.$sub->image.'')}}" alt="{{ $sub->title }}"/></a></div>
                <div class="product-tittle">
                  <a href="{{ url('') }}/{!! !empty($sub->name) ? 'product/catproducts' : 'product/viewdetail' !!}/{{ $sub->slug}}">{{ $sub->title }}</a>
                </div>
             </div>
            </div>
             @endforeach
          </div>
          {{ $search->links() }}
        </div>
      </section>
</section>
@endsection