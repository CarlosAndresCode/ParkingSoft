@if (config('sweetalert.alwaysLoadJS') === true || Session::has('alert.config') || Session::has('alert.delete'))
    @if (config('sweetalert.animation.enable'))
        <link rel="stylesheet" href="{{ config('sweetalert.animatecss') }}">
    @endif

    @if (config('sweetalert.theme') != 'default')
        <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-{{ config('sweetalert.theme') }}" rel="stylesheet">
    @endif

    @if (config('sweetalert.neverLoadJS') === false)
        <script src="{{ $cdn ?? asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
    @endif

    @if (Session::has('alert.delete') || Session::has('alert.config'))
        <script>
            document.addEventListener('click', function(event) {
                // Check if the clicked element or its parent has the attribute
                var target = event.target;
                var confirmDeleteElement = target.closest('[data-confirm-delete]');

                if (confirmDeleteElement) {
                    event.preventDefault();
                    Swal.fire({!! Session::pull('alert.delete') !!}).then(function(result) {
                        if (result.isConfirmed) {
                            var form = document.createElement('form');
                            form.action = confirmDeleteElement.href;
                            form.method = 'POST';
                            form.innerHTML = `
                            @csrf
                            @method('DELETE')
                        `;
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                }
            });

            @if (Session::has('alert.config'))
                Swal.fire({!! Session::pull('alert.config') !!});
            @endif
        </script>
    @endif
@endif

<script>
    // Global SweetAlert confirmation for forms/buttons with data-confirm attribute
    document.addEventListener('DOMContentLoaded', function () {
        const confirmHandler = function (e) {
            const target = e.target;
            const buttonWithConfirm = target.closest('[data-confirm]');

            if (buttonWithConfirm && buttonWithConfirm.tagName !== 'FORM') {
                const form = buttonWithConfirm.closest('form');
                if (form) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    const message = buttonWithConfirm.getAttribute('data-confirm') || '¿Estás seguro?';
                    Swal.fire({
                        icon: 'warning',
                        title: 'Confirmación',
                        text: message,
                        showCancelButton: true,
                        confirmButtonText: 'Sí, continuar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            }
        };

        const submitHandler = function (e) {
            const form = e.target;
            // Only intercept forms that have the data-confirm attribute directly
            // and are NOT the real-time search forms
            if (form.matches('form[data-confirm]') && !form.querySelector('.real-time-search')) {
                e.preventDefault();
                e.stopImmediatePropagation();
                const message = form.getAttribute('data-confirm') || '¿Estás seguro?';
                Swal.fire({
                    icon: 'warning',
                    title: 'Confirmación',
                    text: message,
                    showCancelButton: true,
                    confirmButtonText: 'Sí, continuar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        };

        // Use capture phase to intercept before other listeners
        document.addEventListener('click', confirmHandler, true);
        document.addEventListener('submit', submitHandler, true);
    });
</script>
