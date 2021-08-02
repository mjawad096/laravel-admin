@push('vendor_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
@endpush

@push('page_css')
@endpush

@push('vendor_js')
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/pdfmake.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/vfs_fonts.js') }}"></script>

    <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>
    
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/buttons.print.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js') }}"></script>
@endpush

@push('js')
    <script>
        jQuery(document).ready(function ($) {
            $('.jd-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ request()->url() }}",
                columns: @json($table_columns ?? []),
                columnDefs: [
                    { orderable: false, targets: 'no-sort' },
                    { visible: false, targets: 'no-show' }
                ],
                order: [[ 0, "desc" ]],
            })
        });
    </script>
@endpush