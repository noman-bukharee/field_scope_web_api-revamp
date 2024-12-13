@extends('admin.master') 
@section('content') 
@section('title', 'Questionnaire Management') 
<section class="container-fluid main-sec">
    <form method="POST" action="{{url('admin/questionnaire/update')}}" id="editQuestionForm">
    {{csrf_field()}}  
        <div class="row">
            <div class="col-12 mt-5">
                <div class="user-type-sec">
                    <div>
                        <h2>{{ !empty($data['query']) ? "Edit": "Add" }}  Question</h2>
                    </div>
                    <div class="d-flex flex-wrap align-items-center">
                        <!-- Buttons for different modals -->
                        <a class="btn-theme2 me-3 questionnaire-btn" href="{{ URL::to('admin/questionnaire') }}">
                            Cancel
                        </a>
                        <button type="submit" class="btn-theme" type="button"  data-bs-toggle="modal" data-bs-target="#myModal">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-12 col-md-7 col-lg-8 col-xl-7">
                <div class="addquestion-section">
                    <a href="" class="color-light" data-bs-toggle="modal"
                    data-bs-target="#companyLogoModal">Add Help Photo</a>
                    <div class="mb-3">
                        <div class="d-flex align-center justify-content-between">
                        <div>
                            <label for="" class="color-blue font-22 mb-3"
                            >Question</label
                            >
                        </div>
                        <div class="d-flex switch-btn">
                            <div>
                                <label class="color-light me-2">Required</label>
                            </div>
                            <div class="form-check form-switch">
                                <input
                                    class="form-check-input btn-toggle"
                                    type="checkbox"
                                    {{$data['query']['is_required'] == 1 ? 'checked' : ''}}
                                />
                                <input 
                                    type="hidden" 
                                    name="is_required" 
                                    value="{{$data['query']['is_required'] ? 'true' : 'false'}}" 
                                    class="test_class form-check-input"
                                    id="flexSwitchCheckDefault"
                                />
                            </div>
                        </div>
                        </div>
                        <input
                        type="text"
                        class="form-control"
                        name="question"
                        value="{{$data['query']['query']}}"
                        id="companyName"
                        placeholder="Headline or Question"
                        />
                    </div>
                    <div class="mb-3">
                        <label for="" class="color-blue font-22 mb-3"
                        >Inspection Area</label
                        >
                        <select
                        class="form-control form-select"
                        aria-label="Default select example"
                        name="area"
                        >
                            <option disabled selected>Select Inspection Area</option>
                            @foreach($data['categories'] AS $key => $item)
                                
                                <option value="{{$item['id']}}"
                                {{$data['query']['category_id'] == $item['id'] ? "selected" : ""}}
                                >{{$item['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 photo_view" style="@if(empty($data['photo_views'])) display: none; @endif">
                        <label for="" class="color-blue font-22 mb-3">Photo View</label>
    
                        
                        <select 
                        name="photo_view"
                        class="form-control form-select"
                        aria-label="Default select example"
                        >
                        <option selected disabled>Select Photo View</option>
                        @foreach($data['photo_views'] AS $key => $item)
                        
                            <option data-id="{{$data['query']['photo_view_id']}}" data-ids="{{$item['id']}}" value="{{$item['id']}}"
                                    {{$data['query']['photo_view_id'] == $item['id'] ? "selected" : ""}}
                            >{{$item['name']}}</option>
                        @endforeach
                        </select>
                    </div>
                    <!-- <div class="mb-3">
                        <label for="" class="color-blue font-22 mb-3"
                        >Additional Info</label
                        >
                        <input
                        type="text"
                        class="form-control"
                        id="companyName"
                        placeholder="Enter any details"
                        />
                    </div> -->
                    <!-- Show Fields on Event Start -->
                    <div class="form-group">
                        <div class="input-text options" style="@if(!in_array($data['query']['type'],['radio','checkbox'])) display: none; @endif">
                            <label for="basic-url" class="title color-blue font-22 mb-3">{{ $data['query']['type'] == 'radio' ? "Single Select" : "Multi Select" }}</label>

                            @if(!empty($data['query']['options']))
                                @foreach($data['query']['options'] AS $optionKey => $optionItem)
                                    <div class="input-group mt-3 mb-3">
                                        <input type="text" name="options[]" data-index="0" class="form-control" value="{{$optionItem['title']}}"
                                                placeholder="Option 1">
                                        <span class="input-group-btn remove_option">
                                        <button class="btn btn-default inputs" type="button">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </span>
                                    </div>
                                @endforeach
                            @else
                                <label for="basic-url" class="title"></label>
                                <div class="input-group mt-3 mb-3">
                                    <input type="text" name="options[]" data-index="0"  value="N/A" class="form-control" placeholder="Option 1">
                                </div>
                                <div class="input-group">
                                    <input type="text" name="options[]" data-index="1" class="form-control" placeholder="Option 2">
                                </div>
                            @endif


                        </div>
                        <div class="add_option" style="display: none;">
                        <ul class="add-dlete pt-2  mb-3">
                                <li><a href="" class="">Add Option </a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- SHOW Fields on Events End -->
                     <!--Events Hit Buttons  -->
                     <div class="d-flex flex-wrap align-items-center option_types">
                        <!-- Option Buttons for Input Type Selection -->
                        <div class="me-3">
                            <label class="btn btn-theme {{ $data['query']['type'] == 'text' ? 'active-class' : '' }}">
                                <input type="radio" name="type" class="btn-check" id="option1" value="text" autocomplete="off"
                                    {{ $data['query']['type'] == 'text' ? 'checked="checked"' : '' }} /> Input Field
                            </label>
                        </div>
                        <div class="me-3">
                            <label class="btn btn-theme {{ $data['query']['type'] == 'radio' ? 'active-class' : '' }}">
                                <input type="radio" name="type" class="btn-check" id="option2" value="radio" autocomplete="off"
                                    {{ $data['query']['type'] == 'radio' ? 'checked="checked"' : '' }} /> Single Select
                            </label>
                        </div>
                        <div class="me-3">
                            <label class="btn btn-theme {{ $data['query']['type'] == 'checkbox' ? 'active-class' : '' }}">
                                <input type="radio" name="type" class="btn-check" id="option3" value="checkbox" autocomplete="off"
                                    {{ $data['query']['type'] == 'checkbox' ? 'checked="checked"' : '' }} /> Multi Select
                            </label>
                        </div>
                        <div class="me-3">
                            <label class="btn btn-theme {{ $data['query']['type'] == 'date' ? 'active-class' : '' }}">
                                <input type="radio" name="type" class="btn-check" id="option4" value="date" autocomplete="off"
                                    {{ $data['query']['type'] == 'date' ? 'checked="checked"' : '' }} /> Date
                            </label>
                        </div>
                    </div>

                    <!-- Container for dynamically generated options -->
                    <!-- <div class="options" style="display: none;"></div> -->

                    <!-- Button to add options dynamically -->
                    <!-- <div class="add_option" style="display: none;">
                        <a href="#" class="btn btn-success">Add Option</a>
                    </div> -->
  
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-5 col-lg-4 col-xl-5">
                <div class="question-accord">
                    <div class="accordion" id="accordionExample">
                    @foreach($data['categories'] AS $catKey => $catItem)
                        @if($catItem['category_survey']->isNotEmpty())
                            <div class="accordion-items">
                                <h2 class="accordion-header" id="heading{{$catItem->id}}">
                                <button
                                    class="accordion-button collapsed"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{$catItem->id}}"
                                    aria-expanded="false"
                                    aria-controls="collapse{{$catItem->id}}"
                                >
                                {{$catItem->name}} <span class="rof-blue">{{count($catItem['category_survey'])}}</span>
                                </button>
                                </h2>
                                <div
                                id="collapse{{$catItem->id}}"
                                class="accordion-collapse collapse"
                                aria-labelledby="heading{{$catItem->id}}"
                                data-bs-parent="#accordionExample"
                                >
                                <div class="accordion-body">
                                    <ul class="main-ul">
                                        @foreach($catItem['category_survey'] AS $surveyKey => $surveyItem)
                                            <li>
                                                <div
                                                class="d-flex align-items-center justify-content-between"
                                                >
                                                <div>
                                                    <p>
                                                        {{$surveyItem->query}}
                                                    </p>
                                                </div>
                                                <div>
                                                    <div class="dropdown">
                                                    <button
                                                        class="btn btn-secondary roof-btn dropdown-toggle"
                                                        type="button"
                                                        id="dropdownMenuButton1"
                                                        data-bs-toggle="dropdown"
                                                        aria-expanded="false"
                                                    >
                                                        Action
                                                    </button>
                                                    <ul
                                                        class="dropdown-menu"
                                                        aria-labelledby="dropdownMenuButton1"
                                                    >
                                                        <li>
                                                        <a class="dropdown-item" href="{{url("admin/questionnaire/editQuestionnaireDetails/".$surveyItem->id)}}">Edit</a>
                                                        </li>
                                                        <li>
                                                        <a class="dropdown-item delete_query" data-id="{{$surveyItem->id}}" href=""
                                                            >Delete</a
                                                        >
                                                        </li>
                                                    </ul>
                                                    </div>
                                                </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                </div>
                            </div>
                        @endif
                    @endforeach  
                    </div>
                </div>
            </div>
        </div>
        <div class="row"></div>
    <!-- Help Photo Modal -->
        <div
          class="modal fade"
          id="companyLogoModal"
          tabindex="-1"
          aria-labelledby="companyLogoModalLabel"
          aria-hidden="true"
        >
          <div class="modal-dialog modal-dialog-centered project-modal">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="companyLogoModalLabel">
                  Help Photo
                </h5>
                <button
                  type="button"
                  class="btn-close"
                  data-bs-dismiss="modal"
                  aria-label="Close"
                ></button>
              </div>
              <div class="modal-body">
                <div class="upload-drop-zone  @if(!empty($data['query']['image_url'])) hide @endif"  id="drop-zone">
                    <div class="company-logo-img">
                        <div class="cvs-import-tile text-center">
                            <p>Drag and drop to upload your CSV file </p>
                            <p><span>Acceptable Formats:</span>jpeg, gif, png, pdf</p>

                            <div class="fileUpload btn-broswe blue-btn btn width100">
                                <span>
                                    <ul class="add-cancel-btn drag-btn">
                                        <li>Drag</li>
                                        <!-- <label for="js-upload-files" class="btn browse-btn">Browse</label> -->
                                        <input name="logo" type="file" id="js-upload-files" style="visibility:hidden;" class="uploadlogo @if(!empty($data['query']['image_url'])) {{"hide"}} @endif"/>

                                    </ul>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row image_preview @if(empty($data['query']['image_url'])) hide @endif" >
                    @if(!empty($data['query']['image_url'])) <input type="hidden" name="image_set" value="true" /> @endif
                    <div class="col-md-12 text-center">
                        <img  src="{{ !empty($data['query']['image_url']) ? url("uploads/media/".$data['query']['image_url']) : '' }}" class="img-thumbnail" style="max-height: 300px;"/>
                    </div>
                    <div class="delete-sec">
                        <button type="button" class="delete-btn image_remove">
                            <div><img src="../assets/img/tag-icon.png" alt="" /></div>
                            <div class="ms-2 ">Remove</div>
                        </button>
                    </div>
                </div>
                
              </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-cancel"
                  data-bs-dismiss="modal"
                >
                  Cancel
                </button>
              </div>
            </div>
          </div>
        </div>
    </form>
    <!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered project-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete the question?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" id="confirmDeleteButton" class="btn btn-save">Delete </button>
      </div>
    </div>
  </div>
</div>   
  </section> 
  @endsection 
  @push("page_css") 
  <style>
        .hide{
            display:none;
        }
        .show{
            display:block !important;
        }
        .fileUpload.btn-broswe.blue-btn.btn.width100 {
            pointer-events: none;
        }
        .active-class{
            background-color: #000 !important;
            color: #fff !important
        }
        .accordion-body ul.main-ul li {
            padding: 8px 0px;
        }
        .dropdown-menu li{
            padding: 0px 0px !important;
        }

        ul.main-ul li p {
            font-family: 'EuclidSquare-Medium' !important;!i;!;
            font-size: 16px;
        }
  </style> 
  @endpush 
  @push("page_js") 
  <script>
    // Droopzone
    +function ($) {
        'use strict';

        // UPLOAD CLASS DEFINITION
        // ======================

        var dropZone = document.getElementById('drop-zone');
        var uploadForm = document.getElementById('editQuestionForm');
        let help_photo;

        var startUpload = function (files) {
            console.log('startUpload', files);
        }

        uploadForm.addEventListener('submit', function (e) {
            var uploadFiles = document.getElementById('js-upload-files').files;
            e.preventDefault();

            var fd = new FormData($('#editQuestionForm')[0]);
            let image_set = $(".image_preview").find("input[name='image_set']").first().val();

            console.log('not equal undefined',typeof (help_photo) !== 'undefined', help_photo);
            console.log('image_set',$(".image_preview").find("input[name='image_set']").first().val());

            if (typeof (help_photo) !== 'undefined' ) {
                fd.append('help_photo', help_photo[0]);
            }else{
                fd.append('image_set',$(".image_preview").find("input[name='image_set']").first().val());
            }

            let id = "{{!empty($data['query_id']) ? "/".$data['query_id'] : null}}";


            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: '{{URL::to('admin/questionnaire/update')}}' + id,
                data: fd,
                processData: false,
                contentType: false,
                cache: false,
                success: (response) => {
                    let alertTitle = response.code !== 200 ? "Error": "Success" ;
                    window.location.href = "{{url('/admin/questionnaire')}}";
                    // $("#alertModal").find(".modal-title").text(alertTitle);
                    // $("#alertModal").find(".updated-title p").text(response.message);
                    // $("#alertModal").modal({show: true});
                }
            });

            startUpload(uploadFiles)
        })

        dropZone.ondrop = function (e) {
            e.preventDefault();
            this.className = 'upload-drop-zone';
            help_photo = e.dataTransfer.files;
            // startUpload(e.dataTransfer.files)

            if (FileReader && help_photo && help_photo.length) {
                var fr = new FileReader();
                fr.onload = function () {
                    // document.getElementById(outImage).src = fr.result;
                    $('.image_preview').find('img.img-thumbnail').attr('src', fr.result);

                    if ($('.image_preview').hasClass('hide')) {
                        $('.image_preview').removeClass('hide');
                    }

                }
                console.log("createObjectURL: ", URL.createObjectURL(help_photo[0]));

                fr.readAsDataURL(help_photo[0]); // Initiating Reader that eventually trigger .onload
                $('.upload-drop-zone').addClass('hide');
            }
        }

        dropZone.ondragover = function () {
            this.className = 'upload-drop-zone drop';
            return false;
        }

        dropZone.ondragleave = function () {
            this.className = 'upload-drop-zone';
            return false;
        }

    }(jQuery);
    //Hit Event Triggers Implementation
    $(document).ready(function () {
        /** image_remove is getting used in two inputs:
             *  1. Cover Image
             *  2. Company Logo  */
            $("button.image_remove").on('click', function (e) {
                console.log('clicked')

                let $imagePreview = $(this).closest('.image_preview');
                let $modalBody = $(this).closest('.modal-body');

                console.log('$imagePreview',$imagePreview);
                console.log('$modalBody',$modalBody);
                console.log('drop-zone', $modalBody.find(".upload-drop-zone").first());

                console.log('class',$(this).attr('class'));

                /** For cover image */
                if($(this).hasClass('cover')){
                    console.log('cover_image',$modalBody.find(".form-group.cover_image").first());
                    $modalBody.find(".form-group.cover_image").first().removeClass("hide");
                    $modalBody.find("input[name='cover_image']").first().removeClass("hide");
                }


                $modalBody.find(".upload-drop-zone , .cover-drop-zone").first().removeClass("hide");

                $imagePreview.addClass("hide");
                $imagePreview.find("input[name='image_set']").first().attr("disabled", true);
            });
            $("#cancelBtn").on("click",function () {
                //alert(aa);
                window.location.href = "{{url('/admin/questionnaire')}}";
            });
            console.log('ready');

            let optionTypes = ['radio', 'checkbox'];
            let type = 'text';
            let status = 400;

            $("button.image_remove").on('click', function (e) {
                console.log('clicked')

                let $imagePreview = $(this).closest('.image_preview');
                let $modalBody = $(this).closest('.modal-body');

                console.log('image_set', $imagePreview.find("input[name='image_set']").first() ,$imagePreview.find("input[name='image_set']").val());


                $modalBody.find(".drop-cvs").first().removeClass("hide");
                $imagePreview.addClass("hide");
                $imagePreview.find("input[name='image_set']").first().attr("disabled", true).val(false);
            });

            $('.btn-toggle').on('click', function (e) {
                let state = $(this).closest('.switch-btn').find('input[name="is_required"]').val();
                console.log('is_required state',state);
                if (state === 'true') {
                    $(this).closest('.switch-btn').find('input[name="is_required"]').val(false); // Toggling
                    console.log('is_required Toggling off');
                } else {
                    $(this).closest('.switch-btn').find('input[name="is_required"]').val(true); // Toggling
                    console.log('is_required Toggling on');
                }
            });

            $("select[name='area']").on("change", function (e) {
                fillPhotoview($(this).val())
            });

            function fillPhotoview(inspectionArea) {
                $.ajax({
                    type: "GET",
                    enctype: 'multipart/form-data',
                    url: `{{URL::to('admin/photo_views')}}/${inspectionArea}`,
                    data: [],
                    async: false,
                    contentType: false,
                    success: (response) => {
                        console.log(response);
                        let options = `<option disabled selected>Select Photo View</option>`;
                        $.each(response.data, (i, el) => {
                            options += `<option value="${el.id}">${el.category_name}</option>`;
                        });
                        $("select[name='photo_view']").html(options);
                        $(".photo_view").show();
                    }
                });
            }

            $(".delete_query").on("click", function(e) {
                e.preventDefault();
                $('#deleteConfirmationModal').modal('show');
                let id = $(this).data('id');
                console.log(id);

                $('#confirmDeleteButton').off("click").on("click", function(e) {
                    $.ajax({
                        type: "POST",
                        enctype: 'multipart/form-data',
                        url: `{{URL::to('admin/questionnaire/delete')}}/${id}`,
                        data: {},
                        contentType: false,
                        processData: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  // Add CSRF token to the headers
                        },
                        dataType: 'JSON',
                        success: function(response) {
                            $('#deleteConfirmationModal').modal('hide'); // Hide the modal after deletion
                            $('html, body').animate({scrollTop: '0px'}, 300);
                            $("div.alert-success.success").text(response.message).show();
                            setTimeout(() => {
                                $("div.alert.alert-success.success").hide();
                            }, 5000);
                            window.location.reload(); // Reload the page upon successful deletion
                        },
                        error: function(response) {
                            $('#deleteConfirmationModal').modal('hide');
                            $('html, body').animate({scrollTop: '0px'}, 300);
                            $("div.alert-danger.error").text(response.responseJSON.message).show();
                            setTimeout(() => {
                                $("div.alert.alert-danger.error").hide();
                            }, 5000);
                            console.log(response.responseJSON.message);
                        }
                    });
                });
            });


            $("#alertModal").on("hidden.bs.modal", function () {
                if(status == 200){
                    window.location = $(".query_list").find(".edit_query").first().attr('href');
                }
            });

            $(".btn-add").on("click",function(e){

                e.preventDefault();
                console.log("add ");


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
  </script> 
  <script>
    $(document).ready(function() {
        const optionTypes = ["radio", "checkbox"];
        
        // Event listener for type selection buttons
        $('.option_types .btn-theme').on('click', function () {
            let type = $(this).find("input[name='type']").val();

            // Handle radio and checkbox types
            if (optionTypes.includes(type)) {
                let title = type === 'radio' ? "Single Select" : "Multi Select";

                // Generate input fields for options
                let optionsHtml = `
                    <label for="basic-url" class="title color-blue font-22 mb-3">${title}</label>
                    <div class="input-group mt-3 mb-3">
                        <input type="text" name="options[]" data-index="0" value="N/A" class="form-control" placeholder="Option 1">
                        <span class="input-group-btn remove_option">
                            <button class="btn btn-default inputs close-icon" type="button">
                                <i class="fas fa-times"></i>
                            </button>
                        </span>
                    </div>
                    <div class="input-group mt-3">
                        <input type="text" name="options[]" data-index="1" class="form-control" placeholder="Option 2">
                        <span class="input-group-btn remove_option">
                            <button class="btn btn-default inputs close-icon" type="button">
                                <i class="fas fa-times"></i>
                            </button>
                        </span>
                    </div>`;

                $(".options").html(optionsHtml).show();
                $(".add_option").show();

            // Handle text type
            } 
            // else if (type === 'text') {
            //     let textHtml = `
            //         <label for="text-input" class="title color-blue font-22 mb-3">Input Field</label>
            //         <div class="input-group mb-3">
            //             <input type="text" name="text_input" class="form-control" placeholder="Enter text here">
            //         </div>`;

            //     $(".options").html(textHtml).show();
            //     $(".add_option").hide();

            // // Handle date type
            // } else if (type === 'date') {
            //     let dateHtml = `
            //         <label for="date-input" class="title color-blue font-22 mb-3">Date Field</label>
            //         <div class="input-group mb-3">
            //             <input type="date" name="date_input" class="form-control">
            //         </div>`;

            //     $(".options").html(dateHtml).show();
            //     $(".add_option").hide();
            // } 
            else {
                console.log('no options');
                $(".options").hide();
                $(".add_option").hide();
            }
        });

        // Event listener for adding a new option dynamically
        $(".add_option a").on("click", function (e) {
            e.preventDefault();
            let index = $(".options").find('.form-control').last().data('index');
            let optionHtml = `
                <div class="input-group mt-3 mb-3">
                    <input type="text" name="options[]" data-index="${index + 1}" class="form-control" placeholder="Option ${index + 2}">
                    <span class="input-group-btn remove_option">
                        <button class="btn btn-default inputs close-icon" type="button">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                </div>`;
            $(".options").append(optionHtml);
        });

        // Event listener for removing an option
        $(document).on('click', '.remove_option', function(){
            $(this).closest('.input-group').remove();
        });
        $('.option_types .btn-theme').click(function() {
            // Remove the class if it already exists on the clicked element
            if ($(this).hasClass('active-class')) {
                $(this).removeClass('active-class');
            } else {
                // Remove the class from all elements to ensure only one is active at a time
                $('.option_types .btn-theme').removeClass('active-class');
                
                // Add the class to the clicked element if the related radio input is checked
                if ($(this).find('input[type="radio"]').is(':checked')) {
                    $(this).addClass('active-class');
                }
            }
        });
    });

  </script>
  @endpush  