@extends('layouts.newheader')

@section('pageTitle', 'thankyou')

@section('pageDescription', 'RSM Enterprises is the manufacturers of ed products like sildenafil, vardenafil , tadalfil
    and herbal products like many more . for more details contact us !!!!')

@section('pageKeyword', 'erectile dysfunction products , erectile dysfunction pills , RSM enterprises')

@section('content')
<style>
    .thumbsup-logo {
    width: 80px;
    display: inline-block;
}
.thankyouPage {
    padding: 5%;
    text-align: center;
}
</style>
<section id="thankyouForm">
    <div class="container">
        <div class="row">
            <div class="thanku-block mx-auto">
                 <div class="thankyouPage">                        
                    <!-- <i class="fa-solid fa-check"></i>  -->
                    <h2>Thank You!</h2>
                    <p>Thank you for visiting Website! You Will <br> received your request; we'll be in touch shortly!</p>
                    <div class="thumbsup-logo">
                        <img src="{{ asset('images/thumbs-up.png') }}" class="img-fluid">
                    </div>        
                    <p class="pt-4 fw-bold fs-5">Check Your Email</p>
                 </div>
            </div>
        </div>
    </div>
</section>



@endsection
