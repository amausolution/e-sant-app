<div>
    {{-- Do your work, then step back. --}}
</div>
@push('scripts')
    <!-- iziToast -->
    <link rel="stylesheet" href="{{ au_file('partner/assets/css/iziToast.min.css') }}">
    <script src="{{ au_file('partner/assets/js/iziToast.min.js') }}"></script>

    <script>
        window.addEventListener('success', event => {
            iziToast.success({
                title: '{{__('Success')}}',
                message: event.detail.message,
                position: 'topRight',
            });
        })
        window.addEventListener('error', event => {
            iziToast.error({
                title: '{{__('Error')}}',
                message: event.detail.message,
                position: 'topRight',
            });
        })
        window.addEventListener('warning', event => {
            iziToast.warning({
                title: '{{__('Caution')}}',
                message: event.detail.message,
                position: 'topRight',
            });
        })
        window.addEventListener('info', event => {
            iziToast.info({
                title: {{__('Info')}},
                message: event.detail.message,
                position: 'topRight',
            });
        })

    </script>
@endpush
