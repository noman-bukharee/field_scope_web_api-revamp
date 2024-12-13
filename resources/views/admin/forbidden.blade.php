@extends('admin.master') 
@section('content') 
@section('title', 'Forbidden') 
@php
  $routName = trim(session('response.data'));
  $routName = ucwords(str_replace('.', ' ', $routName));
@endphp
<section class="container-fluid main-sec">
    <div class="row">
      <div class="col-12 mt-5">
        <div class="user-type-sec">
          <div>
            <!-- if routname  exists then show other wise show forbidden -->
            <h2>{{ $routName ? $routName : 'Forbidden' }}</h2>
            
          </div>
        </div>
      </div>
    </div>
    <!-- Settings -->

    <div class="row">
      <p>{{ session('response.message') ? session('response.message') : 'You do not have permission to access this' }}</p>
    </div>
  </section> 
  @endsection 
  @push("page_css") 
  <style>
  </style> 
  @endpush 
  @push("page_js") 
  <script>
  </script> 
  @endpush