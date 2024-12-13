@extends('admin.master')
@section('content')
@section('title', 'Inspection Photo Tags')
<section class="container-fluid main-sec">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="user-type-sec">
                <div>
                    <h2>Inspection Photo Tags</h2>
                </div>
                <div class="d-flex align-items-center">
                    
                    <div class="me-3">
                        <button class="btn-theme2" data-bs-toggle="modal" data-bs-target="#importModal">+ Import</button>
                    </div>
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
                        <th>Tag Name</th>
                        <th>Inspection Photos</th>
                        <th >Quantity</th>
                        <th class="text-start">Required</th>
                        <th class="text-start">Price</th>
                        <th id="action">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data['tagsData']['records'] AS $key => $item)
                        <tr>
                            <td>{{$item['name']}}</td>
                            <td>{{$item['c1_name']}}</td>
                            <td >{{!empty($item['has_qty']) ? 'Yes' : 'No'}}</td>
                            <td class="text-start">{{!empty($item['is_required']) ? 'Yes' : 'No'}}</td>
                            <td class="color-blue text-start">{{!empty($item['price']) ? '$'.$item['price']: '$0'}}</td>
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
                                                    onclick="editRow({{$item->id}})"
                                            >Edit</a
                                            >
                                        </li>
                                        <li>
                                            <a
                                                    class="dropdown-item"
                                                    href="#"
                                                    onclick="deleteRow({{$item->id}})"
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
<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-dialog-centered project-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Upload CSV

                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <!-- <img src="../assets/img/close-icon.png" alt=""> -->
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="drop-cvs">
                            <div class="cvs-border">
                                <div class="img-cvs">
                                    <img src="{{asset('assets/images/csvs-img.png')}}" alt="...">
                                </div>
                                <div class="cvs-import-tile text-center">
                                    <p>Drag and drop to upload your CSV file </p>
                                    <div class="button-wrapper">
                                        <span class="label">
                                            Upload File
                                        </span>
                                        <input type="file" name="upload" id="upload" class="upload-box" placeholder="Upload File">                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="switch-btn">
                    <button type="button" class="btn btn-sm btn-toggle" data-toggle="button"
                            aria-pressed="false" autocomplete="off">
                        <div class="handle"></div>
                    </button>
                    <span>Required</span>
                </div>
                <input type="hidden" name="is_required" value="false" class="test_class"/>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-save">Save </button>
            </div>
        </div>
    </div>
</div>
<!-- Add Inspection Photos Tags-->
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered project-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Photo Tag

        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <!-- <img src="../assets/img/close-icon.png" alt=""> -->
        </button>
      </div>
      <div class="modal-body">
      <form id="add_form" action="{{URL::to('admin/photo_tags/store')}}" method="POST">
            {{csrf_field()}}
            <div class="row">
                <div class="col-md-12 companyinfobody rm-companyinfobody-modified">
                    <input class="form-control place-color" name="name" type="text" placeholder="Tag Name">
                </div>
                <div class="col-md-12 companyinfobody rm-companyinfobody-modified">
                    <input class="form-control place-color" name="annotation" type="text" placeholder="Default Annotation Note">
                </div>
                <div class="col-md-6 companyinfobody rm-companyinfobody-modified">
                    <select  name="ref_id"  class="form-select add-select">
                        <option disabled selected>Photo View</option>
                        @foreach($data['photoViews'] AS $key => $item)
                            <option value="{{$item['category2_id']}}">{{$item['category2_name']}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 companyinfobody rm-companyinfobody-modified">
                    <select name="spec_type" class="form-select add-select spec_type" id="">
                        <option disabled selected>Spec Type</option>
                        @forelse($data['specTypes'] AS $key => $item)
                            <option value="{{$key}}">{{$item}}</option>
                        @empty
                            <option disabled>No Options found</option>
                        @endforelse
                    </select>
                </div>
                <div class="col-md-6 companyinfobody rm-companyinfobody-modified build_spec_group" style="display: none; ">
                    <select name="build_spec" class=" form-control select-form build_spec" id="">
                        <option disabled selected>Build Spec</option>
                    </select>
                </div>
                <div class="col-md-6 companyinfobody rm-companyinfobody-modified hover_field_type_group">
                    <select name="hover_field_type_id" class="form-select add-select hover_field_type" id="">
                        <option disabled selected>Hover Field Type</option>

                        @forelse($data['hover_field_types'] AS $key => $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @empty
                            <option disabled>No Options found</option>
                        @endforelse
                    </select>
                </div>
                <div class="col-md-6 companyinfobody rm-companyinfobody-select-modified hover_field_group"
                        style="display: none; ">
                    <select name="hover_field_id" class="form-control add-select select-form hover_field" id="">
                        <option disabled selected>Select Hover Field</option>
                    </select>
                </div>

                <div class="col-md-6 companyinfobody rm-companyinfobody-modified">
                    <select name="has_qty"  class="form-select add-select" id="sel1">
                        <option disabled selected>Quantity</option>
                        <option value="1">YES</option>
                        <option value="0">NO</option>
                    </select>
                </div>

                <div class="col-md-6 companyinfobody rm-companyinfobody-modified">
                    <input class="form-control place-color" name="price" type="text"  placeholder="Price" readonly>
                </div>

                <div class="col-md-6 companyinfobody rm-companyinfobody-modified">
                    <input class="form-control place-color" name="material_cost" type="text" placeholder="Material">
                </div>
                <div class="col-md-6 companyinfobody rm-companyinfobody-modified">
                    <input class="form-control place-color" name="equipment_cost" type="text" placeholder="Equipment">
                </div>
                <div class="col-md-6 companyinfobody rm-companyinfobody-modified">
                    <input class="form-control place-color" name="margin" type="text" placeholder="Margin %">
                </div>
                <div class="col-md-6 companyinfobody rm-companyinfobody-modified">
                    <select name="uom_id"  class="form-select add-select" id="uom_id">
                        <option disabled selected>Select UOM</option>

                        @forelse($data['uoms'] AS $key => $item)
                            <option value="{{$item->id}}">{{$item->title}}</option>
                        @empty
                            <option disabled>No Options found</option>
                        @endforelse
                    </select>
                </div>
                <div class="col-md-6 companyinfobody rm-companyinfobody-modified">
                    <input class="form-control place-color" name="labor_cost" type="text" placeholder="Labor">
                </div>
                <div class="col-md-12 companyinfobody rm-companyinfobody-modified">
                    <input class="form-control place-color" name="supervision_cost" type="text" placeholder="Supervision">
                </div>

            </div>
            <div class="modal-footer">
                <div class="switch-btn">
                    <button type="button" class="btn btn-sm btn-toggle" data-toggle="button"
                            aria-pressed="false" autocomplete="off">
                        <div class="handle"></div>
                    </button>
                    <span>Required</span>
                </div>
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
                <h5 class="modal-title" id="exampleModalLabel">Edit Photo Tag</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <!-- <img src="../assets/img/close-icon.png" alt=""> -->
                </button>
            </div>
            <form id="update_form" action="{{URL::to('admin/photo_tags/update')}}" method="POST">
                {{csrf_field()}}
                <div class="modal-body companyinfobody rm-companyinfobody-modified">
                <div class="row">
                        <div class="col-md-12 companyinfobody rm-companyinfobody-modified">
                            <input class="form-control place-color" name="name" type="text" placeholder="Tag Name">
                        </div>
                        <div class="col-md-12 companyinfobody rm-companyinfobody-modified">
                            <input class="form-control place-color" name="annotation" type="text" placeholder="Default Annotation Note">
                        </div>
                        <div class="col-md-6 companyinfobody rm-companyinfobody-modified">
                            <select  name="ref_id"  class="form-select add-select">
                                <option disabled selected>Photo View</option>
                                @foreach($data['photoViews'] AS $key => $item)
                                    <option value="{{$item['category2_id']}}">{{$item['category2_name']}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 companyinfobody rm-companyinfobody-modified">
                            <select name="spec_type" class="form-select add-select spec_type" id="">
                                <option disabled selected>Spec Type</option>
                                @forelse($data['specTypes'] AS $key => $item)
                                    <option value="{{$key}}">{{$item}}</option>
                                @empty
                                    <option disabled>No Options found</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="col-md-6 companyinfobody rm-companyinfobody-modified build_spec_group" style="display: none; ">
                            <select name="build_spec" class=" form-control select-form build_spec" id="">
                                <option disabled selected>Build Spec</option>
                            </select>
                        </div>
                        <div class="col-md-6 companyinfobody rm-companyinfobody-modified hover_field_type_group">
                            <select name="hover_field_type_id" class="form-select add-select hover_field_type" id="">
                                <option disabled selected>Hover Field Type</option>

                                @forelse($data['hover_field_types'] AS $key => $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @empty
                                    <option disabled>No Options found</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="col-md-6 companyinfobody rm-companyinfobody-select-modified hover_field_group"
                             style="display: none; ">
                            <select name="hover_field_id" class="form-control add-select select-form hover_field" id="">
                                <option disabled selected>Select Hover Field</option>
                            </select>
                        </div>

                        <div class="col-md-6 companyinfobody rm-companyinfobody-modified">
                            <select name="has_qty"  class="form-select add-select" id="sel1">
                                <option disabled selected>Quantity</option>
                                <option value="1">YES</option>
                                <option value="0">NO</option>
                            </select>
                        </div>

                        <div class="col-md-6 companyinfobody rm-companyinfobody-modified">
                            <input class="form-control place-color" name="price" type="text"  placeholder="Price" readonly>
                        </div>

                        <div class="col-md-6 companyinfobody rm-companyinfobody-modified">
                            <input class="form-control place-color" name="material_cost" type="text" placeholder="Material">
                        </div>
                        <div class="col-md-6 companyinfobody rm-companyinfobody-modified">
                            <input class="form-control place-color" name="equipment_cost" type="text" placeholder="Equipment">
                        </div>
                        <div class="col-md-6 companyinfobody rm-companyinfobody-modified">
                            <input class="form-control place-color" name="margin" type="text" placeholder="Margin %">
                        </div>
                        <div class="col-md-6 companyinfobody rm-companyinfobody-modified">
                            <select name="uom_id"  class="form-select add-select" id="uom_id">
                                <option disabled selected>Select UOM</option>

                                @forelse($data['uoms'] AS $key => $item)
                                    <option value="{{$item->id}}">{{$item->title}}</option>
                                @empty
                                    <option disabled>No Options found</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="col-md-6 companyinfobody rm-companyinfobody-modified">
                            <input class="form-control place-color" name="labor_cost" type="text" placeholder="Labor">
                        </div>
                        <div class="col-md-12 companyinfobody rm-companyinfobody-modified">
                            <input class="form-control place-color" name="supervision_cost" type="text" placeholder="Supervision">
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                <div class="switch-btn">
                    <button type="button" class="btn btn-sm btn-toggle" data-toggle="button"
                            aria-pressed="false" autocomplete="off">
                        <div class="handle"></div>
                    </button>
                    <span>Required</span>
                </div>
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
                <div class="switch-btn">
                    <button type="button" class="btn btn-sm btn-toggle" data-toggle="button"
                            aria-pressed="false" autocomplete="off">
                        <div class="handle"></div>
                    </button>
                    <span>Required</span>
                </div>
        <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" id="confirmDeleteButton" class="btn btn-save">Delete </button>
      </div>
    </div>
  </div>
</div> 

@endsection

@push("page_css")
    <style>
        .cvs-border {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 13px;
            padding: 9px 0px;
        }

        .img-cvs img {
            width: 100px;
        }
        .button-wrapper {
            position: relative;
            width: 150px;
            text-align: center;
            /* margin: 20% auto; */
            margin: auto;
            padding: 14px 0px;
        }

        .button-wrapper span.label {
            position: relative;
            z-index: 0;
            display: inline-block;
            width: 100%;
            background: #1282f2;
            cursor: pointer;
            color: #fff;
            padding: 10px 0;
            text-transform: uppercase;
            font-size: 12px;
            border-radius: 12px;
        }

        #upload {
            display: inline-block;
            position: absolute;
            z-index: 1;
            width: 100%;
            height: 50px;
            top: 0;
            left: 0;
            opacity: 0;
            cursor: pointer;
        }
    </style>
@endpush
@push("page_js")
    <script>
        var updateUrl = "{{URL::to('admin/photo_tags/update')}}";
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

            // COST CALCULATION

            $('input[name="material_cost"], input[name="labor_cost"], input[name="equipment_cost"], input[name="supervision_cost"], input[name="margin"]').on('input', function (e){

                let $form = $(this).closest('form');

                let material_cost    = +$form.find('input[name="material_cost"]').val();
                let labor_cost       = +$form.find('input[name="labor_cost"]').val();
                let equipment_cost   = +$form.find('input[name="equipment_cost"]').val();
                let supervision_cost = +$form.find('input[name="supervision_cost"]').val();
                let margin           = +$form.find('input[name="margin"]').val();

                $form.find('input[name="price"]').val(((material_cost + labor_cost + equipment_cost + supervision_cost)/ (1- (margin/100)) ).toFixed(2));
            });

            // Field Hover Calculation

            function isRequiredToggle(el,state = null){

                let isRequiredEl , isRequiredVal ;
                isRequiredEl = el.closest('.modal-footer').find('input[name="is_required"]');

                if(state){
                    isRequiredVal = state;
                }else{
                    isRequiredVal = !(isRequiredEl.val() === 'false' ? false : true) ; // Toggling

                }

                if(isRequiredVal){
                    el.addClass('active');
                }

                el.attr('aria-pressed',isRequiredVal);
                isRequiredEl.val(isRequiredVal);
            }


                var selectedBuildSpec = '';

                $('.spec_type').on('change', function (e) {
                /*console.log('spec_type change');
                console.log(e.target);*/

                var id = $(this).val();
                var $input = $(this);
                $.getJSON('{{URL::to('admin/crm/spec_list')}}/' + id, function (response) {
                    var items = [];

                    items.push("<option disabled selected>Select Build Spec</option>");

                    /*Adding to build spec*/
                    $.each(response.data, function (key, val) {
                        items.push("<option value='" + val + "'>" + val + "</option>");
                    });


                    $input.closest('.modal-body').find('.build_spec').empty().append(items.join(""));
                    $input.closest('.modal-body').find('.build_spec_group').show();

                    /*Selecting on Update dropdown*/
                    $('#update_form select[name="build_spec"] option').each(function (key, item) {
                        if (selectedBuildSpec == $(item).val()) {
                            $(item).prop('selected', true);
                        }
                    });

                });
                });

                var selectedHoverField = '';
                $('.hover_field_type').on('change', function (e) {
                // console.log('hover_field_type change');
                // console.log(e.target);

                var id = $(this).val();
                var $input = $(this);
                $.getJSON('{{URL::to('admin/hover/field_list')}}/' + id, function (response) {
                    var items = [];
                    items.push("<option disabled selected>Select Hover Field</option>");

                    /*Adding to build spec*/
                    $.each(response.data, function (key, val) {
                        items.push("<option value='" + val.id + "'>" + val.name + "</option>");
                    });

                    $input.closest('.modal-body').find('.hover_field').empty().append(items.join(""));
                    $input.closest('.modal-body').find('.hover_field_group').show();

                    /*Selecting on Update dropdown*/
                    $('#update_form select[name="hover_field_id"] option').each(function (key, item) {
                        if (selectedHoverField == $(item).val()) {
                            $(item).prop('selected', true);
                        }
                    });

                });
                });
        })

        function editRow(id) {
            var $editModal = $('#editModal');

            // Fetch data via AJAX
            $.ajax({
               url: "{{URL::to('admin/photo_tags/editTagDetails/')}}/" + id,
               method: "GET",
               data: '',
               success: function (response) {
                   $('#update_form').attr('action', updateUrl + '/' + response.data.id);
   
                   $('#update_form input[name="name"]').val(response.data.name);
                   $('#update_form input[name="annotation"]').val(response.data.annotation);
                   $('#update_form input[name="price"]').val(response.data.price);

                   $('#update_form select[name="uom_id"] option').each(function (key,item) {
                       if($(item).val() == response.data.uom_id){
                           $(item).prop('selected', true);
                       }
                   });

                   $('#update_form input[name="material_cost"]').val(response.data.material_cost);
                   $('#update_form input[name="labor_cost"]').val(response.data.labor_cost);
                   $('#update_form input[name="equipment_cost"]').val(response.data.equipment_cost);
                   $('#update_form input[name="supervision_cost"]').val(response.data.supervision_cost);
                   $('#update_form input[name="margin"]').val(response.data.margin);

                   $('#update_form select[name="has_qty"] option').each(function (key, item) {
                       if ($(item).val() == response.data.has_qty) {
                           $(item).prop('selected', true);
                       }
                   });

                   if (response.data.is_required) {
                       isRequiredToggle($('#update_form .btn-toggle'), response.data.is_required > 0 ? 'true' : 'false');
                   }

                   $('#update_form select[name="ref_id"] option').each(function (key, item) {
                       if ($(item).val() == response.data.ref_id) {
                           $(item).prop('selected', true);
                       }
                   });

                   // console.log('response.data.build_spec',response.data.hover_field_type_id ,response.data.hover_field_id);

                   selectedBuildSpec = response.data.build_spec;
                   selectedHoverField = response.data.hover_field_id;
                   if(response.data.spec_type && response.data.build_spec >= 0){
                       /*Selecting Spec Type and triggering change*/
                       $('#update_form select[name="spec_type"] option').each(function (key, item) {
                           if ($(item).val() == response.data.spec_type) {
                               $(item).prop('selected', true);
                               $('#update_form select[name="spec_type"]').trigger('change');
                           }
                       });
                   }

                   if(response.data.hover_field_type_id && response.data.hover_field_id > 0 ){
                       $('#update_form select[name="hover_field_type_id"] option').each(function (key, item) {
                           if ($(item).val() == response.data.hover_field_type_id) {

                               $(item).prop('selected', true);
                               $('#update_form select[name="hover_field_type_id"]').trigger('change');
                           }
                       });
                   }

                   $editModal.modal('show');
               },
               error: function () {
                   alert("No Network");
               }
           });

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
                url: '{!! url('admin/photo_tags/delete') !!}/' + deleteId,
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
                    // Handle error if necessary
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