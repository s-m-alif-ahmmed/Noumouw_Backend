
@vite('resources/js/app.js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src='{{asset('backend/libs/choices.js/public/assets/scripts/choices.min.js')}}'></script>
<script src="{{asset('backend/libs/@popperjs/core/umd/popper.min.js')}}"></script>
<script src="{{asset('backend/libs/tippy.js/tippy-bundle.umd.min.js')}}"></script>
<script src="{{asset('backend/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{asset('backend/libs/prismjs/prism.js')}}"></script>
<script src="{{asset('backend/libs/lucide/umd/lucide.js')}}"></script>
<script src="{{asset('backend/js/tailwick.bundle.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js" integrity="sha512-bUg5gaqBVaXIJNuebamJ6uex//mjxPk8kljQTdM1SwkNrQD7pjS+PerntUSD+QRWPNJ0tq54/x4zRV8bLrLhZg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- App js -->
<script src="{{asset('backend/js/app.js')}}"></script>




@stack('scripts')

<script>
    $(document).ajaxStart(function() {
        NProgress.start();
    });

    $(document).ajaxComplete(function() {
        NProgress.done();
    });
</script>



