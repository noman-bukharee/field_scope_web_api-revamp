@extends('admin.master')
@section('content')
@section('title', 'Edit Project')
@php
    $overall = '';
    $time = '';
    $day = '';
    $overall = '';
    $inspectorName = '';
@endphp
<section class="container-fluid main-sec">
    <div class="row">
        <div class="col-12 mt-2 details-row">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="map-img">
                        <div id="map"></div>
                    </div>
                </div>
                <!-- <a href="#" onclick="editRow({{$data['project']['id']}})"  class="btn-theme edit-project">Edit Project</a> -->
                <div class="col-12 col-md-6">
                <div class="project-name-box">
                    <h2>{{$data['project']['name']}}</h2>
                    <p>{{$data['project']['address1']}}</p>
                    <div class="row">
                  <div class="col-12 col-md-6">
                    <div class="row">
                      <div class="col-12 col-md-6">
                        <div class="postal-box">
                          <p>Postal Code</p>
                            @if(!empty($data['project']['postal_code']))
                                  <h6>{{$data['project']['postal_code']}}</h6>
                              @else
                                  <h6>-</h6>
                              @endif   
                        </div>
                      </div>
                      <div class="col-12 col-md-6">
                        <div class="postal-box">
                          <p>Sales Tax </p>
                          <h6>{{$data['project']['sales_tax']}}%</h6>
                        </div>
                      </div>
                      <div class="col-12 col-md-6">
                        <div class="postal-box">
                          <p>Claim Number </p>
                          @if(!empty($data['project']['claim_num']))
                                    <h6>{{$data['project']['claim_num']}}</h6>
                                @else
                                    <h6>-</h6>  
                                @endif
                        </div>
                      </div>
                      <div class="col-12 col-md-6">
                        <div class="postal-box">
                          <p>Inspection Date </p>
                          <h6>{{\Carbon\Carbon::parse($data['project']['inspection_date'])->format('m/d/y') }}</h6>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-md-6">
                    <div class="profile-box">
                      <div class="profile-img">
                        <img
                          src="{{asset('../assets/images/customer-ava.png')}}"
                          alt="inspector avatar"
                        />
                      </div>
                      <div class="ms-3">
                        <p>Inspector</p>
                          @foreach($data['inspectors'] AS $key => $item)

                                
                                @if($data['project']['assigned_user_id'] == $item->id)
                                    @php
                                        $inspectorName = $item->userNames;
                                    @endphp
                                    <h4>{{$inspectorName}}</h4>
                                @endif
                            @endforeach
                            <a href="mailto:{{$data['project']['customer_email']}}">{{$data['project']['customer_email']}}</a>

                      </div>
                    </div>
                    <!-- <button class="new-btn-theme mt-2" type="button">
                      Edit Project
                    </button> -->
                    <a href="#" onclick="editRow({{$data['project']['id']}})"  class="new-btn-theme mt-2 edit-project">Edit Project</a>

                  </div>
                </div>
              </div>
                </div>
            </div>
            <h1 class="heading">Photo Size</h1>
            <div class="action-btns">
                <div class="gallery-view-selector">
                    <button class="view-btn active" data-size="small">Small</button>
                    <button class="view-btn" data-size="medium">Medium</button>
                    <button class="view-btn" data-size="thumbnail">Thumbnail</button>
                </div>
                <div class="download-btns">
                    <button id="download-selected" class="download-btn">Download Photo</button>
                </div>
            </div>
            <!-- Photos -->
            <div id="gallery"  class="gallery small photo-sec row">
                <div class="col-12 photo">
                    <div class="row">
                        <!-- Required Photos -->
                        <div class="req-photo col-12">
                            <h4>Required Photos</h4>
                                @if(!empty($data['reportUrl']))
                                    <a href="{{$data['reportUrl']}}" target="_blank" class="report-btn">View Report</a>
                                @endif
                                <div class="elevations">
                                    @if($rCatItem['category_name'])
                                        @foreach($data['proMedia']['required_category']  AS $rCatkey => $rCatItem)
                                            <div class="d-flex flex-column gap-2">
                                                <h4 class="panel-title">
                                                    {{$rCatItem['category_name']}} ({{$rCatItem['media_count']}}/{{$rCatItem['category_min_quantity']}})
                                                </h4>
                                                @if($rCatItem['media_count'] > 0  ) 
                                                    <p>{{$overall}}</p>
                                                @endif
                                            </div>
                                            @if($rCatItem['media_count'] > 0  ) 

                                                @if(!empty($rCatItem['media']) )
                                                
                                                    <div class="elevation-items d-flex flex-row">
                                                            @foreach($rCatItem['media'] AS $rCatMediaKey => $rCatMediaItem)  
                                                                @php
                                                                    $requiredDate = $rCatMediaItem['created_at'];
                                                                    $time = date("h:i A", strtotime($requiredDate));
                                                                    $day = date("l", strtotime($requiredDate));
                                                                    $overall = date("l, F jS, Y", strtotime($requiredDate));
                                                                @endphp
                                                                <div class="small d-flex flex-column gap-2">                                       
                                                                        <!-- <a class="image-popup-vertical-fit" download="{{$overall}}.jpg" href="{{URL::to($rCatMediaItem['image_path'])}}" title="{{$day}}, {{$time}}"> -->
                                                                    <a class="checked-photos" href="{{URL::to('admin/project/photo/details/')}}/{{$rCatMediaItem['id']}}">
                                                                        <input type="checkbox" class="select-photo form-check-input" data-download="{{URL::to($rCatMediaItem['image_path'])}}">
                                                                        <img src="{{URL::to($rCatMediaItem['image_path'])}}" alt="inspection photos" >
                                                                    </a> 
                                                                    <div class=" d-flex flex-row gap-2 justify-space">
                                                                            <p>{{$time}} </p>
                                                                            <p>{{$inspectorName}} </p>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                    </div>

                                                
                                            @endif 
                                            @else
                                                <div id="img-popup">
                                                    <div class="text-center well">
                                                        <h5>No Media for {{$rCatItem['category_name']}} are uploaded yet</h5>
                                                    </div>
                                                </div>
                                            @endif   
                                        @endforeach
                                    @else
                                        <div id="img-popup">
                                            <div class="text-center well">
                                                <h5>No Media are uploaded yet</h5>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                        </div>
                        <!-- Inspection Photos -->
                        <div class="ins-photo col-12">
                            <h4>Inspection Photos</h4>
                            <div class="elevations">
                                @if(!empty($dCatItem['category_name']))
                                    @foreach($data['proMedia']['damaged_category']  AS $dCatkey => $dCatItem)
                                        <div class="d-flex flex-column gap-2">
                                            <h4 class="panel-title">
                                                {{$dCatItem['category_name']}} ({{$dCatItem['media_count']}} / {{$dCatItem['category_min_quantity']}})
                                            </h4>
                                            @if($dCatItem['media_count'] > 0)
                                                <p>{{$overall}}</p>
                                            @endif
                                        </div>
                                        @if($dCatItem['media_count'] > 0)
                                            @if(!empty($dCatItem['get_child']))
                                                @foreach($dCatItem['get_child'] AS $subCatKey => $subCatItem)
                                                    @if(!empty($subCatItem['media']) )
                                                        <div class="elevation-items d-flex flex-row">
                                                            @foreach($subCatItem['media'] AS $subCatMediaKey => $subCatMediaItem)
                                                                @php
                                                                    $inspectionDate = $subCatMediaItem['created_at'];
                                                                    $time = date("h:i A", strtotime($inspectionDate));
                                                                    $day = date("l", strtotime($inspectionDate));
                                                                    $overall = date("l, F jS, Y", strtotime($inspectionDate));
                                                                @endphp
                                                                <div class="d-flex flex-column gap-2">
                                                                    <!-- <a class="image-popup-vertical-fit" download="{{$overall}}.jpg" href="{{URL::to($subCatMediaItem['image_path'])}}" title="{{$day}}, {{$time}}"> -->
                                                                    <a class="checked-photos" href="{{URL::to('admin/project/photo/details/')}}/{{$subCatMediaItem['id']}}">
                                                                        <input type="checkbox" class="select-photo form-check-input" data-download="{{URL::to($subCatMediaItem['image_path'])}}">
                                                                        <img src="{{URL::to($subCatMediaItem['image_path'])}}" alt="inspection photos">
                                                                    </a>
                                                                    <div class=" d-flex flex-row gap-2  justify-space">
                                                                        <p>{{$time}} </p>
                                                                        <p>{{$inspectorName}} </p>
                                                                </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                @endforeach
                                            
                                        @endif
                                        @else
                                            <div id="img-popup">
                                                <div class="text-center well">
                                                    <h5>No media for {{$dCatItem['category_name']}} are uploaded yet</h5>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    <div id="img-popup">
                                        <div class="text-center well">
                                            <h5>No media are uploaded yet</h5>
                                        </div>
                                    </div>    
                                @endif
                            </div>
                        </div>
                        <!-- Additional  Photos -->
                        @if($data['proMedia']['additional_photos'][0]['media_count'] > 0)
                            <div class="ins-photo col-12">
                                <h4>Additional Photos</h4>
                                <div class="elevations">
                                    @if(!empty($data['proMedia']['additional_photos']))
                                        @foreach($data['proMedia']['additional_photos']  AS $aCatkey => $aCatItem)
                                            <div class="d-flex flex-column gap-2">
                                                <h4 class="panel-title">
                                                    {{$aCatItem['category_name']}}
                                                </h4>
                                                @if(!empty($aCatItem['media']) )
                                                    <p>{{$overall}}</p>
                                                @endif
                                            </div>
                                                @if(!empty($aCatItem['media']) )
                                                    <div class="elevation-items d-flex flex-row">
                                                        @foreach($aCatItem['media'] AS $rCatMediaKey => $rCatMediaItem)
                                                            @php
                                                                $additionalPhotoDate = $rCatMediaItem['created_at'];
                                                                $time = date("h:i A", strtotime($additionalPhotoDate));
                                                                $day = date("l", strtotime($additionalPhotoDate));
                                                                $overall = date("l, F jS, Y", strtotime($additionalPhotoDate));
                                                            @endphp
                                                            <div class="d-flex flex-column gap-2">
                                                                <!-- <a class="image-popup-vertical-fit" download="{{$overall}}.jpg" href="{{URL::to($subCatMediaItem['image_path'])}}" title="{{$day}}, {{$time}}">  -->
                                                                <a class="checked-photos" href="{{URL::to('admin/project/photo/details/')}}/{{$rCatMediaItem['id']}}">
                                                                    <input type="checkbox" class="select-photo form-check-input" data-download="{{URL::to($rCatMediaItem['image_path'])}}">
                                                                    <img src="{{URL::to($rCatMediaItem['image_path'])}}" alt="inspection photos">
                                                                </a>
                                                                <div class=" d-flex flex-row gap-2  justify-space">
                                                                    <p>{{$time}} </p>
                                                                    <p>{{$inspectorName}} </p>
                                                               </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div id="img-popup">
                                                        <div class="text-center well">
                                                            <h5>No media for {{$aCatItem['category_name']}} are upload yet</h5>
                                                        </div>
                                                    </div>
                                                @endif
                                        @endforeach
                                    @else
                                        <div class="check-media">
                                            <h4 class="panel-title">
                                                {{$aCatItem['category_name']}}
                                            </h4>
                                            <div id="img-popup">
                                                <div class="text-center well">
                                                    <h5>No media for {{$aCatItem['category_name']}} are upload yet</h5>
                                                </div>
                                            </div>
                                        </div> 
                                    @endif       
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div> 
        </div>
    </div>
</section>
<!-- Update Project-->
<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered project-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Update Project</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <!-- <img src="../assets/img/close-icon.png" alt=""> -->
        </button>
      </div>
        <form id="update_form" action="{{URL::to('admin/project/update')}}" method="POST">
            {{csrf_field()}}
            <div class="modal-body">
                    <div class="">
                        <input type="text" name="name" placeholder="Project Name" class="form-control place-color" id="exampleInputEmail1" aria-describedby="emailHelp">
                    </div>
                    <div class="address-group">
                        <input type="text"  id="add_address1" name="address1" placeholder="Address" class="form-control place-color" aria-describedby="emailHelp"  autocomplete="off">
                        <!-- <input name="address1" id="add_address1" type="text"  placeholder="Address 1" autocomplete="off"> -->
                        <input name="lat" type="hidden" >
                        <input name="long" type="hidden" >
                    </div>
                    <div class="">
                        <input type="text" name="claim_num" placeholder="Claim #" class="form-control place-color" id="exampleInputEmail2" aria-describedby="emailHelp">
                    </div>
                    <div class="">
                        <input type="text" name="sales_tax" placeholder="Sales Tax" class="form-control place-color" id="exampleInputEmail3" aria-describedby="emailHelp">
                    </div>
                    <div class="">
                        <input type ="date" name="inspection_date" id="inspection_date"  placeholder="Inspection Date" class="form-control place-color disableFuturedate"  />
                    </div>
                    <div class="">
                        <input  type="text" name="customer_email" placeholder="Customer Email" class="form-control place-color"  disabled/>
                    </div>
                    <select name="assigned_user_id" class="form-select add-select" aria-label="Default select example">
                        <option disabled selected>-Assign User-</option>
                        @foreach($data['inspectors'] AS $key => $item)
                        
                            <option value="{{$item->id}}"
                                @if($data['project']['assigned_user_id'] == $item->id)
                                    selected
                                @endif
                                >
                            {{$item->userNames}}</option>
                        @endforeach
                    </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-save">Save </button>
            </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push("page_css")
    <style>
        .justify-space{
            justify-content: space-around;
        }
        .pac-container{
            z-index: 9999;
        }
        .details-row {
            background: #ffffff;
            padding: 15px 15px;
            border-radius: 10px;
        }
        .details-row2 {
            background: #f7f7fa;
            padding: 11px 18px;
            border-radius: 12px;
            border: solid 1px #e2e6ed;
            width: 100%;
        }
        .project-details {
            gap: 30px;
        }

        .project-details h5 {
            color: #323588;
            font-size: 14px;
            font-weight: 100 !important;
            padding: 4px 0px;
        }
        .pr-name p {
            color: #323588;
            font-size: 14px;
            font-weight: 100 !important;
            padding: 4px 0px;
        }

        .pr-name h2 {
            color: #000124;
        }

        .project-details h6 {
            font-size: 24px;
        }
        .project-details h6 {
            font-size: 24px;
            letter-spacing: 1px;
        }
        .req-photo img,.ins-photo img {
            width: 150px;
            height: 144px;
            border-radius: 12px;
        }
        .elevation-items {
            gap: 20px;
            padding: 20px 0px;
            flex-wrap: wrap;
        }

        .photo-sec.row h4 {
            font-size: 24px;
        }

        .photo-sec.row {
            padding: 2rem 1rem;
        }

        .inspector-details img {
            width: 200px;
            height: 200px;
            border-radius: 8px;
        }

        .inspector-details {
            border-radius: 12px;
            border: solid 1px #e2e6ed;
            background-color: #f7f7fa;
            height: max-content;
            padding: 10px 10px;
        }

        .inspector-details>div:first-child {
            align-items: center;
        }
        #map {
            height: 100%; /* Full height */
            width: 100%; /* Full width */
            border-radius: 12px;
        }
        
        .edit-project {
            color: #fff !important;
            padding: 13px 24px;
            font-size: 16px;
            text-align: center;
        }
        .ins-details {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .ins-details h4 {
            font-size: 24px !important;
        }

        .ins-details h6,.ins-details a {
            color: #000124;
        }

        .ins-details a {
            font-size: 16px;
        }
        a.report-btn {
            background: #1282f2;
            color: #fff !important;
            padding: 10px 25px;
            font-size: 16px;
            border-radius: 8px;
            float: right;
        }

        a.report-btn:hover {
            background: #000;
        }
        .elevations {
            padding: 1rem 1rem;
        }

        h4.panel-title {
            font-size: 18px !important;
            font-weight: 100 !important;
            padding: 14px 0px;
        }
        div#img-popup h5 {
            text-align: left;
            font-size: 14px;
            font-family: 'EuclidSquare-Light';
            font-weight: 100 !important;
            padding-bottom: 10px;
        }
        .form-control:disabled {
            opacity: .5;
            cursor: not-allowed;
        }
        .download-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .download-button:hover {
            background-color: #3e8e41;
        }
        /* .mfp-zoom-out-cur */
        /* .gallery {
    display: grid;
    gap: 15px;
} */
        .gallery.small .photo img {
            width: 150px;
            height: 150px;
            transition: .1s ease-in;
            object-fit: cover;
        }
        .gallery.medium .photo img {
            width: 350px;
            height: 350px;
            transition: .1s ease-in;
            object-fit: cover;
        }
        .gallery.thumbnail .photo img {
            width: 300px;
            height: 300px;
            transition: .1s ease-in;
            object-fit: cover;
        }
        .gallery-view-selector {
            padding: 2rem 0rem;
            display: flex;
            flex-direction: row;
            gap: 15px;
        }

        .gallery-view-selector button {
            background: #1282f2;
            color: #fff !important;
            padding: 10px 25px;
            font-size: 16px;
            border-radius: 8px;
            border: none;
        }

        .gallery-view-selector button:hover {
            background: #000;
            transition: .3s ease-in;
            color: #fff;
        }
        .gallery-view-selector button.active {
            background: #000;
            transition: .3s ease-in;
            color: #fff;
        }
        button#download-selected {
            background: #1282f2;
            color: #fff !important;
            padding: 10px 25px;
            font-size: 16px;
            border-radius: 8px;
            border: none;
        }

        button#download-selected:hover {
            background: #000;
            transition: .3s ease-in;
            color: #fff;
        }

        .checked-photos {
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            gap: 10px;
        }
        input.select-photo {
            width: 1.5em;
            height: 1.5em;
        }
        .action-btns {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }
        .download-btns {
            padding: 1rem 0rem;
        }
        input.select-photo:hover,input.select-photo:focus {
            cursor: pointer;
        }
        h1.heading {
            padding-top: 24px;
            font-family: "EuclidSquare-Medium" !important;
            font-size: 24px;
        }
    </style>
@endpush
@push("page_js")
<script src="{{asset('assets/js/moment.min.js')}}" ></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js'></script>
<script>
    // Handle gallery view switching and active button styling
    document.querySelectorAll('.view-btn').forEach((button) => {
        button.addEventListener('click', (event) => {
            // Remove 'active' class from all buttons
            document.querySelectorAll('.view-btn').forEach((btn) => btn.classList.remove('active'));

            // Add 'active' class to the clicked button
            event.target.classList.add('active');

            // Change gallery view size
            const size = event.target.getAttribute('data-size');
            document.getElementById('gallery').className = `gallery ${size} photo-sec row`;
        });
    });
    // Handle download of selected images
    document.getElementById('download-selected').addEventListener('click', () => {
        const selectedImages = document.querySelectorAll('.select-photo:checked');
        if (selectedImages.length === 0) {
            alert('No images selected!');
            return;
        }

        selectedImages.forEach((checkbox) => {
            const downloadUrl = checkbox.getAttribute('data-download');
            const link = document.createElement('a');
            link.href = downloadUrl;
            link.download = downloadUrl.split('/').pop(); // Use the filename for download
            link.click();
            checkbox.checked = false;
        });
    });
    $(document).ready(function(){
        $('.image-popup-vertical-fit').magnificPopup({
            type: 'image',
            closeOnContentClick: true,
            closeBtnInside: false,
            fixedContentPos: true,
            mainClass: 'mfp-no-margins mfp-with-zoom', 
            gallery:{
                        enabled:true
                    },

            zoom: {
                enabled: true, 

                duration: 300, // duration of the effect, in milliseconds
                easing: 'ease-in-out', // CSS transition easing function

                opener: function(openerElement) {

                return openerElement.is('img') ? openerElement : openerElement.find('img');
                }
            }

        });

    });
    var updateUrl = "{{URL::to('admin/project/update')}}";
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
    function editRow(id) {
            
        var $editModal = $('#editModal');
            var shortDateFormat = 'dd/MM/yyyy';
            $.ajax({
                url: "{{URL::to('admin/project/editProjectDetails')}}/" + id,
                method: "GET",
                data: '',
                success: function (response) {
                    var data = response.data;
                    console.log(data)
                    let formattedDate = moment(data.inspection_date).format('YYYY-MM-DD');
                    console.log(formattedDate.toString())
                    // Update the form action with the project ID from the response
                    $('#update_form').attr('action', updateUrl + '/' + data.id);

                    // Populate form fields with response data
                    $('#update_form input[name="name"]').val(data.name); // Assuming response contains 'project_name'
                    $('#update_form input[name="address1"]').val(data.address1); // Assuming response contains 'address1'
                    $('#update_form input[name="lat"]').val({{$data['project']['latitude']}}); // Assuming response contains 'lat'
                    $('#update_form input[name="long"]').val({{$data['project']['longitude']}}); // Assuming response contains 'long'
                    $('#update_form input[name="claim_num"]').val(data.claim_num); // Assuming response contains 'claim_num'
                    $('#update_form input[name="sales_tax"]').val(data.sales_tax); // Assuming response contains 'sales_tax'
                    $('#inspection_date').val(formattedDate); // Assuming response contains 'inspection_date'
                    $('#update_form input[name="customer_email"]').val(data.customer_email); // Assuming response contains 'customer_email'

                    // Populate the assigned user select dropdown
                    $('#update_form select[name="assigned_user_id"] option').each(function (key, item) {
                        if (parseInt($(item).attr('value')) == parseInt(data.assigned_user_id)) {
                            $(item).attr('selected', true);
                        }
                    });
                    
                    // Trigger the change event for any relevant UI updates (if needed)
                    $('#update_form select[name="assigned_user_id"]').trigger('change');

                    // Show the modal with populated data
                    $editModal.modal('show');
                },
                error: function () {
                    alert("No Network");
                }
            });
        // Implement edit functionality here
    }
    // Google Map
    
</script>
<script>
    // Initialize and add the map
    function initMap() {
        // Coordinates (latitude, longitude)
        const location = { lat: {{$data['project']['latitude']}}, lng: {{$data['project']['longitude']}} }; // Example for New York City

        // Create a new map object centered at the given coordinates
        const map = new google.maps.Map(document.getElementById('map'), {
            zoom: 12, // Zoom level
            center: location, // Center the map at the coordinates
        });
    }
    // Map Address Implementation
    function initAutocomplete(){
        let addressInput = $('input[name="address1"]');
        let options = {
            types: ["geocode"],
            /** street_address: indicates a precise street address,
             * address: from table 3 */
            
            fields: ["name","address_components", "geometry"],
        };


        addressInput.each((i,el)=>{
            var autocomplete = new google.maps.places.Autocomplete(el, options);
            autocomplete.inputId = el.id;
            autocomplete.addListener('place_changed', fillIn);
        })



        function fillIn() {
            console.log("fillIn",this.inputId);

            var place = this.getPlace();
            var myInput = $('#' + this.inputId);

            let addr = [];
            place.address_components.filter((el,i)=>{
                el.types.map((typeEl,i)=>{

                    if(typeEl == 'locality')
                        addr.city = el.long_name

                    if(typeEl == 'administrative_area_level_1')
                        addr.state = el.short_name

                    if(typeEl == 'postal_code')
                        addr.postal_code = el.long_name
                })
            });

            console.log('place.name',place.name);
            console.log(place.address_components);

            addr.postal_code = (typeof addr.postal_code) == 'undefined' ? '': addr.postal_code;
            console.log('final address',`${place.name}, ${addr.city}, ${addr.state}, ${addr.postal_code}`);

            let formattedAddress = `${place.name}, ${addr.city}, ${addr.state} ${addr.postal_code}`

            myInput.closest("div.address-group").find("input[name='lat']").val(place.geometry.location.lat());
            myInput.closest("div.address-group").find("input[name='long']").val(place.geometry.location.lng());

            myInput[0].value = formattedAddress;

            console.log('lat',place.geometry.location.lat());
            console.log('lng',place.geometry.location.lng());

        }
    }
    function initBoth() {
        initMap();        // Call first callback
        initAutocomplete(); // Call second callback
    }

    // Load the Google Maps API with the wrapper function as the callback
    function loadGoogleMaps() {
        const script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key=AIzaSyAlUlyus8U80FZOXPzVHEeVEYHcJHsOrjU&callback=initBoth&v=beta&libraries=places&v=weekly`;
        document.head.appendChild(script);
    }

    // Call loadGoogleMaps to dynamically load the script
    loadGoogleMaps();
</script>

@endpush