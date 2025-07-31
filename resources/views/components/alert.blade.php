<div>
    <style>
        .swal2-confirm.btn-confirm-azul,
        .swal2-cancel.btn-confirm-azul {
            background-color: #4A90E2 !important;
            color: white !important;
            border: none !important;
            border-radius: 4px;
            padding: 8px 24px;
            font-weight: bold;
            font-size: 14px;
        }
    </style>
    <script>
        Swal.fire({
            title: '{{ $title }}',
            text: `{!! trim($slot) ?: '' !!}`,
            icon: '{{ $icon }}',
            confirmButtonText: 'Fechar',
            allowOutsideClick: false,
            allowEscapeKey: false,
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn-confirm-azul'
            }
        });
    </script>
</div>