@extends('admin.master')
@section('content')
@section('title', 'Project Photo Feeds Edit')
<pre>
</pre>
<section class="container-fluid main-sec">
    <div class="row details-row mt-4">
        <div class="col-md-5 ">
            <div class="card details-row-card">
                <div class="card-body">
                    <h5 class="card-title">{{ isset($data['pMedia']['project']['name']) ?  $data['pMedia']['project']['name'] : 'No Name' }}</h5>
                    <form id="photo_feed_update">
                    {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Area:</label>
                                    <h5>{{isset($data['pMedia']['category']['name']) ?  $data['pMedia']['category']['name'] : 'N/A' }}</h5>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Lat:</label>
                                    <h5>{{isset($data['pMedia']['project']['latitude']) ?   $data['pMedia']['project']['latitude'] : 'N/A' }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Inspection Date:</label>
                                    <h5>{{\Carbon\Carbon::parse($data['pMedia']['project']['inspection_date'])->format('m/d/y') }}</h5>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Long:</label>
                                    <h5>{{isset($data['pMedia']['project']['longitude']) ?    $data['pMedia']['project']['longitude'] : 'N/A' }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Claim No:</label>
                                    <h5>{{isset($data['pMedia']['project']['claim_num']) ?   $data['pMedia']['project']['claim_num'] : 'N/A' }}</h5>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Quantity:</label>
                                    <h5>{{isset($data['pMedia']['category']['min_quantity']) ?    $data['pMedia']['category']['min_quantity'] : 'N/A' }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="form-group annotation">
                            <label>Photo Tag annotation:</label>
                            <textarea class="form-control" rows="6" name="note" id="noteField">{{isset($data['pMedia']['note']) ?   $data['pMedia']['note'] : '' }}</textarea>
                            <!-- <h5>Test Sync</h5> -->
                        </div>
                        <div class="photo-action-btn">
                            <!-- <a href="{{url('admin/photo_feed/edit/'.$data['id'])}}" class="btn btn-primary mr-2">Edit</a> -->
                            <input type="button" class="btn btn-primary mr-2" name="save" id="save" value="Done"/>
                            <a href="{{url('admin/project/detail')}}/{{$data['pMedia']['project']['id']}}" class="btn btn-primary mr-2"> Cancel </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-7">
             @if(!$data['pMedia']['tags_data']->isEmpty()) 
                <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#myModal">
                    <span class="tag-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-tags" viewBox="0 0 16 16">
                            <path d="M3 2v4.586l7 7L14.586 9l-7-7zM2 2a1 1 0 0 1 1-1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 2 6.586z"/>
                            <path d="M5.5 5a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1m0 1a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3M1 7.086a1 1 0 0 0 .293.707L8.75 15.25l-.043.043a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 0 7.586V3a1 1 0 0 1 1-1z"/>
                        </svg>
                    </span>
                </a>
             @endif 
            <div id="editor" style="height: 800px"></div>
        </div>
    </div>
</section>
<pre>`
</pre>
<!-- Add Project-->
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered project-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Tags</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <form id="update_form">
        {{ csrf_field() }}
        <div class="modal-body">
            <!-- Check if tags data is not empty -->
            @if(!$data['pMedia']['tags_data']->isEmpty())
                <div class="tags-data-parent">
                    @foreach($data['pMedia']['tags_data'] as $key => $item)
                        <div class="form-group tags-data">
                            <!-- Checkbox to show/hide fields -->
                            <label title="Tag title">
                                <input type="checkbox" class="toggle-input" data-target="tag-{{$key}}" /> {{$item->name}}
                            </label>
                            <input type="hidden" name="pmt_tag_id[]" value="{{$item->tag_id}}" />
                            <input type="hidden" name="pmt_target_id[]" value="{{$item->target_id}}" />
                            
                            <!-- Text input for quantity -->
                            <div class="input-fields" id="tag-{{$key}}" style="display:none;">
                                <input type="text" name="pmt_qty[]" class="form-control" 
                                    @if(!empty($item->qty))
                                        value="{{$item->qty}}"
                                    @endif
                                />
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
            <button type="button" id="update" class="btn btn-save">Save</button>
        </div>
    </form>
    </div>
  </div>
</div>
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
        div#editor {
            position: relative;
        }
        button[data-test="MainBarButtonClose"] {
            display: none;
        }

        span.tag-badge {
            position: relative;
            z-index: 999;
            top: 100px;
            left: 90%;
            background: #fff;
            padding: 16px 16px;
            border-radius: 100%;
        }

        span.tag-badge svg {
            transform: scale(1.3);
        }
        .tags-data {
            padding: 5px 0px;
        }

        .tags-data label {
            padding: 8px 0px;
            font-size: 16px;
        }
        .photo-action-btn {
            display: flex;
            flex-direction: row;
            gap: 10px;
            align-items: center;
            justify-content: center;
        }

        .photo-action-btn a,.photo-action-btn input {
            width: 50%;
            padding: 13px 18px;
            border-radius: 10px;
        }
        .photo-action-btn a{
            background:#8d96a7;
            color:#fff !important;
            border:1px solid #0000004f
        }
        .photo-action-btn input:hover {
            background:#000;
        }
        .tags-data-parent {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: flex-start;
        }
        .tags-data-parent label {
            font-family: 'EuclidSquare-Light';
            font-size: 14px !important;
            display: flex;
            flex-direction: row;
            align-items: baseline;
            gap: 10px;
            cursor: pointer;
        }
        .tags-data-parent input[type="text"] {
            font-family: 'EuclidSquare-Light';
            font-size: 14px !important;
            width: 200px;
            margin-left: 20px;
        }
        /* Hide ImgLY */
        .sc-cMljjf.lfgeyf[direction="vertical"]:first-child ul div:nth-child(n+2):nth-child(-n+7),
        .sc-cMljjf.lfgeyf[direction="vertical"]:first-child ul div:nth-child(9) {
            display:none;
        }
    </style>
@endpush
@push("page_js")
<script src="https://unpkg.com/react@16.13.1/umd/react.production.min.js"></script>
<script src="https://unpkg.com/react-dom@16.13.1/umd/react-dom.production.min.js"></script>
<script src="https://unpkg.com/react-dom@16.13.1/umd/react-dom-server.browser.production.min.js"></script>
<script src="https://unpkg.com/styled-components@4.4.1/dist/styled-components.min.js"></script>
<script src="https://cdn.img.ly/packages/imgly/photoeditorsdk/latest/umd/no-polyfills.js"></script>
<script>
    document.querySelectorAll('.toggle-input').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const targetId = this.getAttribute('data-target');
            const targetElement = document.getElementById(targetId);
            if (this.checked) {
                targetElement.style.display = 'block';
            } else {
                targetElement.style.display = 'none';
            }
        });
    });
    var imageUrl = "{{URL::to('uploads/media/'.$data['pMedia']['path']."?".$data['pMedia']['updated_at'])}}";
    // var imageUrl = "https://fieldscope.qa.retrocubedev.com/uploads/media/{{$data['pMedia']['path']."?".$data['pMedia']['updated_at']}}";
    
    // var imageUrl = $('#img_url').attr('data-url');
    console.log(imageUrl)
    let editorInstance; // Declare a variable to store the editor instance
    
    const initEditor = async () => {
      editorInstance = await PhotoEditorSDK.PhotoEditorSDKUI.init({
       
        container: '#editor',
        // Please replace this with your license: https://img.ly/dashboard
        license: "",
        image: imageUrl,
        assetBaseUrl: "{{asset('assets/pesdk')}}",
        language: 'en',
        layout: 'advanced',
        theme: 'light',
        defaultTool: 'library',
        scaleImageToFit: true,
        engine: {
            crossOrigin: 'anonymous',
            downscaleOptions: {
            maxMegaPixels: {
                desktop: 10,
                mobile: 5,
            },
            maxDimensions: {
                height: 1920,
                width: 1080,
            },
            },
            backgroundColor: [0,0,0,0],
        },
        
        
      });
    
      // The export event can be used if the user clicks on the export button
      editorInstance.on(PhotoEditorSDK.UIEvent.EXPORT, async image => {
        // handle the returned image here
        // console.log('Exported image:', image);
      });
    };
    
    initEditor();
    

// Save Image on button click
$('#save').on('click', async function (e) {
  console.log('Saving');
  
  // Disable the save button to avoid multiple clicks
  $(this).prop('disabled', true);

  if (!editorInstance) {
    console.error("Editor instance not initialized.");
    return;
  }

  // Export the edited image from PhotoEditor SDK as a blob
  const exportedImage = await editorInstance.export(); // Use the stored editor instance

  // Convert the exported image to dataURL
  const dataURL = exportedImage.src;

  // Create FormData
  var formData = new FormData($('#photo_feed_update')[0]);
  
  // Append the image dataURL as a Blob to FormData
  formData.append('image', dataURLToBlob(dataURL), 'sampleimage.png');

  // Make the AJAX request
  $.ajax({
    type: "POST",
    enctype: 'multipart/form-data',
    url: '{{URL::to('admin/photo_feed/update/'.$data['pMedia']['id'])}}',
    data: formData,
    processData: false,
    contentType: false,
    cache: false,
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    success: (data) => {
      console.log(data.code);

      $(this).prop('disabled', false); // Re-enable the save button
      if (data.code == 200) {
        // alert(data.message);
        window.location.href = "{{url('admin/project/detail')}}/{{$data['pMedia']['project']['id']}}";
      } else {
        alert("Update Failed");
      }
    },
    error: function (xhr, status, error) {
      alert(xhr.responseText);
      $(this).prop('disabled', false); // Re-enable the save button on error
    }
  });
});

// Helper function to convert dataURL to Blob
function dataURLToBlob(dataURL) {
  const byteString = atob(dataURL.split(',')[1]);
  const mimeString = dataURL.split(',')[0].split(':')[1].split(';')[0];
  const arrayBuffer = new ArrayBuffer(byteString.length);
  const uint8Array = new Uint8Array(arrayBuffer);

  for (let i = 0; i < byteString.length; i++) {
    uint8Array[i] = byteString.charCodeAt(i);
  }

  return new Blob([uint8Array], { type: mimeString });
}
//Update Tags
// Save Image on button click
$('#update').on('click', async function (e) {
    // Create FormData
  var formData = new FormData($('#update_form')[0]);

  // Make the AJAX request
  $.ajax({
    type: "POST",
    enctype: 'multipart/form-data',
    url: '{{URL::to('admin/photo_feed/update/'.$data['pMedia']['id'])}}',
    data: formData,
    processData: false,
    contentType: false,
    cache: false,
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    success: (data) => {
      console.log(data.code);

      $(this).prop('disabled', false); // Re-enable the save button
      if (data.code == 200) {
        // alert(data.message);
        window.location.href = "{{url('admin/project/photo/edit/'.$data['pMedia']['id'])}}";
      } else {
        alert("Update Failed");
      }
    },
    error: function (xhr, status, error) {
      alert(xhr.responseText);
      $(this).prop('disabled', false); // Re-enable the save button on error
    }
  });
});
</script>
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