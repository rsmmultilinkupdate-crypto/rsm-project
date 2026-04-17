@extends('layouts.main')

@section('pageTitle', 'RSM Enterprises- Contact Us')

@section('pageDescription', "RSM Enterprises is the manufacturer and supplier providing excellent and quality products for men's and women's health. Contact us now for more info !!")

@section('pageKeyword', 'Ed medicines manufacturers and suppliers')

@section('content')
<!-- Breadcrumbs-->
<style type="text/css">
.pagination {
  display: inline-block;
}

.pagination a {
  color: black;
  float: left;
  padding: 8px 16px;
  text-decoration: none;
  transition: background-color .3s;
  border: 1px solid #ddd;
}

.pagination a.active {
  background-color: #4CAF50;
  color: white;
  border: 1px solid #4CAF50;
}

.pagination a:hover:not(.active) {background-color: #ddd;}
canvas#canvas_0 {
    width: 100%;
}
</style>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.2.2/pdf.min.js"></script>
        <section class="breadcrumbs-custom breadcrumbs-custom-svg bg-image-custom">
          <div class="container">
            <p class="heading-1 breadcrumbs-custom-title">Other Products</p>
            <ul class="breadcrumbs-custom-path">
              <li><a href="{{ url('/') }}">Home</a></li>
              <li><a href="#">Pages</a></li>
              <li class="active">Other Products</li>
            </ul>
          </div>
        </section>
      </div>
      <!-- Contact information-->
    <!--   <section class="section section-lg bg-default">
        <!-- section wave-->
        <!--div class="section-wave">
          <svg x="0px" y="0px" width="1920px" height="45px" viewbox="0 0 1920 45" preserveAspectRatio="none">
            <path d="M1920,0c-82.8,0-108.8,44.4-192,44.4c-78.8,0-116.5-43.7-192-43.7 c-77.1,0-115.9,44.4-192,44.4c-78.2,0-114.6-44.4-192-44.4c-78.4,0-115.3,44.4-192,44.4C883.1,45,841,0.6,768,0.6 C691,0.6,652.8,45,576,45C502.4,45,461.9,0.6,385,0.6C306.5,0.6,267.9,45,191,45C115.1,45,78,0.6,0,0.6V45h1920V0z"></path>
          </svg>
        </div>
        <div class="container container-bigger">
          <div class="row row-50 justify-content-md-center justify-content-xl-between">
            <div class="col-md-12 col-lg-12">
              <h3>Other Products</h3>
              <hr class="divider divider-left divider-default"><br>
              <div class="pagination">
                <a href="?page=1" class="<?php if(isset($_GET['page']) && $_GET['page'] == 1){ echo 'active'; }else if(!isset($_GET['page'])){ echo 'active'; } ?>">1</a>
                <a href="?page=2" class="<?php if(isset($_GET['page']) && $_GET['page'] == 2){ echo 'active'; } ?>">2</a>
                <a href="?page=3" class="<?php if(isset($_GET['page']) && $_GET['page'] == 3){ echo 'active'; } ?>">3</a>
                <a href="?page=4" class="<?php if(isset($_GET['page']) && $_GET['page'] == 4){ echo 'active'; } ?>">4</a>
                <a href="?page=5" class="<?php if(isset($_GET['page']) && $_GET['page'] == 5){ echo 'active'; } ?>">5</a>
                <a href="?page=6" class="<?php if(isset($_GET['page']) && $_GET['page'] == 6){ echo 'active'; } ?>">6</a>
                <a href="?page=7" class="<?php if(isset($_GET['page']) && $_GET['page'] == 7){ echo 'active'; } ?>">7</a>
                <a href="?page=8" class="<?php if(isset($_GET['page']) && $_GET['page'] == 8){ echo 'active'; } ?>">8</a>
                <a href="?page=9" class="<?php if(isset($_GET['page']) && $_GET['page'] == 9){ echo 'active'; } ?>">9</a>
                <a href="?page=10" class="<?php if(isset($_GET['page']) && $_GET['page'] == 10){ echo 'active'; } ?>">10</a>
                <a href="?page=11" class="<?php if(isset($_GET['page']) && $_GET['page'] == 11){ echo 'active'; } ?>">11</a>
                <a href="?page=12" class="<?php if(isset($_GET['page']) && $_GET['page'] == 12){ echo 'active'; } ?>">12</a>
              </div>

              @if(isset($_GET['page']) && $_GET['page'] == 12)
             <input type="hidden" id="urlid" value="pdf/rsm enterprises brouchers 12.pdf">
                 <div id="canvases"></div>
              @elseif(isset($_GET['page']) && $_GET['page'] == 11)
              <input type="hidden" id="urlid" value="pdf/rsm enterprises brouchers 11.pdf">
                 <div id="canvases"></div>
              @elseif(isset($_GET['page']) && $_GET['page'] == 10)
              <input type="hidden" id="urlid" value="pdf/rsm enterprises brouchers 10.pdf">
                 <div id="canvases"></div>
              @elseif(isset($_GET['page']) && $_GET['page'] == 9)
              <input type="hidden" id="urlid" value="pdf/rsm enterprises brouchers 9.pdf">
                 <div id="canvases"></div>
              @elseif(isset($_GET['page']) && $_GET['page'] == 8)
              <input type="hidden" id="urlid" value="pdf/rsm enterprises brouchers 8.pdf">
                 <div id="canvases"></div>
              @elseif(isset($_GET['page']) && $_GET['page'] == 7)
              <input type="hidden" id="urlid" value="pdf/rsm enterprises brouchers 7.pdf">
                 <div id="canvases"></div>
              @elseif(isset($_GET['page']) && $_GET['page'] == 6)
              <input type="hidden" id="urlid" value="pdf/rsm enterprises brouchers 6.pdf">
                 <div id="canvases"></div>
              @elseif(isset($_GET['page']) && $_GET['page'] == 5)
              <input type="hidden" id="urlid" value="pdf/rsm enterprises brouchers 5.pdf">
                 <div id="canvases"></div>
              @elseif(isset($_GET['page']) && $_GET['page'] == 4)
              <input type="hidden" id="urlid" value="pdf/rsm enterprises brouchers 4.pdf">
                 <div id="canvases"></div>
              @elseif(isset($_GET['page']) && $_GET['page'] == 3)
              <input type="hidden" id="urlid" value="pdf/rsm enterprises brouchers 3.pdf">
                 <div id="canvases"></div>
              @elseif(isset($_GET['page']) && $_GET['page'] == 2)
              <input type="hidden" id="urlid" value="pdf/rsm enterprises brouchers 2.pdf">
                 <div id="canvases"></div>
              @elseif(isset($_GET['page']) && $_GET['page'] == 1)
              <input type="hidden" id="urlid" value="pdf/rsm enterprises brouchers 1.pdf">
                 <div id="canvases"></div>
              @else
                 <input type="hidden" id="urlid" value="pdf/rsm enterprises brouchers 1.pdf">
                 <div id="canvases"></div>
             
<!-- 
              <object data="{{ asset('pdf/rsm enterprises brouchers 1.pdf')}}" type="application/pdf" width="200px" height="200px">
          <p>Your web browser doesn't have a PDF plugin.
          Instead you can <a href="filename.pdf">click here to
          download the PDF file.</a></p>
        </object>


        <embed src="{{ asset('pdf/rsm enterprises brouchers 1.pdf')}}" width="800px" height="210px" / -->
<!-- 
        <iframe src="{{ asset('pdf/rsm enterprises brouchers 1.pdf')}}" width="100%" height="800px"></iframe> 
 -->
       <!--    <embed
              src="{{ action('Guest\HomeController@getDocument', ['id'=> 1]) }}"
              style="width:600px; height:800px;"
              frameborder="0"
          >
 -->

              @endif
 
             
            </div>
            
          </div>
        </div>
      </section--> 

  
  <script type="text/javascript" src="{{ url('')}}/script.js"></script>
@endsection