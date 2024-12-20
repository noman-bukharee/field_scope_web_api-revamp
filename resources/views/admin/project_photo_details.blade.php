@extends('admin.master')
@section('content')
@section('title', 'Project Photo Detail')
<pre>
<!-- {{print_r($data)}} -->
<!-- {{print_r($data['latest_photos'])}} -->
</pre>
<pre>
    <!-- {{print_r($data)}} -->
    </pre>
<section class="container-fluid main-sec">
    <div class="row details-row mt-4">
        <div class="col-md-5 ">
            <div class="card details-row-card">
                <div class="card-body">
                    <h5 class="card-title">{{ isset($data->project->name) ?  $data->project->name : 'No Name' }}</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Area:</label>
                                <h5>{{ isset($data->category->name) ?  $data->category->name : 'N/A' }}</h5>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Lat:</label>
                                <h5>{{ isset($data->project->latitude)  ? $data->project->latitude : 'N/A' }}</h5>
                            </div>
                        </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Inspection Date:</label>
                                    <h5>{{\Carbon\Carbon::parse($data['project']['inspection_date'])->format('d/m/y') }}</h5>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Long:</label>
                                    <h5>{{ isset($data->project->longitude) ? $data->project->longitude : 'N/A' }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Claim No:</label>
                                    <h5>{{ isset($data->project->claim_num) ? $data->project->claim_num : 'N/A' }}</h5>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Quantity:</label>
                                    <h5>{{ isset($data->category->min_quantity)  ? $data->category->min_quantity : 'N/A' }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="form-group annotation">
                            <label>Photo Tag annotation:</label>
                            <h5>{{ isset($data->note) ? $data->note : 'N/A' }}</h5>
                        </div>
                        <div class="photo-action-btn">
                            <a href="{{url('admin/project/photo/edit/'.$data['id'])}}" class="btn btn-primary mr-2">
                            <i class="fa-solid fa-pen"></i> Edit</a>
                        </div>
                    </div>
                </div>
        </div>
        <div class="col-md-7">
            <div class="feed-img" style="background-image:url('{{URL::to('uploads/media/'.$data['path'] )}}')">
                <!-- <img src="{{URL::to('uploads/media/'.$data['path'] )}}" class="card-img-top" alt="Project Image"> -->
                <!-- <div class="card-img-overlay d-flex flex-column justify-content-end align-items-end">
                    <span class="badge badge-light">Img.ly EDITOR</span>
                </div> -->
            </div>
        </div>
    </div>
</section>


    
@endsection

@push("page_css")
    <style>
        .details-row {
            background: #ffffff;
            padding: 15px 2px;
            border-radius: 10px;
        }
        h5.card-title {
            font-size: 32px;
            padding: 10px 0px;
            border-bottom: 1px solid #bfbfbf;
        }

        .card.details-row-card {
            background: #f7f7fa;
        }

        .details-row .row {
            padding: 10px 0px;
        }

        .details-row .row .form-group {
            border: 1px solid #0000001c;
            border-radius: 7px;
            background: #fff;
            padding: 15px 25px;
        }


        .card.details-row-card .form-group label {
            color: #323588;
            font-family: 'EuclidSquare-Light';
            font-size: 14px;
        }

        .card.details-row-card .form-group h5 {
            color: #000124;
            font-size: 16px;
            padding: 5px 0px;
        }
        .form-group.annotation {
            border: 1px solid #0000001c;
            border-radius: 7px;
            background: #fff;
            padding: 15px 25px;
        }
        .photo-action-btn {
            padding: 15px 0px;
        }

        .photo-action-btn a {
            width: 100%;
            color: #fff !important;
            font-family: 'EuclidSquare-Light';
            font-size: 16px;
            padding: 10px 0px;
            transition: .3s ease-in;
        }

        .photo-action-btn a:hover {
            color: #000 !important;
            border: 1px solid #0000003d;
            background: #fff;
        }
        .feed-img {
            background-position: center;
            background-size: cover;
            height: 100%;
            border-radius: 7px;
        }
        .photo-action-btn i {
            padding: 0px 2px;
        }

        /* .feed-img img {
            height: auto;
            border-radius: 7px;
            height: 600px;
        } */
    </style>
@endpush
@push("page_js")
<script>
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