@extends('admin.master')
@section('content')
@section('title', 'Subscription')

<pre>
    <!-- {{print_r($data)}} -->
    
</pre>
<section class="container-fluid main-sec mb-5">
    <div class="row">
        <div class="col-12">
            <div class="user-type-sec">
                <div>
                    <h2>Subscription</h2>
                </div>
                <div class="d-flex align-items-center">
                    <div class="me-3 user-type-input">
                    </div>
                    <!-- <div class="subscribe-btn">
                        <a href="{{URL::to('admin/re_subscription')}}" class="btn-theme " >{{$data['companySub']['subscription_id'] != $item['id'] ? 'Subscribe' : 'Re-Subscribe'}}</a>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
    <div class="row">
    <div class="col-12 col-sm-8 col-md-6 col-lg-7">
    @foreach($data['subs'] AS $key => $item)
     @if($item->total_featured_deals >= 10)
        <div class="subscription-box">
            <div class="d-flex justify-content-between pb-3">
                <div>
                <h2>{{ucfirst($item->type)}}</h2>
                </div>
                <div class="text-end">
                <h2>${{ucfirst($item->per_user_amount)}}</h2>
                <p>{{ucfirst($item->title)}}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-10 col-lg-8">
                <div class="row">
                    <div class="col-12 col-md-6">
                    <div class="form-check">
                        <input
                        class="form-check-input"
                        type="checkbox"
                        value=""
                        id="flexCheckDefault"
                        checked
                        />
                        <label
                        class="form-check-label"
                        
                        >
                        ${{ucfirst($item->per_user_amount)}}/month per User
                        </label>
                    </div>
                    <div class="form-check">
                        <input
                        class="form-check-input"
                        type="checkbox"
                        value=""
                        id="flexCheckDefault"
                        checked
                        />
                        <label
                        class="form-check-label"
                        
                        >
                        {{$item->duration}} {{ucfirst($item->duration_unit)}}
                        </label>
                    </div>
                    <div class="form-check">
                        <input
                        class="form-check-input"
                        type="checkbox"
                        value=""
                        id="flexCheckDefault"
                        checked
                        />
                        <label
                        class="form-check-label"
                        
                        >
                        One Hour of Training
                        </label>
                    </div>
                    </div>
                    <div class="col-12 col-md-6">
                    <div class="form-check">
                        <input
                        class="form-check-input"
                        type="checkbox"
                        value=""
                        id="flexCheckDefault"
                        checked
                        
                        />
                        <label
                        class="form-check-label"
                        
                        >
                        No Contract
                        </label>
                    </div>
                    <div class="form-check">
                        <input
                        class="form-check-input"
                        type="checkbox"
                        value=""
                        id="flexCheckDefault"
                        checked
                        />
                        <label
                        class="form-check-label"
                        
                        >
                        Free Setup
                        </label>
                    </div>
                    </div>
                </div>
                </div>
                <div class="col-12 col-md-2 col-lg-4">
                <div
                    class="d-flex justify-content-end h-100 align-items-end"
                >
                    <!-- <button class="btn-theme sus-btn">Subscribe Now</button> -->
                    <a href="{{URL::to('admin/re_subscription')}}" class="btn-theme sub-btn {{$data['companySub']['subscription_id'] != $item['id'] ? 'enabled' : 'disabled'}}" >{{$data['companySub']['subscription_id'] != $item['id'] ? 'Subscribe Now' : 'Subscribed'}}</a>
                </div>
                </div>
            </div>
        </div>
        @endif
    @endforeach    
    </div>
    <div class="col-12 col-sm-4 col-md-6 col-lg-5">
        @foreach($data['subs'] AS $key => $item)
        @if($data['companySub']['subscription_id'] == $item->id )
            <div class="current-subcription-box">
                <h2>Current Subcription</h2>
                <div class="img-subcription">
                    <img src="../assets/img/subscription.png" alt="">
                </div>
                <div class="sus-box-text">
                    <div class="row">
                        <div class="col-12">
                            <p class="color-black mb-2 font-18">{{$item['title']}}</p>
                        </div>
                        <div class="col-12">
                            <div class="project-detail">
                                <p class="detail-title">Description</p>
                                <p>{{$item['description']}}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mt-3">
                            <div class="project-detail">
                                <p class="detail-title">Duration</p>
                                <p>{{$item['duration'].' '.$item['duration_unit']}}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mt-3">
                                <div class="project-detail">
                                <p class="detail-title">Subscription Expiry Date</p>
                                <p>{{\Carbon\Carbon::parse($data['companySub']['subscription_expiry_date'])->format('m/d/y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                    <!-- <button class="btn-theme w-100 mt-4">Re-Subscribe</button> -->
                    <a href="{{URL::to('admin/re_subscription')}}" class="btn-theme w-100 mt-4 sub-btn">{{$data['companySub']['subscription_id'] != $item['id'] ? 'Subscribe' : 'Re-Subscribe'}}</a>
            </div>
        @endif
        @endforeach    
    </div>
    </div>
</section>
@endsection

@push("page_css")
    <style>
        .subscribe-btn a {
            color: #fff !important;
        }
        input[type="checkbox"] {
            pointer-events: none;
            filter: none;
            opacity: 1;
        }
        .sub-btn{
            color:#fff !important;
            text-align:center;
            font-size: 16px;
        }
        .disabled {
            pointer-events: none;
            filter: opacity(0.8);
            opacity: 1;
        }
        .enabled {
            cursor: pointer;
            pointer-events: auto;
            filter: none;
            opacity: 1;
        }
    </style>
@endpush
@push("page_js")
    <script>
       
        $(document).ready(function () {
            $('#example').DataTable({
                paging: true,
                searching: false,
                ordering: true,
                info: true,
                pageLength: 5,
                responsive:true,
                language: {
                    info: "Page _PAGE_ of _PAGES_",
                },
                scrollY: "400px",   // Set the height for scrolling
                scrollCollapse: true,
        
            });
        })

        function editRow(button) {
            var row = $(button).closest('tr')
            var data = row
                .children('td')
                .map(function () {
                    return $(this).text()
                })
                .get()
            alert('Edit row: ' + data.join(', '))
            // Implement edit functionality here
        }

        function deleteRow(button) {
            var row = $(button).closest('tr')
            row.remove()
            alert('Row deleted.')
            // Implement additional delete functionality here if needed
        }
        $(document).ready(function () {
            var width = $(window).width()
            $(window).resize(function (e) {
                e.preventDefault()
                width = $(window).width()
                if (width <= 767) {
                    // Compare with a number
                    $('#wrapper').removeClass('toggled')
                }
            })
            $('#menu-toggle').click(function (e) {
                e.preventDefault()
                $('#wrapper').toggleClass('toggled')
            })
        })
        $(document).ready(function() {
            $(".dropdown-toggle").dropdown();
        })
    </script>
@endpush