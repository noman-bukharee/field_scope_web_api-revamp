@extends('admin.master')
@section('content')
@section('title', 'Projects')
<?php
// echo "<pre>";
$session = \Session::all();
$user = $session['user'];
// print_r($session['user']); die;

?>
@php
use App\Models\User;
    

    $userGroupId = $user->user_group_id;
    if ($userGroupId == 1) {
        $roleName = 'admin';
    } 
    elseif($userGroupId == 2){

        //Get Agent role Title
        $userInsector = User::leftJoin('company_group AS cg', 'cg.id', '=', 'user.company_group_id')
            ->where('user.id', session('user')->id)
            ->where('cg.id', $user->company_group_id)
            ->first();
        if($userInsector->role_id == 2){
            $roleName = 'manager';
        }
        else{
            $roleName = 'standard';
        }
    }
   
@endphp
<section class="container-fluid main-sec">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="user-type-sec">
                <div>
                    <h2>Projects</h2>
                    <!-- <pre>{{ print_r($data, true) }}</pre> -->
                    
                </div>
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
                        <button class="btn-theme2"  data-bs-toggle="modal" data-bs-target="#myModalFilter">Filters</button>
                    </div>
                    <div class="me-3">
                        <button class="btn-theme2 clear-btn" id="clearFiltersBtn">Clear Filter</button>
                    </div>
                    @if($roleName == 'admin' || $roleName == 'manager')
                    <div >
                        <button class="btn-theme" type="button"  data-bs-toggle="modal" data-bs-target="#myModal">+ Add New</button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-12 mt-2">
            <div class="row" id="card-container">
               
                @foreach($data as $item)
                    @if(is_array($item))
                    
                        @foreach($item as $key => $value)
                        
                            @if(session('user')->user_group_id == 1 || $roleName == 'manager' ||(session('user')->user_group_id == 2 && $value['assigned_user_id'] == session('user')->company_group_id)) 
                            
                                @php
                                    // Format inspection date to "day-month-year"
                                    $inspection_date = date("d-m-Y", strtotime($value['inspection_date']));

                                    // Format display_created_at to "hour:minute AM/PM"
                                    $time = date("h:i A", strtotime($value['display_created_at']));

                                    // Get the current date and time
                                    $current_date = new DateTime();

                                    // Get the project creation date
                                    $project_created_date = new DateTime($value['display_created_at']);

                                    // Calculate the difference between current date and project creation date
                                    $interval = $current_date->diff($project_created_date);

                                    // Check if the difference is more than one day
                                    if ($interval->days >= 1) {
                                        // More than one day ago, output in days
                                        if ($interval->days == 1) {
                                            $time_ago = "1 day ago";
                                        } else {
                                            $time_ago = $interval->days . " days ago";
                                        }
                                    } else {
                                        // Less than one day ago, calculate hours and minutes
                                        if ($interval->h >= 1) {
                                            // More than one hour ago
                                            $time_ago = $interval->h . " hours ago";
                                        } else {
                                            // Less than one hour ago, output in minutes
                                            $time_ago = $interval->i . " minutes ago";
                                        }
                                    }
                                @endphp
                                <div id="demo" class="col-12 col-md-6 col-lg-4 record-item">
                                    <div class="project-card">
                                        <div class="project-card-header">
                                            <div class="card-img">
                                                <a href="{{ URL::to('admin/project/detail')}}/{{ $value['id'] }}">
                                                    <img src="../assets/img/card-img.png" alt="">
                                                </a>
                                                <div class="card-img-title">
                                                    <p class="title">{{ $value['name'] ?  $value['name'] : 'No data available' }}</p>
                                                    <p class="detail">{{ $value['address1'] ?   $value['address1'] : 'No data available' }}</p>
                                                    <div class="edit-details">
                                                        <ul class="d-flex align-items-center">
                                                            <li>{{$time_ago ?  $time_ago : 'No data available'}}</li>
                                                            <li class="list-show">{{ $time ?  $time : 'No data available' }}</li>
                                                        </ul>

                                                    <div class="edit-icon">
                                                        <a
                                                                class=""
                                                                href="{{ URL::to('admin/project/detail')}}/{{ $value['id'] }}"
                                                        >View</a
                                                        >
                                                    </div>
                                                        <!-- <div class="dropdown">
                                                            <button
                                                                    class="action-btn btn-secondary dropdown-toggle"
                                                                    type="button"
                                                                    id="actionMenu1"
                                                                    data-bs-toggle="dropdown"
                                                                    aria-expanded="false"
                                                            >
                                                                Actions
                                                            </button>
                                                            <ul
                                                                    class="dropdown-menu"
                                                                    aria-labelledby="actionMenu1"
                                                            >
                                                                <li>
                                                                    <a
                                                                            class="dropdown-item"
                                                                            href="#"
                                                                            onclick="editRow(this)"
                                                                    >Edit</a
                                                                    >
                                                                </li>
                                                                <li>
                                                                    <a
                                                                            class="dropdown-item"
                                                                            href="#"
                                                                            onclick="deleteRow(this)"
                                                                    >Delete</a
                                                                    >
                                                                </li>
                                                            </ul>
                                                        </div> -->
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="project-card-body">
                                            <div class="row">
                                                <div class="col-12 col-md-6">
                                                    <div class="project-detail">
                                                        <p class="detail-title">Assigned to:</p>
                                                        <p>{{ $value['assigned_user'] ?  $value['assigned_user'] : 'No data available' }}</p>
                                                    </div>
                                                    <div  class="project-detail mt-3">
                                                        <p class="detail-title">Email:</p>
                                                        <p>{{ $value['customer_email'] ?   $value['customer_email'] : 'No data available' }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6 ">
                                                    <div  class="project-detail">
                                                        <p class="detail-title">Claim No.:</p>
                                                        <p>{{ $value['claim_num'] ?   $value['claim_num'] : 'No data available' }}</p>
                                                    </div>
                                                    <div class="project-detail mt-3">
                                                        <p class="detail-title">Inspection Date:</p>
                                                        <p>{{ $inspection_date ?   $inspection_date : 'No data available' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                @endforeach
                <!-- Pagination -->
                <nav class="pagination-records" aria-label="Page navigation ">
                    <ul class="pagination justify-content-center" id="pagination">
                        <!-- jQuery will populate pagination items here -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Add Project-->
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered project-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Add Project</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <!-- <img src="../assets/img/close-icon.png" alt=""> -->
        </button>
      </div>
        <form id="add_form" action="{{URL::to('admin/project/store')}}" method="POST">
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
                        <input type ="date"  name="inspection_date"  placeholder="Inspection Date" class="form-control place-color disableFuturedate"  />
                    </div>
                    <div class="">
                        <input  type="text" name="customer_email" placeholder="Customer Email" class="form-control place-color"  />
                    </div>
                    <select name="assigned_user_id" class="form-select add-select" aria-label="Default select example">
                        <option disabled selected>-Assign User-</option>
                        @foreach($listData as $key => $item)
                            <option value="{{$item->id}}">{{$item->userNames}}</option>
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

<!-- Filter Modal -->
 <div class="modal fade " id="myModalFilter" tabindex="-1" role="dialog">
    <div class="modal-dialog " role="document">
        <div class="modal-content add-project-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Add Filter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <!-- <img src="../assets/img/close-icon.png" alt=""> -->
                </button>
            </div>
            <form method="GET" id="projectSearchForm" action="{{URL::to('admin/project')}}">
                <div class="modal-body companyinfobody rm-companyinfobody-modified">
                    <div class="row">
                        <div class="col-md-4 companyinfobody rm-companyinfobody-modified">
                            <input type="date" class="form-control place-color" id="filter_created_date"  name="filter_created_date" placeholder="Date"  value="{{ request()->get('filter_created_date') }}"
                            />
                        </div>
                        <div class="col-md-4 companyinfobody rm-companyinfobody-select-modified">
                            <select  id="filter_project_status" name="filter_project_status" class="form-select add-select" aria-label="Default select example">
                                <option value="-1" disabled selected>Select Status</option>
                                <option value="2" {{ request()->get('filter_project_status') == 2 ? 'selected' : '' }}>Closed</option>
                                <option value="1" {{ request()->get('filter_project_status') == 1 ? 'selected' : '' }}>Open</option>
                            </select>
                        </div>
                        <div class="col-md-4 companyinfobody rm-companyinfobody-select-modified">
                            <select  id="filter_inspectors" name="filter_inspectors" class="form-select add-select" aria-label="Default select example">
                                <option value="-1" disabled selected>Select User</option>
                                    @foreach($listData as $key => $item)
                                        <option value="{{$item->id}}" {{ request()->get('filter_inspectors') == $item->id ? 'selected' : '' }}>{{$item->userNames}}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-save">Save </button>
                </div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection

@push("page_css")
    <style>
        .pac-container{
            z-index: 9999;
        }
        .edit-details {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }

        .edit-details .dropdown button {
            background: #ffffff !important;
        }
        button.btn-theme2.clear-btn {
            background: #fff;
            color: #00000082;
            border: 1px solid #00000029;
        }
    </style>
@endpush
@push("page_js")




<!-- <script src="{{asset('assets/js/moment.min.js')}}" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.0.10/datepicker.min.js" integrity="sha512-RCgrAvvoLpP7KVgTkTctrUdv7C6t7Un3p1iaoPr1++3pybCyCsCZZN7QEHMZTcJTmcJ7jzexTO+eFpHk4OCFAg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://code.jquery.com/ui/1.14.0/jquery-ui.js"></script> -->
<script>
    // Search Filter
    document.getElementById('clearFiltersBtn').addEventListener('click', function () {
        // Reset the form fields
        document.getElementById('projectSearchForm').reset();

        // Optionally, reset any custom selection (like select2, if using it)
        $('select').val('').trigger('change');

        // Redirect to the same page without any filters
        window.location.href = window.location.pathname;
    });
    // Function to get a URL parameter by name
    function getUrlParam(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    // Function to initialize the form fields based on the URL parameters
    function setFiltersFromUrl() {
        const customSearch = getUrlParam('custom_search');
        
        if (customSearch) {
            // Split the custom_search string into individual parameters
            const filters = customSearch.split('&');

            filters.forEach(function(filter) {
                const [key, value] = filter.split('=');

                // Set the form field value based on the key and value
                if (key === 'filter_created_date') {
                    document.querySelector('input[name="filter_created_date"]').value = decodeURIComponent(value);
                } else if (key === 'filter_project_status') {
                    document.querySelector('select[name="filter_project_status"]').value = decodeURIComponent(value);
                } else if (key === 'filter_inspectors') {
                    document.querySelector('select[name="filter_inspectors"]').value = decodeURIComponent(value);
                }
            });
        }
    }

    // Initialize the form fields when the page loads
    window.addEventListener('load', setFiltersFromUrl);
  document.getElementById('projectSearchForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent default form submission

    var createdDate = document.querySelector('input[name="filter_created_date"]').value;
    var projectStatus = document.querySelector('select[name="filter_project_status"]').value;
    var inspector = document.querySelector('select[name="filter_inspectors"]').value;

    // Build custom_search string
    var customSearch = '';
    if (createdDate) {
        customSearch += 'filter_created_date=' + encodeURIComponent(createdDate);
    }
    if (projectStatus) {
        if (customSearch) customSearch += '&';
        customSearch += 'filter_project_status=' + encodeURIComponent(projectStatus);
    }
    if (inspector) {
        if (customSearch) customSearch += '&';
        customSearch += 'filter_inspectors=' + encodeURIComponent(inspector);
    }

    // Get the current URL and add custom_search parameter
    var url = new URL(window.location.href);
    url.searchParams.set('custom_search', customSearch);  // Set or update custom_search

    // Redirect or send request with the updated URL
    window.location.href = url.toString();  // This will reload the page with the new filters
  });
</script>

    <script>
        
        $(document).ready(function () {
            $('#add_form').submit(function(){
                $(this).find('.btn-save').attr('disabled',true);
                $('#myModal').modal('toggle');
                $('#myModal').addClass('in');
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
    </script>
    
    <script>
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
    
    $(document).ready(function () {
        var currentDate = new Date();
        $('.disableFuturedate').datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true,
        startDate: "currentDate",
        minDate: currentDate
        }).on('changeDate', function (ev) {
        $(this).datepicker('hide');
        });
        $('.disableFuturedate').keyup(function () {
        if (this.value.match(/[^0-9]/g)) {
            this.value = this.value.replace(/[^0-9^-]/g, '');
        }
        });
    });
</script>
<script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAlUlyus8U80FZOXPzVHEeVEYHcJHsOrjU&callback=initAutocomplete&libraries=places&v=weekly"
      defer
    ></script>
@endpush