@extends('layouts.newheader')

@section('pageTitle', $blog->title.' - '.app('global_settings')[0]['setting_value'])
@section('pageDescription', $blog->excerpt)
@section('pageImage', asset('storage/'.$blog->image) )

@section('content')

    <section class="py-5">
      <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="content-block">
                  <img src="{{asset('storage/'.$blog->image.'')}}" alt="{{ $blog->title }}"  class="w-100" /> 
                    <div class="below-cont">
                        <h2>{{ $blog->title }}</h2>
                  <ul class="post-blog">
                    <li>
                      <p class="icon">
                        by
                        <a href="#">Admin</a>
                      </p>
                    </li>
      
                    <li>
                      <p>{{ date('F d, Y h:i A', strtotime($blog->created_at)) }}</p>
                    </li>
                  </ul>
      
                   {!! $blog->description !!}
                    </div>
				

                </div>
              </div>
        </div>
      </div>
    </section>
@endsection
