<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Latest compiled and minified CSS -->
    <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css"
            integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
            crossorigin="anonymous"
    />

    <!-- Optional theme -->
    <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css"
            integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp"
            crossorigin="anonymous"
    />

    <!-- Latest compiled and minified JavaScript -->

    <title>Document</title>
<style>
    @font-face {
        font-family: 'creattion';
        src: url('../../../public/assets/fonts/creattion.ttf');
    }
    body,
    html {
        height: 100%;
        padding: 0;
        margin: 0;
      overflow-x: hidden;
    }
    #content::-webkit-scrollbar {
        display: none;
    }
    input:active,
    input:focus,
    input:valid,
    input:focus-within,
    input:focus-visible {
        border: 0 !important;
        box-shadow: none;
    }
    #pdfContainer {
      width: 90%;
      margin: 0 auto 50px;
      height: 80vh; /* Adjust height based on viewport height */
    }
    #pdfContainer embed {
    width: 100%;
    height: 100%;
    }
    @media only screen and (max-width: 1399px) {
    }

    @media only screen and (max-width: 1199px) {
      header p {
        font-size: 14px;
        padding: 0 5px !important;
      }
      header img {
        width: 150px;
      }
      .menu p {
        font-size: 16px !important;
      }
      footer p {
        font-size: 16px;
      }
    }

    @media only screen and (max-width: 991px) {
      header {
        padding: 20px 10px !important;
      }
      header p {
        font-size: 12px !important;
        padding: 0 5px !important;
      }
      header img {
        width: 120px;
      }
      .menu p {
        font-size: 14px !important;
      }
      footer p {
        font-size: 14px;
        padding: 10px 0 !important;
      }
    }

    @media only screen and (max-width: 767px) {
      .divider {
        display: none;
      }
      header div {
        flex-direction: column !important;
        align-items: start !important;
      }
    }

    @media only screen and (max-width: 575px) {
      header img {
        width: 120px;
        margin-bottom: 10px;
      }
      #pdfContainer {
        width: 340px;
        height: 60vh; /* Adjust height based on viewport height */
        overflow: auto;
      }
      header p {
        font-size: 10px !important;
        padding: 0 !important;
      }
      header {
        flex-direction: column !important;
        align-items: start !important;
        padding: 10px 10px 0 10px !important;
      }
      header div {
        margin-bottom: 5px;
      }
      header div {
        flex-direction: column !important;
        align-items: start !important;
      }
      .divider {
        display: none;
      }
      button {
            margin-bottom: 15px;
        }
    }
    .spinner {
    border: 4px solid rgba(0, 0, 0, 0.1); /* Light grey */
    border-top: 4px solid #3498db; /* Blue */
    border-radius: 50%;
    width: 30px;
    height: 30px;
    animation: spin 1s linear infinite;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
</head>
<body>
<header
        class="header"
        style="
				padding: 20px 30px;
				display: flex;
				align-items: center;
				justify-content: space-between;
				color: #00aee7;
				font-weight: bold;
			"
>
    <div>
        <img src="{{url('image/logo-grey.png')}}" alt="" style="height: auto;width: 120px;"/>
    </div>
    <div style="display: flex; align-items: center">
        <p style="margin: 0; padding: 0 10px">
            {{$report->project->address1}}
        </p>
        <p style="margin: 0" class="divider">|</p>
        <p style="margin: 0; padding: 0 10px">Claim # {{$report->project->claim_num}}</p>
        <p style="margin: 0" class="divider">|</p>
        <p style="margin: 0; padding: 0 10px">
            Location Lat:{{round($report->project->latitude, 7)}}, Long: {{round($report->project->longitude, 7)}}
        </p>
    </div>
</header>
    <main>
<div
        style="
				display: flex;
				align-items: center;
				justify-content: space-between;
				box-shadow: 2px 2px 12px rgba(0, 0, 0, 0.362);
				margin-bottom: 20px;
				padding: 10px 20px;
			"
>
    <div class="menu">
          <p style="font-size: 18px; color: black; margin: 0">
        Review Document
            @if(!empty($report->customer_sign))
                (Document is already signed)
            @endif
        </p>
    </div>
    <div>
        @if(empty($report->customer_sign))
        <button
                type="button"
                data-toggle="modal"
                data-target="#signModal"
                style="
						background-color: #00aee7;
						border: 0;
						border-radius: 4px;
						color: #fff;
						width: 70px;
						height: 30px;
					"
        >
            Sign
        </button>
        @else
        <button
                type="button"
                data-toggle="modal"
                data-target="#signModal"
                style="
						background-color: #00aee7;
						border: 0;
                        color: #fff;
                        border-radius: 4px;
                        padding: 10px 23px;
                        position: relative;
                        top: 2px;
                        margin-right: 10px;
					"
        >
            Re-sign
        </button>
            <a
                    type="button"
                    style="
						background-color: #00aee7;
						border: 0;
						border-radius: 4px;
						color: #fff;
						/*width: 90px;
						height: 30px;*/
						padding: 10px 15px;
					"
                    class="btn"

                    href="{{url($report->path)}}"
                    download>
                Download
            </a>
        @endif
    </div>
</div>
<div
        style="
				background-color: #eef0f2;
				padding: 10px 40px;
				box-shadow: 1px 5px 10px rgba(0, 0, 0, 0.1);
				margin-bottom: 30px;
			"
>
    <span id="zoomPercentage">100%</span>
    <button
            id="zoomIn"
            style="
					border-radius: 50%;
					border: 1px solid #989a9c;
					padding: 0;
					width: 30px;
					height: 30px;
					font-size: 20px;
					margin: 0 10px;
					color: #989a9c;
					cursor: pointer;
				"
    >
        +
    </button>
    <button
            id="zoomOut"
            style="
					border-radius: 50%;
					border: 1px solid #989a9c;
					padding: 0;
					width: 30px;
					height: 30px;
					font-size: 20px;
					margin: 0 10px 0 0;
					color: #989a9c;
					cursor: pointer;
				"
    >
        -
    </button>
</div>
      <div id="pdfContainer">
      <!--<iframe id="pdfViewer"></iframe>-->
      </div>
</main>
<footer>
    <p
            style="
					color: #000;
					text-align: center;
					padding: 20px 0;
					background-color: #989a9c;
          margin: 0 !important;
				"
    >
        Copyright {{config("app.name")}}. All rights Reserved {{now()->format("Y")}}
    </p>
</footer>
<div id="signModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{url("report/sign/".$report->token)}}" id="signForm">
            {{csrf_field()}}
        <!-- Modal content-->
        <div class="modal-content">
            <div class="" style="padding: 30px 20px 4px 20px">
                <button type="button" class="close" data-dismiss="modal">
                    &times;
                </button>
                <h4 class="modal-title">Sign document</h4>
            </div>
            <div class="" style="border: 0; padding: 0 20px">
                <p>
                    The parties agree that this agreement may be electronically
                    signed. The parties agree that the electronic signatures appearing
                    on this agreement are the same as handwritten signatures for the
                    purpose of validity, enforceability, and admissibility. By
                    clicking "Agree & sign", your signature will be applied to all
                    signature areas in the document.
                </p>
                <p style="padding: 10px 0 3px 0; margin-bottom: 0;">Signatory Name:</p>
                <p style="font-size: 11px; color: #000; padding: 0">This name will be used to sign the document</p>
                <input
                        type="text"
                        style="
								border: 0;
								width: 100%;
								background-color: #f7f6f7;
								height: 45px;
								padding: 0 10px;
							"
                        name="signatory_name"
                        placeholder="Enter your name exactly"
                />
            </div>
            <div style="border: 0; padding: 0px 20px">
                <div class="alert signValidationAlert" role="alert" style="display: none;">
                    <p><strong>Warning!</strong> Better check yourself, you're not looking too good.</p>
                    <ul class="errorList"></ul>
                </div>
            </div>
            <div
                    class=""
                    style="
							padding: 20px;
							display: flex;
							align-items: center;
							justify-content: end;
						"
            >
                <button
                        type="button"
                        style="
								background-color: #f7f6f7;
								width: 130px;
								height: 40px;
								border: 0;
								border-radius: 6px;
								color: #00aee7;
								margin-left: 10px;
							"
                        data-dismiss="modal"
                >
                    Cancel
                </button>
                <button
                        type="submit"
                        style="
								background-color: #00aee7;
								width: 130px;
								height: 40px;
								border: 0;
								border-radius: 6px;
								color: #fff;
								margin-left: 10px;
							"
                >
                    Agree & sign
                </button>
            </div>
        </div>
        <div id="loadingSpinner" class="spinner" style="display:none;"></div>
        </form>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfobject/2.2.7/pdfobject.min.js"></script>
<script
        src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"
></script>
<script>
    $(document).ready(function () {


        // Path to your PDF file
        var pdfUrl = '{{url($report->path)}}';//'https://pdfobject.com/pdf/sample.pdf'
        var pdfDoc = null;
        var scale = 1.0;
        // var pdfViewer = document.getElementById('pdfViewer');
        var zoomInBtn = document.getElementById("zoomIn");
        var zoomOutBtn = document.getElementById("zoomOut");
        var zoomPercentageSpan = document.getElementById("zoomPercentage");

        // Specify the worker source for PDF.js
        // pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.worker.min.js';

        // Function to zoom in
        zoomInBtn.addEventListener("click", function () {
            scale += 0.1;
            renderPdf(scale);
        });

        // Function to zoom out
        zoomOutBtn.addEventListener("click", function () {
            scale -= 0.1;
            renderPdf(scale);
        });
        // function renderPdf(scale) {
        //     if (pdfDoc) {
        //         pdfDoc.getPage(1).then(function (page) {
                 //    var viewport = page.getViewport({ scale: scale });
                 //    var embed = document.createElement("embed");
                 //   var context = canvas.getContext("2d");
                 //   embed.src = pdfUrl + "#zoom=" + (scale * 100);
                 //   embed.type = "application/pdf";
                 //   embed.width = viewport.width;
                 //   embed.height = viewport.height;
                 //   pdfContainer.innerHTML = "";
                 //   pdfContainer.appendChild(embed);
                 //   updateZoomPercentage(scale);
                 //   canvas.height = viewport.height;
                 //   canvas.width = viewport.width;
                 //   var renderContext = {
                 //       canvasContext: context,
                 //       viewport: viewport,
                 //   };
                 //   page.render(renderContext);

                    // Convert canvas to data URL
                   // var dataUrl = canvas.toDataURL();

                    // Set iframe source to data URL
                   // pdfViewer.src = dataUrl;

                    // pdfContainer.innerHTML = "";
                    // pdfContainer.appendChild(canvas);
                 //   updateZoomPercentage(scale);
        //         });
        //     }
        // }

        // Load PDF using PDF.js
        // pdfjsLib.getDocument(pdfUrl).promise.then(function (pdf) {
        //     pdfDoc = pdf;
        //     renderPdf(scale);
        // });

        // Function to render PDF
        function renderPdf(scale) {
            var options = {
                pdfOpenParams: {
                    zoom: scale * 100 + "%",
                }
            };
            PDFObject.embed(pdfUrl, "#pdfContainer", options);
            updateZoomPercentage(scale);
        }

        // Load PDF
        renderPdf(scale);

        // Function to update zoom percentage display
        function updateZoomPercentage(scale) {
            var percentage = Math.round(scale * 100) + "%";
            zoomPercentageSpan.textContent = percentage;
        }
        // Options for PDFObject (optional)
        // var options = {
        //     pdfOpenParams: {
        //         navpanes: 0,
        //         toolbar: 0,
        //         statusbar: 0,
        //         view: "FitH",
        //     },
        // };

        // Embed the PDF into the specified container
        // PDFObject.embed(pdfUrl, "#pdfContainer", options);




        $("#signForm").submit(function(e){

            e.preventDefault();

            resettingAlert();

            console.log(e.currentTarget,$(e.target).find("select,textarea, input").serialize());

            $.ajax({
                type: 'POST',
                url: e.target.action,
                data: $(e.target).find("select,textarea, input").serialize(),
                beforeSend: function () {
                    console.log('type=submit',$("#signForm").find('button[type="submit"]'));
                    $("#signForm").find('button[type="submit"]').attr('disabled','disabled');
                    $("#loadingSpinner").show(); // Show the spinner
                },
                success: function (data) {
                    console.log('success: _signature_submit', data);
                    $(".signValidationAlert").find('p').text(data.message);
                    $(".signValidationAlert").addClass('alert-success');
                    $(".signValidationAlert").show();
                    return true;
                },
                error: function (data) {
                    console.log('_signature_submit', data.responseJSON.message);
                    // console.log('_signature_submit', data.responseJSON.data.join('</li><li>'));
                    let errors = Object.values(data.responseJSON.data);

                    $(".signValidationAlert").find('p').text(data.responseJSON.message);
                    if (errors.length) {
                        let errorList = `<li>${errors.join('</li><li>')}</li>`;
                        $(".signValidationAlert").find('ul.errorList').html(errorList);
                        console.log('_signature_submit2 ', errorList);
                    } else {
                        $(".signValidationAlert").find('ul.errorList').html('');
                    }
                    $(".signValidationAlert").addClass('alert-danger');
                    $(".signValidationAlert").show();
                    return false;
                },
                complete: function(){
                    $("#signForm").find('button[type="submit"]').attr('disabled',false);
                    $("#loadingSpinner").hide(); // Hide the spinner
                }
            });


        })

        function resettingAlert(){
            $(".signValidationAlert").hide();
            $(".signValidationAlert").removeClass('alert-danger').removeClass('alert-success');
            $(".signValidationAlert").find('p').text('');
            $(".signValidationAlert").find('ul.errorList').html('');
        }
    })
</script>
</body>
</html>
