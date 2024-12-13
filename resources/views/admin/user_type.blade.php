@extends('admin.master')
@section('content')
@section('title', 'User Types')
<section class="container-fluid main-sec">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="user-type-sec">
                <div>
                    <h2>User Types</h2>
                </div>
                <div class="d-flex align-items-center">
                    <!-- <div class="me-3 user-type-input">
                        <div class="input-group flex-nowrap">
											<span class="input-group-text" id="addon-wrapping">
												<img src="../assets/img/search-icon.png" alt=""
                                                /></span>
                            <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Search"
                                    aria-label="Search"
                                    aria-describedby="addon-wrapping"
                            />
                        </div>
                    </div> -->
                    <!-- <div class="me-3">
                        <button class="btn-theme2">Filters</button>
                    </div> -->
                    <div>
                        <!-- <button class="btn-theme">+ Add New</button> -->
                        <button class="btn-theme"  data-bs-toggle="modal" data-bs-target="#myModal">+ Add New</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="table-responsive ">
                
                <table
                        id="example"
                        class="table data-table responsive"
                        style="width: 100%"
                >
                    <thead>
                    <tr>
                        <th>User Type</th>
                        <th>Assigned Users</th>
                        <th id="action">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($records ))
                        @foreach ($records['data'] AS $item)
                    <tr id="row-{{$item['id']}}">
                        <td>{{$item['title']}}</td>
                        <td>
                            {{$item['users']}}
                        </td>

                        <td>
                            <div class="dropdown">
                                <button
                                        class="btn btn-secondary dropdown-toggle"
                                        type="button"
                                        id="actionMenu1"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                        data-bs-display="static"
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
                                                onclick="editRow({{$item['id']}})"
                                        >Edit</a
                                        >
                                    </li>
                                    <li>
                                        <a
                                                class="dropdown-item"
                                                href="#"
                                                onclick="deleteRow({{$item['id']}})"
                                        >Delete</a
                                        >
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                        @endforeach
                    @endif
                    <!-- Add more rows as needed -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Add Modal -->
<div class="modal fade " id="myModal"  tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered project-modal">
        <!-- Modal content-->
        <div class="modal-content">
            <form id="add_form" action="{{URL::to('admin/user_type/store')}}" method="POST">
                {{csrf_field()}}
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add User Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <!-- <img src="../assets/img/close-icon.png" alt=""> -->
                    </button>
                </div>
                <div class="modal-body companyinfobody rm-companyinfobody-modified">
                    <div class="row">
                        <div class="col-md-12 companyinfobody rm-companyinfobody-modified">
                            <!-- <label>User Type Name</label> -->
                            <input type="text" name="user_type"  placeholder="User Type Name" class="form-control place-color" aria-describedby="emailHelp" maxlength="30"  autocomplete="off"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-save">Save </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Modal -->
<div class="modal fade new-modal" id="editModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered project-modal">
        <!-- Modal content-->
        <div class="modal-content">
            <form id="update_form" action="{{URL::to('admin/user_type/update')}}" method="POST">
                {{csrf_field()}}
                <input type="hidden" name="page" value="{{\Request::input('page',1)}}">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit User Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <!-- <img src="../assets/img/close-icon.png" alt=""> -->
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group new-input-modal">
                        <!-- <label>User Type Name</label> -->
                    <input type="text" name="user_type" placeholder="User Type Name" class="form-control place-color" aria-describedby="emailHelp"  autocomplete="off"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-save">Save </button>
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
        Are you sure you want to delete?
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
        .data-table .btn {
        /* width: 30%; */
    }
    </style>
@endpush
@push("page_js")
    <script>
        var updateUrl = "{{URL::to('admin/user_type/update')}}";
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
                // scrollY: "400px",   // Set the height for scrolling
                scrollCollapse: true,
        
            });
        
        })

        function editRow(id) {
            var $editModal = $('#editModal');
            $.ajax({
                url: "{{URL::to('admin/user_type/editFormDetails')}}/" + id,
                method: "GET",
                data: '',
                success: function (response) {
                    var data = response.data ;

                    $('#update_form').attr('action',updateUrl+'/'+response.data.id);
                    $('#update_form input[name="user_type"]').val(response.data.title);
                    $editModal.modal('show');
                },
                error: function () {
                    alert("No Network");
                }
            });
        }

        //Delete 
        var deleteId; // Store the id to be deleted

        // Delete Row Function
        function deleteRow(id) {
            deleteId = id;  // Store the id to be used for deletion
            $('#deleteConfirmationModal').modal('show');  // Show the confirmation modal
        }

        // Handle delete confirmation button click
        $('#confirmDeleteButton').on('click', function() {
            $.ajax({
                url: '{!! url('admin/user_type/delete') !!}/' + deleteId,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  // Add CSRF token to the headers
                },
                success: function(response) {
                    $('#deleteConfirmationModal').modal('hide');  // Hide the modal after deletion
                    window.location.reload();  // Reload the page upon successful deletion
                },
                error: function(response) {
                    console.log(response.responseJSON.data.id);  // Log the error response if needed
                    $('#deleteConfirmationModal').modal('hide');  // Hide the modal even on error
                    window.location.reload();  // Reload the page
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