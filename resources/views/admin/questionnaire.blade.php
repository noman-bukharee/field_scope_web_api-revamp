@extends('admin.master') 
@section('content') 
@section('title', 'Questionnaire Management')
@php
    use Illuminate\Support\Str;
@endphp
<section class="container-fluid main-sec">
  <div class="row">
    <div class="col-12 mt-5">
      <div class="user-type-sec">
        <div>
          <h2>Questionnaire Management</h2>
        </div>
        <div class="d-flex flex-wrap align-items-center">
          <!-- Buttons for different modals -->
            <a class="btn-theme questionnaire-btn" href="{{ URL::to('admin/questionnaire/add') }}">
              + Add New Question
            </a>
          <!-- </button> -->
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="rofing-box">
        <div class="accordion question-accord" id="card-container" data-limit="5" >
            @php
                // Group the questions by category_name
                $groupedQuestions = collect($data['data'])->groupBy('category_name');
                
            @endphp
            
            @foreach($groupedQuestions as $category => $questions)
                <div class="accordion-items record-item">
                    <h2 class="accordion-header" id="heading-{{ Str::slug($category) }}">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse-{{ Str::slug($category) }}" aria-expanded="true"
                            aria-controls="collapse-{{ Str::slug($category) }}">
                            {{ $category }} <span class="rof-blue">{{ count($questions) }}</span>
                        </button>
                    </h2>
                    <div id="collapse-{{ Str::slug($category) }}" class="accordion-collapse collapse"
                        aria-labelledby="heading-{{ Str::slug($category) }}" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <ul class="main-ul">
                                @php $count = 1; @endphp
                                @foreach($questions as $question)
                                    <li>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <p class="rof-heading">
                                                    Question No: {{ $count}} 
                                                </p>
                                            </div>
                                            <div>
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary roof-btn dropdown-toggle"
                                                        type="button" id="dropdownMenuButton{{ $question['id'] }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $question['id'] }}">
                                                        <li><a class="dropdown-item" href="{{URL::to('admin/questionnaire/editQuestionnaireDetails/'.$question['id'])}}">Edit</a></li>
                                                        <li><a class="dropdown-item" href="#" onclick="deleteRow({{$question['id']}})">Delete</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <p>
                                                {{ $question['query'] }}
                                            </p>
                                        </div>
                                    </li>
                                @php $count++; @endphp
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                
            @endforeach
            <nav class="pagination-records" aria-label="Page navigation ">
                <ul class="pagination justify-content-center" id="pagination">
                    <!-- jQuery will populate pagination items here -->
                </ul>
            </nav>
        </div>

        
      </div>
    </div>
  </div>
  <div class="row"></div>
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
  </style> 
  @endpush 
  @push("page_js") 
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

        var deleteId; // Store the id to be deleted

        // Delete Row Function
        function deleteRow(id) {
            deleteId = id;  // Set the id to be used later for deletion
            $('#deleteConfirmationModal').modal('show');  // Show the confirmation modal
        }
        // Handle delete confirmation button click
        $('#confirmDeleteButton').on('click', function() {
            $.ajax({
                url: '{!! url('admin/questionnaire/delete') !!}/'+deleteId,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  // Add CSRF token to the headers
                },
                dataType: 'JSON',
                success: function(response) {
                    $('#deleteConfirmationModal').modal('hide'); // Hide the modal after deletion
                    $('html, body').animate({scrollTop: '0px'}, 300);
                    $("div.alert-success.success").text(response.message).show();
                         setTimeout(()=>{
                             $("div.alert.alert-success.success").hide();
                         },5000);
                        window.location.reload(); // Reload the page upon successful deletion
                },
                error: function(response) {
                    $('#deleteConfirmationModal').modal('hide');
                    $('html, body').animate({scrollTop: '0px'}, 300);
                    $("div.alert-danger.error").text(response.responseJSON.message).show();
                         setTimeout(()=>{
                             $("div.alert.alert-danger.error").hide();
                         },5000);
                    console.log(response.responseJSON.message)
                }
            });
        });
  </script> 
  @endpush  