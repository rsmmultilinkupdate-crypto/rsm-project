@extends('layouts.newheader')

@section('pageTitle', $meta_title)

@section('pageDescription', $meta_description)

@section('pageKeyword', $meta_keywords)
@section('content')
<!-- Page-->
  <div class="page container">
      <!-- Page Header-->
      <div class="section page-header novi-background bg-cover">

        <section class="breadcrumbs-custom breadcrumbs-custom-svg bg-image-custom" style="background-image: url(images/bg-breadcrumbs-01.jpg)">
          <div class="container">
            <p class="breadcrumbs-custom-subtitle"></p>
            <p class="heading-1 breadcrumbs-custom-title">{{ $pages_details->title }}</p>
        <!--     <ul class="breadcrumbs-custom-path">
              <li><a href="https://rsmmultilink.com/">Home</a></li>
              
              <li class="active">{{ $pages_details->title }}</li>
            </ul> -->
          </div>
        </section>
      </div>
      <!-- hi, we are brave-->
      <section class="section section-lg bg-default">
        <!-- section wave-->
        {!! $pages_details->description !!}
      </section>
  </div>
@endsection