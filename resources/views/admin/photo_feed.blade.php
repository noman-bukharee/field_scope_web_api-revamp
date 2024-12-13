@extends('admin.master')
@section('content')
@section('title', 'Photo Feeds')
<section class="container-fluid main-sec">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="user-type-sec">
                <div>
                    <h2>Photo Feeds</h2>
                </div>
                <!-- <pre>{{print_r($data['latest_photos'])}}</pre> -->
                <div class="d-flex align-items-center">
                    <div class="me-3 user-type-input">
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text" id="addon-wrapping">
                                <img src="../assets/img/search-icon.png" alt=""
                                /></span>
                            <input
                                    type="text"
                                    id="filter"
                                    class="form-control"
                                    placeholder="Search"
                                    aria-label="Search"
                                    aria-describedby="addon-wrapping"
                            />
                        </div>
                    </div>
                    <div class="me-3">
                        <button class="btn-theme2">Filters</button>
                    </div>
                    <div>
                        <button class="btn-theme2 clear-btn">Clear Filter</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mt-2">
            <div class="row" id="card-container">
                @if(!empty($data['latest_photos']->total()))
                    @foreach($data['latest_photos'] AS $key => $item)
                        @if(!empty($item->path))

                        <!-- {{$item->media_tags}} -->
                            <div class="col-12 col-md-6 col-lg-4 record-item">
                                <div class="project-card photo-feed">
                                    <div class="project-card-header">
                                        <a href="photo_feed/details/{{$item['id']}}">
                                            <div class="card-img">
                                                @if(!empty($item->path))
                                                    <img src="{{url('uploads/media/'.$item->path)}}" alt="" loading="lazy">
                                                    <!--<img src="https://fieldscope.qa.retrocubedev.com/uploads/media/{{$item->path}}" alt="">-->
                                                @else
                                                    <img src="{{url('assets/images/blank-img.png')}}" alt="" loading="lazy">
                                                @endif
                                            </div>
                                        </a>
                                    </div>
                                    <div class="project-card-body">
                                        <div class="card-img-title">
                                            <p class="title">{{$item->p_name}}</p>
                                            <!-- <p class="detail">755 Lindenwold Garden Apartments Ro</p> -->
                                            <div class="d-flex align-items-center justify-content-between">
                                                <ul class="d-flex align-items-center">
                                                    <li >{{\Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</li>
                                                    <li class="list-show">{{$item->u_first_name}} {{$item->u_last_name}}</li>
                                                </ul>
                                                <div class="feed-action">
                                                    <div class="photo-action-btn">
                                                        <a href="{{url('admin/photo_feed/edit/'.$item['id'])}}" class="d-flex btn btn-primary mr-2">
                                                        <img src="{{asset("assets/img/edit-icon.png")}}" alt=""> Edit</a>
                                                    </div>
                                                    <div class="photo-action-btn view">
                                                        <a href="photo_feed/details/{{$item['id']}}" class="btn btn-primary mr-2"> View</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        @endif    
                    @endforeach
                @else
                <div class="col-md-12 mb-3 text-center">
                    <h4>No Data Found</h4>
                </div>
                @endif
            </div>
        </div>
        <!-- Pagination -->
        <nav class="pagination-records" aria-label="Page navigation ">
            <ul class="pagination justify-content-center" id="pagination">
                <!-- jQuery will populate pagination items here -->
            </ul>
        </nav>
    </div>
</section>
@endsection

@push("page_css")
    <style>
        .photo-action-btn a {
            background: #fff !important;
            border: 1px solid #0000004a;
            font-size: 14px;
            font-family: 'EuclidSquare-Light';
            padding: 6px 20px;
        }
        .photo-action-btn a:hover i {
            color: #fff !important;  
        }
        .photo-action-btn a:hover{
            color: #fff !important;
            background:#1282f2 !important;
            border: 1px solid #1282f2;
        }
        .photo-action-btn a:hover img {
            filter: brightness(0) invert(1);
        }

        .photo-action-btn a i {
            font-size: 13px;
            padding: 0px 0px;
            /* border-bottom: 1px solid #000; */
            color: #000000b5;
        }
        ul li {
            font-family: 'EuclidSquare-Light';
        }
        .project-card:hover {
            transform: scale(1.03);
            transition: .2s ease-out;
        }
        .project-card {
            transition: .2s ease-out;
        }
        .project-card:hover .project-card-body {
            box-shadow: 0 20px 21px 0 rgba(0, 0, 0, 0.1);
        }
        button.btn-theme2.clear-btn {
            background: #fff;
            color: #00000082;
            border: 1px solid #00000029;
        }
        .feed-action {
            display: flex;
            gap: 12px;
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