
<script src="{{ asset('assets/js/jquery.min.js')}}"></script>
   <script src="{{ asset('assets/js/bootstrap.js') }}"></script>
   <script>
      $(function () {
          $('input, select').on('focus', function () {
              $(this).parent().find('.input-group-text').css({'border-color':'#80bdff','color':'#00ADE7','background': '#F7FDFF'});
          });
          $('input, select').on('blur', function () {
              $(this).parent().find('.input-group-text').css({'border-color':'#ced4da','color':'gray','background':'#F5F5F5'});
          });
      
      });
      
            
   </script>