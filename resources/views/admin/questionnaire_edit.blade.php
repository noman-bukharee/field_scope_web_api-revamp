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
                <div class="upload-drop-zone"  id="drop-zone">
                    <div class="company-logo-img">
                        <div class="cvs-import-tile text-center">
                            <p>Drag and drop to upload your CSV file </p>
                            <p><span>Acceptable Formats:</span>jpeg, gif, png, pdf</p>

                            <div class="fileUpload btn-broswe blue-btn btn width100">
                                <span>
                                    <ul class="add-cancel-btn drag-btn">
                                        <li>Drag</li>
                                        <!-- <label for="js-upload-files" class="btn browse-btn">Browse</label> -->
                                        <input name="help_photo[]" type="file" id="js-upload-files" multiple style="visibility:hidden;" class="uploadlogo @if(!empty($data['query']['image_url'])) {{"hide"}} @endif"/>

                                    </ul>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row image_preview">
                    @if(!empty($data['query']['image_url']))
                        @foreach($data['query']['image_url'] as $index => $image)
                            <div class="col-md-12 text-center image-container">
                                <img src="{{ url('uploads/media/' . $image) }}" class="img-thumbnail" style="max-height: 300px;"/>
                                <!-- Hidden input to track existing image -->
                                <input type="hidden" name="existing_images[]" value="{{ $image }}" class="existing-image">
                                <button type="button" class="delete-btn photo_remove">Remove</button>
                            </div>
                        @endforeach
                        <input type="hidden" name="image_set" value="true" />
                    @endif
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
        div#companyLogoModal .image-container {
            overflow: visible;
            margin: 30px 0px;
        }
  </style> 
  @endpush 
  @push("page_js") 
  <script>
    // Droopzone
    // +function ($) {
    //     'use strict';

    //     // UPLOAD CLASS DEFINITION
    //     // ======================

    //     var dropZone = document.getElementById('drop-zone');
    //     var uploadForm = document.getElementById('editQuestionForm');
    //     let help_photo;

    //     var startUpload = function (files) {
    //         console.log('startUpload', files);
    //     }

    //     uploadForm.addEventListener('submit', function (e) {
    //         var uploadFiles = document.getElementById('js-upload-files').files;
    //         e.preventDefault();

    //         var fd = new FormData($('#editQuestionForm')[0]);
    //         let image_set = $(".image_preview").find("input[name='image_set']").first().val();

    //         console.log('not equal undefined',typeof (help_photo) !== 'undefined', help_photo);
    //         console.log('image_set',$(".image_preview").find("input[name='image_set']").first().val());

    //         if (typeof (help_photo) !== 'undefined' ) {
    //             for (var i = 0; i < help_photo.length; i++) {
    //                 fd.append('help_photo[]', help_photo[i]);
    //             }
    //         }else{
    //             fd.append('image_set',$(".image_preview").find("input[name='image_set']").first().val());
    //         }

    //         let id = "{{!empty($data['query_id']) ? "/".$data['query_id'] : null}}";


    //         $.ajax({
    //             type: "POST",
    //             enctype: 'multipart/form-data',
    //             url: '{{URL::to('admin/questionnaire/update')}}' + id,
    //             data: fd,
    //             processData: false,
    //             contentType: false,
    //             cache: false,
    //             success: (response) => {
    //                 let alertTitle = response.code !== 200 ? "Error": "Success" ;
    //                 window.location.href = "{{url('/admin/questionnaire')}}";
    //                 // $("#alertModal").find(".modal-title").text(alertTitle);
    //                 // $("#alertModal").find(".updated-title p").text(response.message);
    //                 // $("#alertModal").modal({show: true});
    //             }
    //         });

    //         startUpload(uploadFiles)
    //     })

    //     dropZone.ondrop = function (e) {
    //         e.preventDefault();
    //         this.className = 'upload-drop-zone';
    //         help_photo = e.dataTransfer.files;
    //         console.log('Noman', help_photo[0]);
    //         // startUpload(e.dataTransfer.files)

    //         if (FileReader && help_photo && help_photo.length) {
    //             for (var i = 0; i < help_photo.length; i++) {
    //                 var fr = new FileReader();
    //                 fr.onload = function () {
    //                     // document.getElementById(outImage).src = fr.result;
    //                     var imageContainer = $('<div class="image-container"></div>');
    //                     var image = $('<img src="' + fr.result + '" class="img-thumbnail" style="max-height: 300px;"/>');
    //                     var removeButton = $('<button type="button" class="delete-btn photo_remove">Remove</button>');
    //                     imageContainer.append(image).append(removeButton);
    //                     $('.image_preview').append(imageContainer);

    //                     if ($('.image_preview').hasClass('hide')) {
    //                         $('.image_preview').removeClass('hide');
    //                     }

    //                 }
    //                 console.log("createObjectURL: ", URL.createObjectURL(help_photo[i]));

    //                 fr.readAsDataURL(help_photo[i]); // Initiating Reader that eventually trigger .onload
    //             }
    //             // $('.upload-drop-zone').addClass('hide');
    //         }
    //     }

    //     dropZone.ondragover = function () {
    //         this.className = 'upload-drop-zone drop';
    //         return false;
    //     }

    //     dropZone.ondragleave = function () {
    //         this.className = 'upload-drop-zone';
    //         return false;
    //     }
    //     // Add multiple attribute to input file
    //     document.getElementById('js-upload-files').setAttribute('multiple', 'multiple');

    // }(jQuery);
    +function ($) {
        'use strict';

        var dropZone = document.getElementById('drop-zone');
        var uploadForm = document.getElementById('editQuestionForm');
        let help_photos = [];

        uploadForm.addEventListener('submit', function (e) {
            e.preventDefault();
            var fd = new FormData($('#editQuestionForm')[0]);
            console.log('Files to send:', help_photos.length);
            if (help_photos.length > 0) {
                help_photos.forEach((photo, index) => {
                    fd.append('help_photo[]', photo);
                });
            }
            // No need to manually append existing_images or image_set; FormData includes them automatically

            let id = "{{ !empty($data['query_id']) ? '/' . $data['query_id'] : '' }}";

            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: '{{ URL::to('admin/questionnaire/update') }}' + id,
                data: fd,
                processData: false,
                contentType: false,
                cache: false,
                success: (response) => {
                    window.location.href = "{{ url('/admin/questionnaire') }}";
                },
                error: (error) => {
                    console.error('Upload failed:', error);
                }
            });
        });

        dropZone.ondrop = function (e) {
            e.preventDefault();
            this.className = 'upload-drop-zone';
            help_photos = Array.from(e.dataTransfer.files);
            console.log('Dropped files:', help_photos.length);
            
            if (FileReader && help_photos.length) {
                if (!{{ !empty($data['query_id']) ? 'true' : 'false' }}) {
                    $('.image_preview').empty();
                }
                help_photos.forEach(file => {
                    var fr = new FileReader();
                    fr.onload = function () {
                        var imageContainer = $('<div class="image-container"></div>');
                        var image = $('<img src="' + fr.result + '" class="img-thumbnail" style="max-height: 300px;"/>');
                        var removeButton = $('<button type="button" class="delete-btn photo_remove">Remove</button>');
                        imageContainer.append(image).append(removeButton);
                        $('.image_preview').append(imageContainer);
                        
                        if ($('.image_preview').hasClass('hide')) {
                            $('.image_preview').removeClass('hide');
                        }
                    };
                    fr.readAsDataURL(file);
                });
                // $('.upload-drop-zone').addClass('hide');
            }
        };

        dropZone.ondragover = function () {
            this.className = 'upload-drop-zone drop';
            return false;
        };

        dropZone.ondragleave = function () {
            this.className = 'upload-drop-zone';
            return false;
        };

        $(document).on('click', '.photo_remove', function () {
            var $container = $(this).parent();
            var index = $('.image-container').index($container);
            
            // If it’s an existing image, removing it deletes the hidden input (handled by DOM)
            // If it’s a new upload, remove it from help_photos
            if ($container.find('.existing-image').length === 0 && index >= ($('.image-container').length - help_photos.length)) {
                help_photos.splice(index - ($('.image-container').length - help_photos.length), 1);
            }
            $container.remove();
            
            if ($('.image_preview').find('.image-container').length === 0) {
                $('.image_preview').addClass('hide');
                $('.upload-drop-zone').removeClass('hide');
            }
        });
    }(jQuery);
    //Hit Event Triggers Implementation
    $(document).ready(function () {
        $(document).on('click', '.photo_remove', function () {
            var imageContainer = $(this).parent();
            let $imagePreview = $(this).closest('.image_preview');
            
            let $modalBody = $(this).closest('.modal-body');
            imageContainer.remove();
            if ($('.image_preview').find('.image-container').length === 0) {
                $('.image_preview').addClass('hide');
                $modalBody.find(".upload-drop-zone , .cover-drop-zone").first().removeClass("hide");
                $imagePreview.find("input[name='image_set']").first().attr("disabled", true);
            }
            
        });
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
    
        // Function to toggle option fields and "Add Option" button visibility
        function updateOptionVisibility(type) {
            if (optionTypes.includes(type)) {
                let title = type === 'radio' ? "Single Select" : "Multi Select";
                $(".options").show();
                $(".add_option").show();
                $(".options .title").text(title);
            } else {
                $(".options").hide();
                $(".add_option").hide();
            }
        }

        // Initialize based on the current question type
        let initialType = $("input[name='type']:checked").val() || 'text';
        updateOptionVisibility(initialType);

        // Event listener for type selection buttons
        $('.option_types .btn-theme').on('click', function () {
            let type = $(this).find("input[name='type']").val();
            updateOptionVisibility(type);

            // Handle active class for visual feedback
            $('.option_types .btn-theme').removeClass('active-class');
            if ($(this).find('input[type="radio"]').is(':checked')) {
                $(this).addClass('active-class');
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