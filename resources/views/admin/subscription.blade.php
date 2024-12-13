@extends('admin.master')
@section('content')
@section('title', 'Subscription')

<pre>
    <!-- {{print_r($data)}} -->
</pre>
<section class="container-fluid main-sec">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="user-type-sec">
                <div>
                    <h2>Subscription</h2>
                </div>
                <div class="d-flex align-items-center">
                    <div class="me-3 user-type-input">
                    </div>
                    <div class="subscribe-btn">
                        <a href="{{URL::to('admin/re_subscription')}}" class="btn-theme">{{$data['companySub']['subscription_id'] == '5' ? 'Subscribe' : 'Re-Subscribe'}}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mt-2">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-4">
                    @foreach($data['subs'] AS $key => $item)
                        @if($data['companySub']['subscription_id'] == $item->id )
                            <div class="project-card photo-feed">
                                <div class="project-card-header">
                                    <div class="card-img">
                                        <img src="../assets/img/subscription.png" alt="">
                                    </div>
                                    
                                </div>
                                <div class="project-card-body">
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
                            </div>
                        @endif
                    @endforeach    
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push("page_css")
    <style>
        .subscribe-btn a {
            color: #fff !important;
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