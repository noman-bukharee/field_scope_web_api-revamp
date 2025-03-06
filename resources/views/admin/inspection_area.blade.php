@extends('admin.master')
@section('content')
@section('title', 'Inspection Area')
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">

<section class="container-fluid main-sec">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="user-type-sec">
                <div>
                    <h2>Inspection Area</h2>
                </div>
                
                <div class="d-flex align-items-center">
                    <div>
                        <button class="btn-theme" data-bs-toggle="modal" data-bs-target="#exampleModal">+ Add New</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mt-3">
            <div class="table-responsive">
                <table
                        id="example"
                        class="table data-table"
                        style="width: 100%"
                >
                    <thead>
                    <tr>
                        <th>Inspection Area</th>
                        <th>Area Type</th>
                        <th>User Type</th>
                        <th id="action">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data['categories'] AS $key => $item)
                        <tr>
                            <td>{{$item->name }}</td>
                            <td>{{($item->type == 1) ? 'Required': 'Inspection Photos'}}</td>
                            <td>{{!empty($item->company_group_titles) ? $item->company_group_titles : 'N.A' }}</td>
                            <td>
                                <div class="dropdown">
                                    <button
                                            class="btn btn-secondary dropdown-toggle"
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
                                                    onclick="editRow({{$item->id }})"
                                            >Edit</a
                                            >
                                        </li>
                                        <li>
                                            <a
                                                    class="dropdown-item"
                                                    href="#"
                                                    onclick="deleteRow({{$item->id }})"
                                            >Delete</a
                                            >
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                   
                    <!-- Add more rows as needed -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Add Area-->
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered project-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Inspection Area
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <!-- <img src="../assets/img/close-icon.png" alt=""> -->
        </button>
      </div>
      <div class="modal-body">
        <form id="add_form" action="{{URL::to('admin/inspection_area/store')}}" method="POST">
            {{csrf_field()}}
            <div class="">
                <input type="text" name="name" placeholder="Inspection Area Name" class="form-control place-color" id="exampleInputEmail1" aria-describedby="emailHelp">
            </div>
            <div id="add-select" class="slect-option">
                <select id="choices-multiple-remove-button1" name="company_group_id[]" class="form-select add-select selectpicker" title="Select user type" aria-label="Default select example" multiple>
                    @foreach($data['companyGroups'] AS $key => $item)
                        <option value="{{$item->id}}">{{$item->title}}</option>
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
</div>
<!-- Edit Modal -->
<div class="modal fade new-modal" id="editModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered project-modal">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Inspector User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <!-- <img src="../assets/img/close-icon.png" alt=""> -->
                </button>
            </div>
            <form id="update_form" action="{{URL::to('admin/inspection_area/update')}}" method="POST">
                {{csrf_field()}}
                <input type="hidden" name="page" value="{{\Request::input('page',1)}}">
                <div class="modal-body companyinfobody rm-companyinfobody-modified">
                    <div class="">
                    <input type="text" name="name" placeholder="Inspection Area Name" class="form-control place-color" id="exampleInputEmail1" aria-describedby="emailHelp">
                </div>
                <div id="add-select" class="slect-option">
                    <select id="choices-multiple-remove-button" name="company_group_id[]" class="form-select add-select selectpicker" aria-label="Default select example" multiple>
                        <option>User Type</option>
                        @foreach($data['companyGroups'] AS $key => $item)
                            <option value="{{$item->id}}">{{$item->title}}</option>
                        @endforeach
                    </select>
                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-save">Update </button>
                </div>
            </form>
        </div>

    </div>
</div> 
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered project-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete the user?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" id="confirmDeleteButton" class="btn btn-save">Delete </button>
      </div>
    </div>
  </div>
</div>   
@endsection
@push("page_css")
    <style>
        .dropdown.bootstrap-select.show-tick.form-select.add-select {
            width: 100%;
            padding: 3px 0px;
        }
        .filter-option-inner-inner {
            font-size: 14px;
        }
        .slect-option .dropdown-menu.show {
            height: 175px !important;
            width: 100px;
        }
        .bootstrap-select.show-tick .dropdown-menu li a span.text,.bootstrap-select .dropdown-toggle .filter-option-inner-inner,.filter-option-inner-inner {
            font-family: 'EuclidSquare-Light' !important;
            font-size: 14px;
        }
        
        .bootstrap-select.show-tick .dropdown-menu>li:last-child a{
            color:#000 !important;
        }
        input[placeholder] {
            font-family: 'EuclidSquare-Light';
        }
        #add-select .bootstrap-select>.dropdown-toggle.bs-placeholder:focus{
            border: none;
            outline: none !important;
        }
        #add-select .bootstrap-select .dropdown-toggle:focus {
            outline: none !important;
            border: none;
        }
        .dropdown-menu>li:first-child a{
            color:#000 !important
        }
    </style>
@endpush
@push("page_js")

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

<script>
        var updateUrl = "{{URL::to('admin/inspection_area/update')}}";

        $(document).ready(function() {
            // To style all selects
            $('.selectpicker').selectpicker();

            // Initialize DataTable
            $('#example').DataTable({
                paging: true,
                searching: false,
                ordering: true,
                info: true,
                pageLength: 5,
                responsive: true,
                language: {
                    info: "Page _PAGE_ of _PAGES_",
                },
                scrollY: "400px", 
                scrollCollapse: true,
            });

        });
        function editRow(id) {
            var $editModal = $('#editModal');
            var selectElement = $('#choices-multiple-remove-button');

            // Clear the existing options before adding new ones
            selectElement.html(''); // Clear all options
            selectElement.selectpicker('destroy'); // Destroy the Bootstrap select instance

            let existingIds = new Set(); // Initialize existing IDs set

            // Fetch data via AJAX
            $.ajax({
                url: "{{URL::to('admin/inspection_area/editAreaDetails')}}/" + id,
                method: "GET",
                success: function(response) {
                    var data = response.data;

                    // Set the form action URL and input value
                    $('#update_form').attr('action', updateUrl + '/' + data.company_group[0].id);
                    $('#update_form input[name="name"]').val(data.company_group[0].name);

                    // Now add all options from the AJAX response
                    if (Array.isArray(data.company_group) && data.company_group.length > 0) {
                        
                        // First, add options from the AJAX response
                        data.company_group.forEach(function(group) {
                            if (!existingIds.has(group.cg_id)) { // Check for duplicate IDs
                                existingIds.add(group.cg_id); // Add to Set
                                var newOption = new Option(group.user_group_title, group.cg_id, true, true);
                                selectElement.append(newOption);
                            }
                        });
                    } else {
                        console.error('Invalid company_group data:', data.company_group);
                    }

                    // Now, process the options from the Blade template
                    @foreach($data['companyGroups'] AS $key => $item)
                        if (!existingIds.has({{ $item->id }})) { // Check for duplicates
                            existingIds.add({{ $item->id }}); // Add to Set
                            var option = new Option("{{ $item->title }}", "{{ $item->id }}");
                            selectElement.append(option);
                        }
                    @endforeach

                    // Refresh the Bootstrap Select UI after adding new options
                    selectElement.selectpicker();

                    // Show the modal after processing the data
                    $editModal.modal('show');
                },
                error: function() {
                    alert("No Network");
                }
            });

        }

        // Delete Row
        var deleteId; // Store the id to be deleted

        // Delete Row Function
        function deleteRow(id) {
            deleteId = id;  // Set the id to be used later for deletion
            $('#deleteConfirmationModal').modal('show');  // Show the confirmation modal
        }

        // Handle delete confirmation button click
        $('#confirmDeleteButton').on('click', function() {
            $.ajax({
                url: '{!! url('admin/delete/inspection_area') !!}/' + deleteId,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  // Add CSRF token to the headers
                },
                dataType: 'JSON',
                success: function(response) {
                    $('#deleteConfirmationModal').modal('hide'); // Hide the modal after deletion
                    window.location.reload(); // Reload the page upon successful deletion
                },
                error: function() {
                    // Handle error if needed
                }
            });
        });

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
@endpush