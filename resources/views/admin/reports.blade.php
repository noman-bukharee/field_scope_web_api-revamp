@extends('admin.master')
@section('content')
@section('title', 'Reports')
<!-- Include Editor style. -->
<link href="https://cdn.jsdelivr.net/npm/froala-editor@latest/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
<section class="container-fluid main-sec">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="user-type-sec">
                <div>
                    <h2>Reports</h2>
                </div>
            </div>
        </div>
        <div class="col-12 mt-2">
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3 col-lg-3 reports-card">
                    <div class="project-card report-card photo-feed" data-bs-toggle="modal"
                      data-bs-target="#companyLogoModal">
                        <div class="project-card-header">
                            <div class="card-img">
                                <img src="{{asset("assets/img/report.png")}}" alt="">
                            </div>
                            
                        </div>
                        <div class="project-card-body">
                            <div class="row">
                                <div class="col-12">
                                    <p class="color-black  pb-3 font-22">Company Logo</p>
                                </div>
                            </div>
                        </div>
                    </div>
                            
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 reports-card company-color">
                    <div class="project-card report-card photo-feed" 
                      data-bs-toggle="modal"
                      data-bs-target="#companyColorsModal">
                        <div class="project-card-header">
                            <div class="card-img">
                                <img src="{{asset("assets/img/report-color.png")}}" alt="">
                            </div>
                            
                        </div>
                        <div class="project-card-body">
                            <div class="row">
                                <div class="col-12">
                                    <p class="color-black  pb-3 font-22">Company Colors</p>
                                </div>
                            </div>
                        </div>
                    </div>
                            
                </div>

                <div class="col-12 col-sm-6 col-md-4 col-lg-3 reports-card company-information">
                    <div class="project-card report-card photo-feed" 
                      data-bs-toggle="modal"
                      data-bs-target="#companyEditModal">
                        <div class="project-card-header">
                            <div class="card-img">
                                <img src="{{asset("assets/img/report-company-information.png")}}" alt="">
                            </div>
                            
                        </div>
                        <div class="project-card-body">
                            <div class="row">
                                <div class="col-12">
                                    <p class="color-black  pb-3 font-22">Company Information</p>
                                </div>
                            </div>
                        </div>
                    </div>
                            
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 reports-card cover-page">
                    <div class="project-card report-card photo-feed" 
                      data-bs-toggle="modal"
                      data-bs-target="#coverPageModal">
                        <div class="project-card-header">
                            <div class="card-img">
                                <img src="{{asset("assets/img/cover.png")}}" alt="">
                            </div>
                            
                        </div>
                        <div class="project-card-body">
                            <div class="row">
                                <div class="col-12">
                                    <p class="color-black  pb-3 font-22">Cover Page</p>
                                </div>
                            </div>
                        </div>
                    </div>
                            
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 reports-card company-intro">
                    <div class="project-card report-card photo-feed" 
                      data-bs-toggle="modal"
                      data-bs-target="#companyIntroModal">
                        <div class="project-card-header">
                            <div class="card-img">
                                <img src="{{asset("assets/img/report-intro.png")}}" alt="">
                            </div>
                            
                        </div>
                        <div class="project-card-body">
                            <div class="row">
                                <div class="col-12">
                                    <p class="color-black  pb-3 font-22">Company Introduction</p>
                                </div>
                            </div>
                        </div>
                    </div>     
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 reports-card credit-disclaimer">
                    <div class="project-card report-card photo-feed" 
                      data-bs-toggle="modal"
                      data-bs-target="#creditDisclaimerModal">
                        <div class="project-card-header">
                            <div class="card-img">
                                <img src="../assets/img/report.png" alt="">
                            </div>
                            
                        </div>
                        <div class="project-card-body">
                            <div class="row">
                                <div class="col-12">
                                    <p class="color-black  pb-3 font-22">Credit Disclaimer</p>
                                </div>
                            </div>
                        </div>
                    </div>     
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 reports-card owner-authorization">
                    <div class="project-card report-card photo-feed" 
                      data-bs-toggle="modal"
                      data-bs-target="#ownerAuthModal">
                        <div class="project-card-header">
                            <div class="card-img">
                                <img src="{{asset("assets/img/report-owner.png")}}" alt="">
                            </div>
                            
                        </div>
                        <div class="project-card-body">
                            <div class="row">
                                <div class="col-12">
                                    <p class="color-black  pb-3 font-22">Owner Authorization</p>
                                </div>
                            </div>
                        </div>
                    </div>     
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 reports-card terms-conditions">
                    <div class="project-card report-card photo-feed" 
                      data-bs-toggle="modal"
                      data-bs-target="#companyTermsModal">
                        <div class="project-card-header">
                            <div class="card-img">
                                <img src="{{asset("assets/img/report-terms.png")}}" alt="">
                            </div>
                            
                        </div>
                        <div class="project-card-body">
                            <div class="row">
                                <div class="col-12">
                                    <p class="color-black  pb-3 font-22">Company Terms & Conditions</p>
                                </div>
                            </div>
                        </div>
                    </div>
                            
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 reports-card  report-pdf">
                    <div class="project-card report-card photo-feed" 
                      data-bs-toggle="modal"
                      data-bs-target="#documentOptionsModal">
                        <div class="project-card-header">
                            <div class="card-img">
                                <img src="{{asset("assets/img/report-pdf.png")}}" alt="">
                            </div>
                            
                        </div>
                        <div class="project-card-body">
                            <div class="row">
                                <div class="col-12">
                                    <p class="color-black  pb-3 font-22">Additional Document Options</p>
                                </div>
                            </div>
                        </div>
                    </div>
                            
                </div>
                

                
                
                
                
            </div>
        </div>
    </div>
    <!-- Modals Start -->
     <!-- Company Logo Modal -->
        <div
          class="modal fade"
          id="companyLogoModal"
          tabindex="-1"
          aria-labelledby="companyLogoModalLabel"
          aria-hidden="true"
        >
        <form id="js-upload-form" method="POST" action="{{url('admin/reports/storeLogo')}}" enctype="multipart/form-data">
            {{csrf_field()}}
          <div class="modal-dialog modal-dialog-centered project-modal">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="companyLogoModalLabel">
                  Company Logo
                </h5>
                <button
                  type="button"
                  class="btn-close"
                  data-bs-dismiss="modal"
                  aria-label="Close"
                ></button>
              </div>
              <div class="modal-body">
                <div class="upload-drop-zone @if(!empty($report->logo_path)) hide @endif" id="drop-zone">
                    <div class="company-logo-img">
                        <div class="cvs-import-tile text-center">
                            <p>Drag and drop to upload your company logo </p>
                            <p><span>Acceptable Formats:</span>jpeg, gif, png, pdf</p>
                            <p><span>Acceptable Dimension:</span> 500 x 167 pixels</p>

                            <div class="fileUpload btn-broswe blue-btn btn width100">
                                <span>
                                    <ul class="add-cancel-btn drag-btn">
                                        <li>Drag</li>
                                        <!-- <label for="js-upload-files" class="btn browse-btn">Browse</label> -->
                                        <input id="js-upload-files" name="logo" accept=".jpeg,.jpg,.png" style="visibility:hidden;" type="file">
                                    </ul>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row image_preview @if(empty($report->logo_path)) hide @endif" >
                    @if(!empty($report->logo_path)) <input type="hidden" name="image_set" value="true" /> @endif
                    <div class="col-md-12 text-center">
                        <img  src="{{url("uploads/report_templates/".$report->logo_path)}}" class="img-thumbnail" style="max-height: 300px;"/>
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
                <button type="submit" class="btn btn-save">Save</button>
              </div>
            </div>
          </div>
        </form>  
        </div>

        <!-- Company Colors Modal -->
        <div
          class="modal fade"
          id="companyColorsModal"
          tabindex="-1"
          aria-labelledby="companyColorsModalLabel"
          aria-hidden="true"
        >
        <form id="report-image" action="{{ action('ReportController@companyColor') }}" method="POST">
                {{ csrf_field() }}
          <div class="modal-dialog modal-dialog-centered project-modal">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="companyColorsModalLabel">
                  Company Colors
                </h5>
                <button
                  type="button"
                  class="btn-close"
                  data-bs-dismiss="modal"
                  aria-label="Close"
                ></button>
              </div>
              <div class="modal-body">
                <!-- Content for Company Colors -->
                <p class="">
                  These colors will be applied to your reports to match your
                  company branding.
                </p>
                <div class="row">
                  <div class="col-12 col-md-6">
                    <div class="mb-3">
                      <label for="primaryColor" class="form-label"
                        >Primary Color</label
                      >
                      <input id="primaryColor" class="form-control form-control-color" title="Choose your primary color" name="primary_color" type="color" value="{{ !empty($report->primary_color) ? $report->primary_color : '#83b545' }}" data-css-var="--main-bg-color" />
                      
                    </div>
                  </div>
                  <div class="col-12 col-md-6">
                    <div class="mb-3">
                      <label for="secondaryColor" class="form-label"
                        >Secondary Color</label
                      >
                      <input id="secondaryColor" title="Choose your secondary color" class="form-control form-control-color" name="secondary_color" type="color" value="{{ !empty($report->secondary_color) ? $report->secondary_color : '#83b545' }}"data-css-var="--main-bg-color" />
                    
                    </div>
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
                <button type="submit" class="btn btn-save">Confirm</button>
              </div>
            </div>
          </div>
        </form>  
        </div>

        <!-- Company Terms & Conditions Modal -->
        <div
          class="modal fade"
          id="companyTermsModal"
          tabindex="-1"
          aria-labelledby="companyTermsModalLabel"
          aria-hidden="true"
        >
        <form id="report-image" action="{{ action('ReportController@companyTermsConditions') }}" method="POST">
                {{ csrf_field() }}
          <div class="modal-dialog modal-dialog-centered project-modal">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="companyTermsModalLabel">
                  Company Terms & Conditions
                </h5>
                <button
                  type="button"
                  class="btn-close"
                  data-bs-dismiss="modal"
                  aria-label="Close"
                ></button>
              </div>
              <div class="modal-body">
                <!-- Content for Company Terms & Conditions -->
                <textarea name="editor2">{{ !empty($termsConditions->content) ? $termsConditions->content : null }}</textarea>
              </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-cancel"
                  data-bs-dismiss="modal"
                >
                  Cancel
                </button>
                <button type="submit" class="btn btn-save">Save</button>
              </div>
            </div>
          </div>
        </form>  
        </div>

        <!-- Document Options Modal -->
        <div
          class="modal fade"
          id="documentOptionsModal"
          tabindex="-1"
          aria-labelledby="documentOptionsModalLabel"
          aria-hidden="true"
        >
        <form id="report-images" action="{{ action('ReportController@saveDocument') }}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="modal-dialog modal-dialog-centered project-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="documentOptionsModalLabel">Additional Document Options</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="upload-csv-modal">
                        <div class="card">
                            <div class="drop_box form-group thumbnail_container">
                                <header class="upload-ico">
                                    <img src="{{asset("assets/img/upload-icon.png")}}" alt="">
                                </header>
                                <header class="enable-header"><h4></h4></header>
                                <input type="hidden" name="title" id="Filetitle" class="form-control" required>
                                <p>Users can select from these PDF documents when creating a report.</p>
                                <label for="fileID" class="btn browse-btn">Browse</label>
                                <input id="fileID" type="file" name="document" hidden accept=".doc,.docx,.pdf">
                            </div>
                        </div>
                        <!-- <div class="uploded-img"> -->
                            <p class="font-18 color-blue mt-3 mb-2">PDF Documents</p>
                            <div>
                            @if(count($documents))
                                @foreach($documents as $document)
                                    <div class="uploded-img">
                                        <div>
                                            <p class="color-black"><a target="_blank" href="{{ URL::to($document->path) }}">PDF {{ $document->title }}</a></p>
                                        </div>
                                        <div>
                                            
                                            <a id="{{ md5($document->path) }}" class="delIcon delete_document" href="#"><i class="fa-solid fa-trash"></i></a>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
</div>
                        <!-- </div> -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="save-document" class="btn btn-save"  onclick="saveDocument()">Save</button>
                </div>
            </div>
        </div>
    </form> 
        </div>

        <!-- Company Information Modal -->
        <div
          class="modal fade"
          id="companyEditModal"
          tabindex="-1"
          aria-labelledby="companyEditModalLabel"
          aria-hidden="true"
        >
        <form method="POST" action="{{url('admin/reports/storeInfo')}}">
            {{csrf_field()}}
          <div class="modal-dialog modal-dialog-centered project-modal">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="companyEditModalLabel">
                  Company Information
                </h5>
                <button
                  type="button"
                  class="btn-close"
                  data-bs-dismiss="modal"
                  aria-label="Close"
                ></button>
              </div>
              <div class="modal-body">
                <!-- Content for Company Edit -->
                  <div class="mb-3">
                        <input type="text" 
                            placeholder="Company Name"  
                            class="form-control"
                            name="name"  
                            value="@if(!empty($report->name)){{$report->name}}@endif" 
                        />
                  </div>
                  <div class="mb-3">
                        <input type="text" 
                            placeholder="Company Email" 
                            class="form-control"
                            name="email"     
                            value="@if(!empty($report->email)){{$report->email}}@endif" 
                        />

                  </div>
                  <div class="mb-3">
                        <input type="text" 
                            placeholder="Company Phone" 
                            class="form-control"
                            name="phone"     
                            value="@if(!empty($report->phone)){{$report->phone}}@endif" 
                        />

                  </div>
                  <div class="mb-3">
                        <input type="text" 
                            placeholder="Website"   
                            class="form-control"    
                            name="website"   
                            value="@if(!empty($report->website)){{$report->website}}@endif" 
                        />

                  </div>
                  <!-- <div class="mb-3">
                        <input type="text" 
                            placeholder="Our Services"  
                            class="form-control"
                            name="services"  
                            value="@if(!empty($report->services)){{$report->services}}@endif" 
                        />

                  </div> -->
              </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-cancel"
                  data-bs-dismiss="modal"
                >
                  Cancel
                </button>
                <button type="submit" class="btn btn-save">Confirm</button>
              </div>
            </div>
          </div>
        </form>  
        </div>

        <!-- Cover Page Modal -->
        <div
          class="modal fade"
          id="coverPageModal"
          tabindex="-1"
          aria-labelledby="coverPageModalLabel"
          aria-hidden="true"
        >
        <form id="cover-upload-form" action="{{url('admin/reports/storeCoverInfo')}}" method="POST" enctype="multipart/form-data">
            {{csrf_field()}}
          <div class="modal-dialog modal-dialog-centered project-modal">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="coverPageModalLabel">Cover Page</h5>
                <button
                  type="button"
                  class="btn-close"
                  data-bs-dismiss="modal"
                  aria-label="Close"
                ></button>
              </div>
              <div class="modal-body">
                <div class="cover-drop-zone upload-csv-modal @if(!empty($report->report_cover_image)) hide @endif" id="cover-drop-zone">
                    <div class="drop-cvs" >
                        <div class="cvs-border" >
                            <div class="cvs-import-tile text-center">
                                <p>Drag and drop to upload your cover image </p>
                                <p><span>Acceptable Formats:</span> jpeg, gif, png, pdf</p>
                                <p><span>Acceptable Dimension:</span> 800x700 pixels</p>

                                <!-- <div class="fileUpload btn-broswe blue-btn btn width100">
                                        <span>
                                            <label for="cover-image-file" class="btn browse-btn">Browse</label>
                                            <input id="cover-image-file" name="cover_image" style="visibility:hidden;" type="file">
                                        </span>
                                </div> -->
                                <div class="fileUpload btn-broswe blue-btn btn width100">
                                <span>
                                    <ul class="add-cancel-btn drag-btn">
                                        <li>Drag</li>
                                        <!-- <label for="js-upload-files" class="btn browse-btn">Browse</label> -->
                                        <input id="cover-image-file" name="cover_image" style="visibility:hidden;" type="file">
                                    </ul>
                                </span>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row image_preview @if(empty($report->report_cover_image)) hide @endif" >
                    @if(!empty($report->report_cover_image)) 
                        <input type="hidden" name="image_set" value="true" />
                    @endif
                    <div class="col-md-12 text-center">
                        <img  src="{{url("uploads/report_templates/".$report->report_cover_image)}}" class="img-thumbnail" style="max-height: 300px;"/>
                    </div>
                    <div class="col-md-12 text-center pt-3">
                        <button type="button" class="btn btn-danger image_remove cover">Remove</button>
                    </div>
                </div>

                <div class="report-name">
                  <p class="color-blue mt-3">Report Cover Name</p>
                  <input type="text" class="form-control" placeholder="Report Name" name="report_name" value="@if(!empty($report->report_name)) {{$report->report_name}} @endif" />
                </div>
                <div class="footer-seeting">
                    <p class="color-blue mt-3">Footer Settings</p>
                    <p class="mt-1 color-black">
                    Select which user information you want to display in the
                    footer on the cover page. Main company information will
                    display by default.
                    </p>
                    <div class="company-cover-check">
                        <label class="form-check-label radio-inline"><input class="form-check-input" type="checkbox" value="true" name="user_name" @if(!empty($report->is_footer_user_name)) checked="checked" @endif  >User Name</label>
                        <label class="form-check-label radio-inline"><input class="form-check-input" type="checkbox" value="true" name="user_email" @if(!empty($report->is_footer_user_email)) checked="checked" @endif >User Email </label>
                        <label class="form-check-label radio-inline"><input class="form-check-input" type="checkbox" value="true" name="user_number" @if(!empty($report->is_footer_user_phone)) checked="checked" @endif >Use Mobile Number </label>
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
                <button type="submit" class="btn btn-save">Confirm</button>
              </div>
            </div>
          </div>
        </form>  
        </div>

        <!-- Company Introduction Modal -->
        <div
          class="modal fade"
          id="companyIntroModal"
          tabindex="-1"
          aria-labelledby="companyIntroModalLabel"
          aria-hidden="true"
        >
        <form method="post" action="{{ action('ReportController@storeIntroduction') }}" id="#">
            {{csrf_field()}}
          <div class="modal-dialog modal-dialog-centered project-modal">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="companyIntroModalLabel">
                  Company Introduction
                </h5>
                <button
                  type="button"
                  class="btn-close"
                  data-bs-dismiss="modal"
                  aria-label="Close"
                ></button>
              </div>
              <div class="modal-body">
                <div class="bg-grey">
                    <textarea name="editor1">{{ !empty($introductions->content) ? $introductions->content : null }}</textarea>
                </div>
                <div class="mt-3 bg-grey">
                  <p class="color-blue font-18">Introduction Templates</p>
                  <p class="mt-1 color-black">
                    These introduction templates can be selected by the user
                    when creating report.
                  </p>
                </div>
                <div class="modal-table">
                    <table class="table table-striped" >
                    <tbody>
                    @foreach($templates->where('identifier','introduction') AS $key => $item)
                        {{--@php  dd($item->title) @endphp--}}
                        <tr class="table-body">
                            <td  class="left first-cell">{{$item->title}}</td>
                            <td class="right">
                                <!-- <div class="dropdown">
                                <button class="dropdown-dots dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <img src="{{asset('assets/images/dropdown-dots.png')}}" alt="...">
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                    <li><a href="#"  data-id="{{$item->id}}" class="edit_intro"><img src="{{asset('image/edit.png')}}" alt="..."></a></li>
                                    <li><a href="#" data-id="{{$item->id}}" class="delete_intro"><img src="{{asset('image/trash.png')}}" alt="..."></a></li>
                                </ul>
                            </div> -->
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    </table>
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
                <button type="submit" class="btn btn-save">Save</button>
              </div>
            </div>
          </div>
        </form>  
        </div>

        <!-- Credit Disclaimer Modal -->
        <div
          class="modal fade"
          id="creditDisclaimerModal"
          tabindex="-1"
          aria-labelledby="creditDisclaimerModalLabel"
          aria-hidden="true"
        >
        <form id="report-image" action="{{url('admin/reports/storeInfo')}}" method="POST">
            {{csrf_field()}}
          <div class="modal-dialog modal-dialog-centered project-modal">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="creditDisclaimerModalLabel">
                  Credit Disclaimer
                </h5>
                <button
                  type="button"
                  class="btn-close"
                  data-bs-dismiss="modal"
                  aria-label="Close"
                ></button>
              </div>
              <div class="modal-body">
                <!-- Content for Credit Disclaimer -->
                <!-- Example -->
                    <input class="color-black form-control" type="text" placeholder="Credit Disclaimer" name="credit_disclaimer" value="@if(!empty($report->credit_disclaimer)){{$report->credit_disclaimer}}@endif"/>
                    <div class="coverPageModalCheckBox creditPageModalCheckBox">
                        <p class="font-18 color-blue mt-2 mb-1">Active:</p>
                        <input class="form-check-input" type="radio" id="yes" name="is_disclaimer" value="1" @if($report->is_disclaimer > 0)checked="checked"@endif />
                        <label class="form-check-label" for="yes">Yes</label>
                        <input class="form-check-input" type="radio" id="no" name="is_disclaimer" value="0" @if($report->is_disclaimer < 1)checked="checked"@endif>
                        <label class="form-check-label" for="no">No</label>
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
                <button type="submit" class="btn btn-save">Confirm</button>
              </div>
            </div>
          </div>
        </form>  
        </div>

        <!-- Owner Authorization Modal -->
        <div
          class="modal fade"
          id="ownerAuthModal"
          tabindex="-1"
          aria-labelledby="ownerAuthModalLabel"
          aria-hidden="true"
        >
        <form id="report-image" action="{{ action('ReportController@storeOwnerAuthorization') }}" method="POST">
            {{ csrf_field() }}
          <div class="modal-dialog modal-dialog-centered project-modal">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="ownerAuthModalLabel">
                  Owner Authorization
                </h5>
                <button
                  type="button"
                  class="btn-close"
                  data-bs-dismiss="modal"
                  aria-label="Close"
                ></button>
              </div>
              <div class="modal-body">
                <div class="bg-grey">
                  <p class="color-black">Fieldscope</p>
                </div>
                <input type="text" class="form-control mb-3 mt-3" required value="{{ $report->estimate_terms }}" name="estimate_terms" placeholder="Estimate Terms" name="disclaimercredit">
                <div class="bodysecitems">
                    <p class="font-18 color-blue mb-2">Section Items</p>
                    <p class="color-black">
                    These items will show on owner authorization as additional
                    available options for your clients to select in additions to
                    the other items on the estimate.
                    </p>
                    <div class="row">
                        <div class="col-12">
                            <div id="_section_items_continer" class="rm-companyinfobody-modified">
                                @if( !empty($report->json_data) )
                                    @php
                                        $json_data = json_decode($report->json_data);
                                    @endphp
                                        @for( $i=0; $i < count($json_data->section_item->item); $i++ )
                                            <div class="row mt-3">
                                            <div class="col-10 col-md-9">
                                                <input
                                                type="text"
                                                class="form-control item-input-price "
                                                value="{{ $json_data->section_item->item[$i] }}" 
                                                name="section_item[]" 
                                                placeholder="Item"
                                                />
                                            </div>
                                            <div class="col-2 col-md-3">
                                                <input
                                                type="number"
                                                class="form-control priceinput"
                                                value="{{ $json_data->section_item->price[$i] }}" 
                                                name="section_price[]" 
                                                placeholder="Price"
                                                />
                                                <div class="editdelicons">
                                                    <a class="dropdown-item delIcon delete_section_item" href="javascript:void(0)">
                                                        <span><i class="fa fa-times" aria-hidden="true"></i></span>
                                                    </a>
                                                </div>
                                            </div>
                                            </div>
                                        @endfor    
                                    @else
                                        <div class="row mt-3">
                                            <div class="col-10 col-md-9">
                                                <input
                                                type="text"
                                                name="section_item[]" 
                                                placeholder="Item" 
                                                class="form-control iteminput"
                                                />
                                            </div>
                                            <div class="col-2 col-md-3">
                                                <input
                                                type="number" 
                                                name="section_price[]" 
                                                placeholder="Price" 
                                                class="form-control priceinput"
                                                />
                                                <div class="editdelicons">
                                                    <a class="dropdown-item delIcon delete_section_item" href="javascript:void(0)">
                                                        <span><i class="fa fa-times" aria-hidden="true"></i></span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif 
                                </div>
                            <!-- <p id="add_more_section_item" class="color-blue">Add Item</p> -->
                            <a id="add_more_section_item" class="color-blue" href="javascript:void(0)"><span>Add Item</span> </a>
                        </div>
                    </div>
                </div>
                <div class=" bodysecitems itemoptions">
                    <div class="row">
                        <div class="col-12">
                            <p class="color-blue font-18 mt-3">Item Options</p>
                            <p class="color-black mt-1 mb-2">Example: Roof Color</p>
                            <div id="item_option_container" class="rm-companyinfobody-modified">
                                @if( !empty($report->json_data) )
                                    @php
                                        $json_data = json_decode($report->json_data);
                                    @endphp
                                    @for( $i=0; $i < count($json_data->item_option); $i++ )
                                        <!-- <p class="color-black mt-1 mb-2 aboveinput">Example: Roof Color</p> -->
                                        <div>
                                        <input type="text" value="{{ $json_data->item_option[$i] }}" class="form-control mt-3 mb-3 iteminput-modified" name="item_option[]" placeholder="Option Title">
                                          <div class="editdelicons">
                                              <a class="dropdown-item delIcon delete_section_option" href="javascript:void(0)">
                                                  <span><i class="fa fa-times" aria-hidden="true"></i></span>
                                              </a>
                                          </div>
                                        </div>
                                    @endfor
                                @else
                                    <p class="aboveinput">Example: Roof Color</p>
                                    <input type="text" class="form-control  iteminput-modified" name="item_option[]" placeholder="Option Title">
                                    <div class="editdelicons">
                                        <a class="dropdown-item delIcon delete_section_option" href="javascript:void(0)">
                                            <span><i class="fa fa-times" aria-hidden="true"></i></span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <!-- <p class="color-blue">Add Option</p> -->
                            <a class="color-blue" id="add_more_item_option" href="javascript:void(0)"> <span>Add Option</span></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <p class="mt-4 color-blue font-18 mb-3">
                      Footer Disclaimer
                    </p>
                    <input 
                        type="text" 
                        class="form-control"
                        value="{{ $report->footer_disclaimer }}" 
                        required name="footer_disclaimer" 
                        placeholder="Footer Disclaimer"
                    />
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
                <button type="submit" class="btn btn-save">Confirm</button>
              </div>
            </div>
          </div>
        </form>  
        </div>
    <!-- Modals End -->
</section>
@endsection
@push("page_css")
    <style>
        .reports-card {
            cursor: pointer;
            transition: .1s ease-in;
        }
        .reports-card p {
            font-size: 16px;
            font-family: 'EuclidSquare-Medium' !important;
        }
        .reports-card:hover {
            transform: scale(1.05);
            transition: .1s ease-in;
        }
        .report-pdf .card-img {
            width: 55% !important;
            margin: auto;
        }
        .hide{
            display:none;
        }
        .dashboard header{
            background:transparent !important;
        }
        .enable-header{
            display:none;
        }
        .show{
            display:block !important;
        }
        .editdelicons {
            position: absolute;
            /* bottom: 60px; */
            /* left: 90px; */
            background: #fff;
            width: max-content;
            border: 1px solid #ff1515;
            border-radius: 13px;
            padding: 0px 5px;
            transform: translate(-5px, -60px);
            right: 0;
        }
        .editdelicons a span i {
            color: #ff0000;
        }
        .delete_document i {
            color: #fe0000;
        }
        .modal-dialog {
            max-width: 700px !important;
        }

        .modal-content {
            padding: 0px 15px !important;
        }
        .main-sec {
            padding: 15px 30px;
            background-color: #f7f7fa !important;
        }
        .fileUpload.btn-broswe.blue-btn.btn.width100 {
            pointer-events: none;
        }
    </style>
@endpush
@push("page_js")
<!-- Include Editor JS files. -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@latest/js/froala_editor.pkgd.min.js"></script>
<script src="{{asset('assets/js/jquery-simple-upload.js')}}"></script>
<script>
document.getElementById('report-images').addEventListener('submit', function (e) {
    const fileInput = document.getElementById('fileID');

    if (!fileInput.value) {
        alert("Please select a document before saving.");
        e.preventDefault(); // Prevent form submission
    }
});
</script>
<script>
var editor1 = new FroalaEditor("textarea[name='editor1']",{
    attribution: false,
    autofocus: true,
    videoUpload: false
});
var editor2 = new FroalaEditor("textarea[name='editor2']",{
    attribution: false,
    autofocus: true,
    videoUpload: false
});
// new FroalaEditor('#editor2');
//editor1.setHTMLCode("Use inline HTML or setHTMLCode to init the default content.");
// let introEditor = CKEDITOR.replace( 'editor2' );
    // Trigger file input when "Browse" button is clicked
    document.querySelector('.browse-btn').addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('fileID').click();
            this.className = `cover-drop-zone`;
            logos = e.dataTransfer.files;
            // startUpload(e.dataTransfer.files)
            console.log(e);
            console.log('onDrop','logos.length',logos.length);

            if (FileReader && logos && logos.length) {
                var fr = new FileReader();
                fr.onload = function () {
                    // document.getElementById(outImage).src = fr.result;
                    let imagePreviewObj = $(`#${modalSelector}-upload-form .image_preview`);
                    imagePreviewObj.find('img.img-thumbnail').attr('src',fr.result);

                    if(imagePreviewObj.hasClass('hide')){
                        imagePreviewObj.removeClass('hide');
                    }
                }
                console.log(URL.createObjectURL(logos[0]) );
                fr.readAsDataURL(logos[0]);
                $(`#cover-drop-zone`).addClass('hide');
            }
    });

    // Display the selected file name after choosing a file
    document.getElementById('fileID').addEventListener('change', function () {
        const fileName = this.files[0] ? this.files[0].name : "No file selected";
        document.querySelector('.drop_box header h4').textContent = fileName;
        document.querySelector('.enable-header').classList.add("show");;
        document.getElementById('Filetitle').value = fileName;
    });

</script>
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
        // Company Logo Start

        + function($) {
             'use strict';

             // UPLOAD CLASS DEFINITION
             // ======================

             var dropZone = document.getElementById('drop-zone');
             var uploadForm = document.getElementById('js-upload-form');
             let logos ;

             var startUpload = function(files) {
                 console.log('startUpload');

             }

             uploadForm.addEventListener('submit', function(e) {
                 var uploadFiles = document.getElementById('js-upload-files').files;
                 e.preventDefault()

                 var fd = new FormData($('#js-upload-form')[0]);

                 fd.append('logo',logos[0]);

                 console.log('logo',logos[0]);
                 $.ajax({
                     type: "POST",
                     enctype: 'multipart/form-data',
                     url: '{{URL::to('admin/reports/storeLogo')}}',
                     data: fd,
                     processData: false,
                     contentType: false,
                     cache: false,
                     success: (data) => {
                         console.log('ajax good',$(".alert-success.success"));
                         $('#companyLogoModal').modal('toggle');
                         $("div.alert-success.success").text(data.message).show();
                         setTimeout(()=>{
                             $("div.alert.alert-success.success").hide();
                         },2000);
                     },
                     error: (xhr,textStatus,errorThrown)=>{

                         let errors = Object.values(xhr.responseJSON.data);
                         let errorList = `<li>${errors.join(`</li><li>`)}</li>`;
                         console.log(errorList);

                         $("div.alert-danger.error")
                             .html(`
                                     ${xhr.responseJSON.message}
                                     <ul>${errorList} </ul>
                                  `).show();

                         $('#companyLogoModal').modal('toggle');

                         setTimeout(()=>{
                             $("div.alert.alert-danger.error").hide();
                         },2000);
                     }
                 });

                 startUpload(uploadFiles)
             })

             dropZone.ondrop = function(e) {
                 e.preventDefault();
                 this.className = 'upload-drop-zone';
                 logos = e.dataTransfer.files;
                 // startUpload(e.dataTransfer.files)

                 if (FileReader && logos && logos.length) {
                     var fr = new FileReader();
                     fr.onload = function () {
                         // document.getElementById(outImage).src = fr.result;
                         $('.image_preview').find('img.img-thumbnail').attr('src',fr.result);

                         if($('.image_preview').hasClass('hide')){
                             $('.image_preview').removeClass('hide');
                         }

                     }
                     console.log(URL.createObjectURL(logos[0]) );
                     fr.readAsDataURL(logos[0]);
                     $('.upload-drop-zone').addClass('hide');
                 }
             }

             dropZone.ondragover = function() {
                 this.className = 'upload-drop-zone drop';
                 return false;
             }

             dropZone.ondragleave = function() {
                 this.className = 'upload-drop-zone';
                 return false;
             }




             let modalSelector = "cover";
             let imageFieldName = "cover_image";

             let modalFormSelector = "cover";

             var dropzone = document.getElementById(`${modalSelector}-drop-zone`);
             var form = document.getElementById(`${modalSelector}-upload-form`);

            //  let logos ;

             var startUpload = function(files) {
                 console.log('startUpload');
             }

             form.addEventListener('submit', function(e) {

                 // console.log(e.target);
                 // return false;
                 // var uploadFiles = document.getElementById('js-upload-files').files;
                 e.preventDefault()

                 var fd = new FormData($(`#${modalSelector}-upload-form`)[0]);
                 // Check if a new file has been added
                  if (logos && logos.length > 0) {
                      fd.append(imageFieldName, logos[0]);
                      console.log("imageFieldName", imageFieldName, logos[0]);
                  } else {
                      // No new file selected, so do nothing with `imageFieldName`
                      console.log("No new image selected. Keeping existing image if it exists.");
                  }

                //  fd.append(imageFieldName,logos[0]);

                //  console.log("imageFieldName",imageFieldName,logos[0]);
                 $.ajax({
                     type: "POST",
                     enctype: 'multipart/form-data',
                     url: '{{URL::to('admin/reports/storeCoverInfo')}}',
                     data: fd,
                     processData: false,
                     contentType: false,
                     cache: false,
                     success: (data) => {
                         console.log('ajax good',$(".alert-success.success"));
                         $(`#${modalSelector}PageModal`).modal('toggle');
                         window.location.href = "{{URL::to('admin/reports')}}";
                         setTimeout(()=>{
                             $("div.alert.alert-success.success").hide();
                         },2000);
                     },
                     error: (xhr,textStatus,errorThrown)=>{

                         let errors = Object.values(xhr.responseJSON.data);
                         let errorList = `<li>${errors.join(`</li><li>`)}</li>`;
                         console.log(errorList);

                         $("div.alert-danger.error")
                             .html(`
                                     ${xhr.responseJSON.message}
                                     <ul>${errorList} </ul>
                                  `).show();

                         $(`#${modalSelector}PageModal`).modal('toggle');

                         setTimeout(()=>{
                             $("div.alert.alert-danger.error").hide();
                         },2000);
                     }
                 });

                 // startUpload(uploadFiles)
             })

             dropzone.ondrop = function(e) {
                 e.preventDefault();
                 this.className = `${modalSelector}-drop-zone`;
                 logos = e.dataTransfer.files;
                 // startUpload(e.dataTransfer.files)

                 console.log('onDrop','logos.length',logos.length);

                 if (FileReader && logos && logos.length) {
                     var fr = new FileReader();
                     fr.onload = function () {
                         // document.getElementById(outImage).src = fr.result;
                         let imagePreviewObj = $(`#${modalSelector}-upload-form .image_preview`);
                         imagePreviewObj.find('img.img-thumbnail').attr('src',fr.result);

                         if(imagePreviewObj.hasClass('hide')){
                             imagePreviewObj.removeClass('hide');
                         }
                     }
                     console.log(URL.createObjectURL(logos[0]) );
                     fr.readAsDataURL(logos[0]);
                     $(`#${modalSelector}-drop-zone`).addClass('hide');
                 }
             }

             dropzone.ondragover = function() {
                 this.className = `${modalSelector}-drop-zone drop`;
                 return false;
             }

             dropzone.ondragleave = function() {
                 this.className = `${modalSelector}-drop-zone`;
                 return false;
             }
             <!--endregion-->

         }(jQuery);

        // Company Logo End
        // Logo remove
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
            // Delete Document

            $('.delete_document').click( function(e) {
                e.preventDefault();
                var path = $(this).attr('id');  
                var ele  = $(this); 
                // var msg  = confirm('Are you sure you want to delete this document?');
                // if( msg ){
                    $.ajax({
                    type:'post',
                    url: '{{ action("ReportController@deleteDocument") }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data:{path:path},
                    success: function(res){
                        if( res.code == 200  ){
                            // alert(res.message);
                            ele.parent().parent().remove();
                            window.location.href = "{{URL::to('admin/reports')}}";
                        }
                    }
                    })
                // } else {
                // return false
                // } 
            })

            // Add More Section
            $('#add_more_section_item').click( function(e){
                e.preventDefault();
                var add_more_item = `<div class="row mt-3">
                                        <div class="col-10 col-md-9">
                                            <input type="text" name="section_item[]" placeholder="Item" class="form-control iteminput">
                                        </div>
                                        <div class="col-2 col-md-3">
                                            <input type="number" name="section_price[]" placeholder="Price" class="form-control priceinput">
                                            <div class="editdelicons">
                                                <a class="dropdown-item delIcon delete_section_item" href="javascript:void(0)">
                                                    <span><i class="fa fa-times" aria-hidden="true"></i></span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>`;
                $('#_section_items_continer').append(add_more_item)
            }) 
            // Delete Section Item
            $(document).on( 'click', '.delete_section_item', function(){
                $(this).parent().parent().parent().remove();
            })  
            // Add More Options
            $('#add_more_item_option').click( function(){
                var add_more_item = `<span>
                                    <input type="text" class="form-control iteminput-modified mt-3 mb-3" name="item_option[]" placeholder="Option Title">
                                    
                                    <div class="editdelicons">
                                                <a class="dropdown-item delIcon delete_section_option" href="javascript:void(0)">
                                                    <span><i class="fa fa-times" aria-hidden="true"></i></span>
                                                </a>
                                            </div>
                                    </span>`;
                $('#item_option_container').append(add_more_item);
            })  
            // Delete Section Option
            $(document).on( 'click', '.delete_section_option', function(){
                $(this).parent().parent().remove();
            })  
            
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
        $(document).ready(function() {
            $(".dropdown-toggle").dropdown();
        })
    </script>
@endpush