
<!-- iziToast -->
<link rel="stylesheet" href="{{ au_file('partner/assets/css/iziToast.min.css') }}">
<script src="{{ au_file('partner/assets/js/iziToast.min.js') }}"></script>
<!--message-->
    @if(session()->has('success'))

    <script type="text/javascript">
        iziToast.success({
            title: '{{__('Success')}}',
            message: '{{ session('success') }}',
            position: 'topRight',
        });
    </script>
    @endif

    @if(session()->has('error'))
    <script type="text/javascript">
        iziToast.error({
            title: '{{__('Error')}}',
            message: '{{ session('error') }}',
            position: 'topRight',
        });
    </script>
    @endif

    @if(session()->has('Warning'))
    <script type="text/javascript">
        iziToast.warning({
            title: '{{__('Caution')}}',
            message: '{{ session('warning') }}',
            position: 'topRight',
        });
    </script>
    @endif

@if(session()->has('notify'))
    @foreach(session('notify') as $msg)
        <script>
            "use strict";
            iziToast.{{ $msg[0] }}({message:"{{ trans($msg[1]) }}", position: "topRight"});
        </script>
    @endforeach
@endif

@if ($errors->any())
    <script>
        "use strict";
        @foreach ($errors->all() as $error)
        iziToast.error({
            message: '@lang($error)',
            position: "topRight"
        });
        @endforeach
    </script>

@endif
<script>
    "use strict";
    function notify(status, message) {
        if(typeof message == 'string'){
            iziToast[status]({
                message: message,
                position: "topRight"
            });
        }else{
            $.each(message, function(i, val) {
                iziToast[status]({
                    message: val,
                    position: "topRight"
                });
            });
        }

    }

    function notifyOne(status, message) {
        iziToast[status]({
            message: message,
            position: "topRight"
        });
    }
</script>

