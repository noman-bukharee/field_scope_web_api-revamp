@extends('admin.master')
@section('content')
@section('title', 'Photo Views')
<section class="container-fluid main-sec">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="user-type-sec">
                <div>
                    <h2>Photo Views</h2>
                </div>
                <div class="d-flex align-items-center">
                    <div >
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
                        <th>Photo View</th>
                        <th>Inspection Area</th>
                        <th class="text-start">Quantity</th>
                        <th >Thumbnail</th>
                        <th id="action">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data['photoViewData'] AS $key => $item)
                        <tr>
                            <td>{{$item['name']}}</td>
                            <td>{{$item['parent']}}</td>
                            <td class="text-start">{{$item['min_quantity']}}</td>
                            <td>{{$item['thumbnail']}}</td>
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
                   
                    <!-- Add more rows as needed -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<!-- Add PhotoView-->
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered project-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Photo View

        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <!-- <img src="../assets/img/close-icon.png" alt=""> -->
        </button>
      </div>
      <div class="modal-body">
        <form id="add_form" action="{{URL::to('admin/photo_views/store')}}" method="POST">
            {{csrf_field()}}
            <div class="row">
                <div class="col-md-12 companyinfobody rm-companyinfobody-modified">
                    <input name="name" type="text" class="form-control place-color" placeholder="Photo View" />
                </div>

                <div class="col-md-12 companyinfobody rm-companyinfobody-select-modified">
                    <select name="parent_id" id="sel1" class="form-select add-select">
                        <option selected disabled> Inspection Area</option>
                        @foreach($data['area'] AS $item)
                            <option value="{{$item['id']}}">{{$item['name']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 companyinfobody rm-companyinfobody-modified">
                    <input name="min_quantity" type="text" placeholder="Quantity"  class="form-control place-color">
                </div>
                <div class="col-md-6 companyinfobody rm-companyinfobody-select-modified ">
                    <select name="thumbnail" class="form-select add-select" data-placeholder="Thumbnail"
                            @if($data['thumbnailCount'] > 0) disabled @endif>
                        <option value="" disabled selected>Default
                            Thumbnail @if($data['thumbnailCount'] > 0) (Already Set) @endif</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
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
</div>
<!-- Edit Modal -->
<div class="modal fade new-modal" id="editModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered project-modal">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Photo View</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <!-- <img src="../assets/img/close-icon.png" alt=""> -->
                </button>
            </div>
            <form id="update_form" action="{{URL::to('admin/photo_view/update')}}" method="POST">
                {{csrf_field()}}
                <div class="modal-body companyinfobody rm-companyinfobody-modified">
                <div class="row">
                    <div class="col-md-12 companyinfobody rm-companyinfobody-modified">
                        <input name="name" type="text" class="form-control place-color" placeholder="Photo View">
                    </div>

                    <div class="col-md-12 companyinfobody rm-companyinfobody-select-modified">
                        <select name="parent_id" id="sel1" class="form-select add-select">
                            <option selected disabled> Inspection Areas</option>
                            @foreach($data['area'] AS $item)
                                <option value="{{$item['id']}}">{{$item['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 companyinfobody rm-companyinfobody-modified">
                        <input name="min_quantity" type="text" placeholder="Quantity"  class="form-control place-color">
                    </div>
                    <div class="col-md-6 companyinfobody rm-companyinfobody-select-modified ">
                        <select name="thumbnail" class="form-select add-select" data-placeholder="Default Thumbnail"
                                @if($data['thumbnailCount'] > 0) disabled @endif>
                            <option value="" disabled selected>Default
                                Thumbnail @if($data['thumbnailCount'] > 0) (Already Set) @endif</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
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

    </style>
@endpush
@push("page_js")
    <script>
        var updateUrl = "{{URL::to('admin/photo_views/update')}}";
        let thumbnailCount = {{$data['thumbnailCount']}};
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

        // Edit Row
        function editRow(id) {
            
            var $editModal = $('#editModal');
            
                $.ajax({
                    url: "{{URL::to('admin/photo_views/editPhotoDetails')}}/" + id,
                    method: "GET",
                    data: '',
                    success: function (response) {
                        var data = response.data.company_group[0];
                        $('#update_form').attr('action', updateUrl + '/' + data.id);
                        $('#update_form input[name="name"]').val(data.name);
                        $('#update_form input[name="min_quantity"]').val(data.min_quantity);

                        $('#update_form select[name="parent_id"] option').each(function (key,item) {
                            if($(item).val() == data.parent_id){
                                $(item).prop('selected', true);
                            }
                        });


                        $('#update_form select[name="thumbnail"] option').each(function (key,item) {

                            if($(item).val() == data.thumbnail){
                                $(item).prop('selected', true);
                            }
                        });

                        /** IF tag which is getting update, doesn't have thumbnail 'YES' disable this fieldd
                         * Meaning: thumbnail only can be 'NO' (un-set) */
                        let $thumbnailSelect = $('#update_form select[name="thumbnail"]');
                        if (response.data.company_group.thumbnail == 0 && thumbnailCount > 0) {
                            $thumbnailSelect.prop('disabled', true);
                        }else{
                            $thumbnailSelect.prop('disabled', false);
                        }


                        $('#update_form select[name="parent_id"]').trigger('change');
                        $('#update_form select[name="thumbnail"]').trigger('change');

                        $editModal.modal('show');
                    },
                    error: function () {
                        alert("No Network");
                    }
                });
            // Implement edit functionality here
        }

        // Delete Row
        var deleteId; // Store the id to be deleted

        // Delete Row Function
        function deleteRow(id) {
            deleteId = id;  // Store the id to be used for deletion
            $('#deleteConfirmationModal').modal('show');  // Show the confirmation modal
        }

        // Handle delete confirmation button click
        $('#confirmDeleteButton').on('click', function() {
            $.ajax({
                url: '{!! url('admin/delete/photo_views') !!}/' + deleteId,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  // Add CSRF token to the headers
                },
                dataType: 'JSON',
                success: function(response) {
                    $('#deleteConfirmationModal').modal('hide');  // Hide the modal after deletion
                    window.location.reload();  // Reload the page upon successful deletion
                },
                error: function() {
                    // Handle error
                    $('#deleteConfirmationModal').modal('hide');  // Hide the modal even on error
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