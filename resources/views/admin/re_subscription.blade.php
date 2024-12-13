@extends('admin.master')
@section('content')
@section('title', 'Re-Subscription')
<section class="container-fluid main-sec">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="user-type-sec">
                <div>
                    <h2>Re-Subscribe</h2>
                </div>
            </div>
        </div>
        <div class="col-12 mt-2">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <form id="resub_form" method="POST" action="{{URL::to('admin/re_subscription')}}">
                        {{csrf_field()}}
                        <div class="col-md-12 col-sm-12 col-xs-12 resub_form">
                            <div class="form-group col-md-4 resub_form1">
                                <label for="plan">Subscripion Plan</label>
                                <select id="plan" name="plan" class="form-control inputs">
                                    <option disabled selected>Select Subscription Plan</option>
                                    @foreach($data['subs'] AS $key => $item)
                                        <option value="{{$item->id}}">{{ucfirst($item->type)}} - {{ucfirst($item->title)}} -
                                            {{ucfirst($item->per_user_amount)}} per user - ({{$item->duration}} {{ucfirst($item->duration_unit)}})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
        
                            <div class="form-group resub_form2  col-md-4">
                                <label for="card-element">Credit Card Details</label>
                                <div id="card-element" style="    margin-top: 20px;">
                                    <!-- A Stripe Element will be inserted here. -->
                                </div>
                                <!-- Used to display form errors. -->
                                <div id="card-errors" role="alert"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-add ft-left btn-pmadd-modified">Subscribe</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push("page_css")
    <style>
        .col-md-12.col-sm-12.col-xs-12.resub_form {
            display: flex;
            flex-direction: row;
            gap: 30px;
        }

        form#resub_form {
            padding: 10px 0px;
        }

        form#resub_form label {
            padding: 8px 0px;
            font-size: 16px;
        }

        div#card-element {
            margin: 0 !important;
            padding: 6px 14px;
            line-height: 1.5;
            color: #212529;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            border-radius: .375rem;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        select#plan {
            font-size: 14px;
            font-family: 'EuclidSquare-Light';
        }
        button.btn.btn-add.ft-left.btn-pmadd-modified {
            background: #1282f2;
            color: #fff;
            font-family: 'EuclidSquare-Light';
            transition: .3s ease-in;
            margin-top: 15px;
        }

        button.btn.btn-add.ft-left.btn-pmadd-modified:hover {
            background: #000000;
            transition: .3s ease-out;
            color: #ffffff;
            /* box-shadow: 0px 2px 8px 0px #00000054; */
        }
    </style>
@endpush
@push("page_js")
<script type="text/javascript">
        $(document).ready(function () {
            $('.select2').select2({
                placeholder: "Select Your Option"
            });

            $('.stateInput').on('change', function (e) {
                var id = $(this).val();
                var $input = $(this);
                $.getJSON('{{URL::to('admin/cities')}}/' + id, function (response) {
                    var items = [];
                    $.each(response.data, function (key, val) {
                        items.push("<option value='" + val.id + "'>" + val.name + "</option>");
                    });
                    // console.log($('.stateInput').closest('.row').find('.cityInput'));
                    $input.closest('.row').find('.cityInput').append(items.join(""));
                });
                //search(keyword);
            });
        });
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
        var form = document.getElementById('resub_form');
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
            var form = document.getElementById('resub_form');
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