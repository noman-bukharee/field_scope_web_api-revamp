@extends('admin.master')
@section('content')


    <style>
      /* body {
        margin: 0;
      } */
    </style>
    <section class="container-fluid main-sec">
        <div class="row">
            <div class="col-12 mt-5">
                <div class="user-type-sec">
                    <div>
                        <h2>Edit Photo Info</h2>
                    </div>
                </div>
                <form id="photo_feed_update">
                {{csrf_field()}}
                    <div class="col-12 mt-5">
                        <div id="editor" style="height: 800px"></div>
                    </div>
                    <div class="row pt-4">
                        <div class="col-md-12 textarea">
                            <label>Annotation</label>
                            <textarea class="form-control" rows="6" name="note" >{{$data['pMedia']['note']}}</textarea>
                        </div>
                    </div>
                    <div class="row pt-3">
                        @foreach($data['pMedia']['tags_data'] AS $key => $item)
                            <div class="col-md-3 form-group">
                                <label title="Tag title">{{$item->name}}</label>
                                <input type="hidden" name="pmt_tag_id[]" value="{{$item->tag_id}}" />
                                <input type="hidden" name="pmt_target_id[]" value="{{$item->target_id}}" />
                                <input type="text" name="pmt_qty[]" class="form-control"
                                    @if(!empty($item->qty))
                                    value="{{$item->qty}}"
                                        @endif
                                />
                            </div>
                        @endforeach
                    </div>
                    <div class="row pt-3">
                        <div class="col-md-3 form-group">
                            <button type="button" class="btn btn-primary mr-2" name="save" id="save" value="Save">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection

@push("page_css")
    <style>
        /* aside#sidebar {
            display: none;
        } */
        div#editor {
            position: relative;
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
    var imageUrl = "{{URL::to('uploads/media/'.$data['pMedia']['path']."?".$data['pMedia']['updated_at'])}}";
let editorInstance; // Declare a variable to store the editor instance

const initEditor = async () => {
  editorInstance = await PhotoEditorSDK.PhotoEditorSDKUI.init({
    container: '#editor',
    // Please replace this with your license: https://img.ly/dashboard
    license: '',
    image: imageUrl,
    assetBaseUrl: "{{asset('assets/pesdk')}}",
  });

  // The export event can be used if the user clicks on the export button
  editorInstance.on(PhotoEditorSDK.UIEvent.EXPORT, async image => {
    // handle the returned image here
    console.log('Exported image:', image);
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
        window.location.href = "{{URL::to('admin/photo_feed')}}";
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
