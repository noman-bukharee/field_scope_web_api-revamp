@extends('admin.master')
@section('content')
@section('title', 'User Management')
<!-- {{session('role')}} -->
<section class="container-fluid main-sec">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="user-type-sec">
                <div>
                    <h2>User Management</h2>
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
                        <button class="btn-theme"  data-bs-toggle="modal" data-bs-target="#myModal">+ Add New</button>
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
                        <th>User Name</th>
                        <th>Email Address</th>
                        <th >Phone Number</th>
                        <th class="text-start">User Type</th>
                        <th id="action">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                        
                        @if(count($records))
                            @foreach($records['data'] AS $item)
                            <!-- {{print_r($item['role_id'])}} -->
                        <tr>
                            <td>{{$item['name']}}</td>
                            <td>{{$item['email']}}</td>
                            <td >{{$item['mobile_no']}}</td>
                            <td class="text-start">{{$item['user_type']}}</td>
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
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <!-- <img src="../assets/img/close-icon.png" alt=""> -->
                    </button>
                </div>
                <form id="add_form" action="{{URL::to('admin/user_management/store')}}" method="POST">
                    {{csrf_field()}}

                    <div class="modal-body companyinfobody rm-companyinfobody-modified ">
                        <div class="row">

                            <div class="col-md-12 companyinfobody rm-companyinfobody-modified">
                                <!-- <label>Name</label> -->
                                <input type="text" name="name"
                                        placeholder="Enter Name" class="form-control place-color" aria-describedby="emailHelp"  autocomplete="off">
                            </div>
                            <div class="col-md-12 companyinfobody rm-companyinfobody-modified">
                                <!-- <label>Email</label> -->
                                <input type="text" name="email"
                                        placeholder="Enter Email" class="form-control place-color" aria-describedby="emailHelp"  autocomplete="off">
                            </div>
                            <div class="col-md-12 companyinfobody rm-companyinfobody-modified">
                                <!-- <label>Password</label> -->
                                <input type="password" name="password"
                                        placeholder="Enter Password" class="form-control place-color" aria-describedby="emailHelp"  autocomplete="off">
                            </div>
                            <div class="col-md-12 companyinfobody rm-companyinfobody-modified">
                                <!-- <label>Confirm Password</label> -->
                                <input type="password" name="password_confirmation"
                                        placeholder="Confirm Password" class="form-control place-color" aria-describedby="emailHelp"  autocomplete="off">
                            </div>
                            <div class="col-md-12 companyinfobody rm-companyinfobody-modified">
                                <!-- <label>Mobile Number</label> -->
                                <input type="text" name="mobile_no"
                                        placeholder="Enter  Mobile Number" class="form-control place-color" aria-describedby="emailHelp"  autocomplete="off">
                            </div>
                            <div class="col-md-12 companyinfobody rm-companyinfobody-select-modified">
                                <!-- <label>User Type</label> -->
                                <select name="company_group_id" id="sel1" class="form-select add-select">
                                    <option value="">User type</option>
                                    @foreach ($CompanyUsers['companyGroup'] as $company)
                                        <option value="{{$company['id']}}">{{$company['title']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 companyinfobody rm-companyinfobody-modified iu_mgt_special">
                                <!-- <label for="card-element">Credit Card Details</label> -->
                                <div id="card-element">
                                    <!-- A Stripe Element will be inserted here. -->
                                </div>
                                <!-- Used to display form errors. -->
                                <div id="card-errors" role="alert"></div>
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
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Inspector User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <!-- <img src="../assets/img/close-icon.png" alt=""> -->
                    </button>
                </div>
            <form id="update_form" action="{{URL::to('admin/user_management/update')}}" method="POST">
                {{csrf_field()}}
                <input type="hidden" name="page" value="{{\Request::input('page',1)}}">
                <div class="modal-body companyinfobody rm-companyinfobody-modified">
                    <div class="fcol-md-12 companyinfobody rm-companyinfobody-modified">
                        <input type="text" name="name" class="form-control place-color"
                                placeholder="Enter Name">
                    </div>
                    <div class="col-md-12 companyinfobody rm-companyinfobody-modified">
                        <input type="text" name="email"  class="form-control place-color"
                                placeholder="Enter Email">
                    </div>
                    <div class="col-md-12 companyinfobody rm-companyinfobody-modified">
                        <input type="text" name="mobile_no" class="form-control place-color"
                                placeholder="Enter Mobile Number">
                    </div>
                    <div class="col-md-12 companyinfobody rm-companyinfobody-modified">
                        <select name="company_group_id"  id="sel2" class="form-select add-select">
                            <option value="">User type</option>
                            @foreach ($CompanyUsers['companyGroup'] as $company)
                                <option value="{{$company['id']}}">{{$company['title']}}</option>
                            @endforeach
                        </select>
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
        var updateUrl = "{{URL::to('admin/user_management/update')}}";
        $(document).ready(function () {
           var table = $('#example').DataTable({
                paging: true,
                searching: false,
                ordering: true,
                info: true,
                "language": {
                    "info": "Page _PAGE_ of _PAGES_",
                }
            })
        })

        // Edit Row
        function editRow(id) {
            
            var $editModal = $('#editModal');
                $.ajax({
                    url: "{{URL::to('admin/user_management/editFormDetails')}}/" + id,
                    method: "GET",
                    data: '',
                    success: function (response) {
                        var data = response.data;
                        $('#update_form').attr('action', updateUrl + '/' + response.data.id);

                        $('#update_form input[name="name"]').val(response.data.first_name+' '+response.data.last_name);
                        $('#update_form input[name="email"]').val(response.data.email);
                        $('#update_form input[name="mobile_no"]').val(response.data.mobile_no);

                        console.log('group id',response.data.company_group_id
                        );

                        $('#update_form select[name="company_group_id"] option').each(function (key, item) {
                            if (parseInt($(item).attr('value')) == parseInt(response.data.company_group_id)) {
                                $(item).attr('selected', true);
                                console.log('selected')
                            }
                        });
                        $('#update_form select[name="company_group_id[]"]').trigger('change');
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

        // Delete Row
        function deleteRow(id) {
            deleteId = id; // Set the id for the deletion
            $('#deleteConfirmationModal').modal('show'); // Show the modal
        }

        // When the confirm delete button is clicked
        $('#confirmDeleteButton').on('click', function() {
            $.ajax({
                url: '{!! url('admin/user_management/delete') !!}/' + deleteId,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  // Add CSRF token to the headers
                },
                dataType: 'JSON',
                success: function(response) {
                    window.location.reload(); // Reload the page upon successful deletion
                },
                error: function() {
                    // Handle error if needed
                }
            });

            $('#deleteConfirmationModal').modal('hide'); // Hide the modal after deletion
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
<script src="https://js.stripe.com/v3/"></script>
    <script type="text/javascript">
        // Create a Stripe client.
        var stripe = Stripe('{{config('app.stripe_pub_key')}}');

        // Create an instance of Elements.
        var elements = stripe.elements();

        // Custom styling can be passed to options when creating an Element.
        // (Note that this demo uses a wider set of styles than the guide below.)
        var style = {
            base: {
                color: '#32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };

        // Create an instance of the card Element.
        var card = elements.create('card', {style: style});

        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');

        // Handle real-time validation errors from the card Element.
        card.addEventListener('change', function (event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // Handle form submission.
        var form = document.getElementById('add_form');
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            stripe.createToken(card).then(function (result) {
                if (result.error) {
                    // Inform the user if there was an error.
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    // Send the token to your server.
                    stripeTokenHandler(result.token);
                }
            });
        });

        // Submit the form with the token ID.
        function stripeTokenHandler(token) {
            // Insert the token ID into the form so it gets submitted to the server
            var form = document.getElementById('add_form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);

            // Submit the form
            form.submit();
        }
    </script>    
@endpush