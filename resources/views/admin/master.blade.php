<?php
// echo "<pre>";
$session = \Session::all();
$user = $session['user'];
// print_r($session['user']); die;

?>
@php
use App\Models\User;
    

    $userGroupId = $user->user_group_id;
    if ($userGroupId == 1) {
        $roleName = 'admin';
    } 
    elseif($userGroupId == 2){

        //Get Agent role Title
        $userInsector = User::leftJoin('company_group AS cg', 'cg.id', '=', 'user.company_group_id')
            ->where('user.id', session('user')->id)
            ->where('cg.id', $user->company_group_id)
            ->first();
        if($userInsector->role_id == 2){
            $roleName = 'manager';
        }
        else{
            $roleName = 'standard';
        }
    }
   
@endphp
@php  
$userImage = ''; 
$userImageBasePath = env('APP_URL') .config('constants.USER_IMAGE_PATH'); 
if(!empty(session('user')->image_url))
    { 
        $userImage = $userImageBasePath.session('user')->image_url; 
    }
else{
     $userImage = env('APP_URL').'image/default_user.png'; 
} 
@endphp 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('image/logo-icon.png') }}" type="image/gif" size="16x16">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>{{env('APP_NAME')}} Admin - @yield('title')</title>
    <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
            crossorigin="anonymous"
    />
    <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
            integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer"
    />
    <link
            href="https://cdn.datatables.net/v/bs5/dt-2.0.7/datatables.min.css"
            rel="stylesheet"
    />
    <link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.0/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{asset("assets/css/style.css")}}" />
    <link rel="stylesheet" href="{{asset("assets/css/responsive.css")}}" />
    <link rel="stylesheet" href="{{asset("assets/css/img-lightbox.css")}}" />
    @stack("page_css")
    <script>
        var base_url = "{{URL::to('/')}}";
    </script>

    <!--Start: Page Level CSS-->
    @stack('page_level_css')
    <!--End: Page Level CSS-->
    <style>
        .active-nav {
            color: #1282f2 !important;
        }
    </style>
</head>
<body>
<div class="d-flex toggled" id="wrapper">
    <!-- Sidebar -->
    @include('admin.include.sidebar-nav')
    <!-- /#sidebar -->
    <!-- Page Content -->
    <main class="dashboard flex-grow-1">
        @include('admin.include.header')
        @include('admin.error')
        @yield('content')
    </main>
    <!-- /#page-content -->
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- <script
        src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"
></script> -->
<script src="https://cdn.datatables.net/v/bs5/dt-2.0.7/datatables.min.js"></script>
<script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"  
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"
></script>

@stack("page_js")

<script>
    var itemsPerPage = 12;
    var filteredItems = $('.record-item'); // Initially, all items

    // Define showPage as a global function
    function showPage(page) {
        var cardContainer = $('#card-container'); // The container holding the project cards
        var pagination = $('#pagination'); // Pagination container

        // Hide all items initially
        $('.record-item').hide();

        // If there are filtered items, use them; otherwise, use all items
        var items = filteredItems;
        var totalItems = items.length;

        // Remove any existing 'No projects found' message
        cardContainer.find('.not-found').remove();

        // If no items are found, display a "No projects found" message
        if (totalItems === 0) {
            // cardContainer.append('<p class="not-found">No projects found</p>'); // Add new message
            pagination.empty(); // Clear pagination if no items
            return; // Stop execution if no items
        }

        // Calculate start and end indices for pagination
        var start = (page - 1) * itemsPerPage;
        var end = start + itemsPerPage;

        // Show items for the current page
        items.slice(start, end).show();

        // Build pagination
        pagination.empty();
        var totalPages = Math.ceil(totalItems / itemsPerPage);

        // Create Previous button
        if (page > 1) {
            pagination.append('<li class="page-item"><a class="page-link" href="#" data-page="' + (page - 1) + '"> < </a></li>');
        }

        // Display pagination with ellipses
        if (totalPages <= 5) {
            // If there are 5 or fewer pages, show all
            for (var i = 1; i <= totalPages; i++) {
                appendPageItem(pagination, i, page);
            }
        } else {
            // Show first page
            appendPageItem(pagination, 1, page);
            
            // Show ellipsis if necessary
            if (page > 3) {
                pagination.append('<li class="page-item disabled"><span class="page-link">...</span></li>');
            }

            // Show a range of pages around the current page
            var startPage = Math.max(2, page - 1); // Start from 2 or one before current
            var endPage = Math.min(totalPages - 1, page + 2); // End at totalPages - 1 or one after current
            
            for (var i = startPage; i <= endPage; i++) {
                appendPageItem(pagination, i, page);
            }

            // Show ellipsis if necessary
            if (page < totalPages - 2) {
                pagination.append('<li class="page-item disabled"><span class="page-link">...</span></li>');
            }

            // Show last page
            appendPageItem(pagination, totalPages, page);
        }

        // Create Next button
        if (page < totalPages) {
            pagination.append('<li class="page-item"><a class="page-link" href="#" data-page="' + (page + 1) + '"> > </a></li>');
        }
    }

    // Helper function to append page item
    function appendPageItem(pagination, pageNum, currentPage) {
        var pageItem = $('<li class="page-item"><a class="page-link" href="#" data-page="' + pageNum + '">' + pageNum + '</a></li>');
        if (pageNum === currentPage) {
            pageItem.addClass('active');
        }
        pagination.append(pageItem);
    }

    $(document).ready(function() {
        // Initial page load
        showPage(1);

        // Pagination click event
        $('#pagination').on('click', 'a', function(e) {
            e.preventDefault();
            var page = parseInt($(this).data('page')); // Get the page number from data attribute
            showPage(page);
        });

        // Search filter
        $('#filter').on('keyup', function() {
            var searchValue = $(this).val().toLowerCase();

            // Filter items based on search input and update filteredItems
            filteredItems = $('.record-item').filter(function() {
                return $(this).find('.title').text().toLowerCase().indexOf(searchValue) > -1;
            });

            // Recalculate pagination for filtered results and show first page
            showPage(1);
        });

        // Clear search input handling
        $('#filter').on('input', function() {
            var searchValue = $(this).val().toLowerCase();

            // If search field is cleared, reset to show all items
            if (!searchValue) {
                filteredItems = $('.record-item'); // Reset to all items
                showPage(1); // Show the first page of all items
            }
        });
    });
</script>

</body>
</html>
