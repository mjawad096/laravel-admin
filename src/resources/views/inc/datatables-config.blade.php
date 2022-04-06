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
            let ajax_status = null;
            let filterForm = $('form[name=table-filters]');

            let updateQueryParams = function(params, _url, return_url){
                try {
                    let url = _url || window.location.href
                    url = new URL(url);

                    params.forEach(function(param){
                        if(param.value){
                            url.searchParams.set(param.name, param.value);
                        }else{
                            url.searchParams.delete(param.name);
                        }
                    });

                    let newUrl = url.toString();

                    if(return_url){
                        return newUrl;
                    }

                    window.history.pushState({path: newUrl}, '', newUrl);
                } catch (e) {console.log(e)}

                if(return_url){
                    return _url;
                }
            }

            let debounce = function(func, timeout = 250){
                let timer;
                return function(...args){
                    clearTimeout(timer);
                    timer = setTimeout(() => { func.apply(this, args); }, timeout);
                };
            }
            
            let updateCreateLink = function(){
                let anchor = $('.link-create a');
                let fields = filterForm.find('.append_to_create_link').find(':input');

                let create_url = updateQueryParams(fields.serializeArray(), anchor.attr('href'), true);
                anchor.attr('href', create_url);
            };

            updateQueryParams(filterForm.serializeArray());
            updateCreateLink();

            $.fn.dataTable.ext.errMode = function( settings, techNote, message ){
                if(ajax_status == 401 || ajax_status == 419){
                    message = 'Sorry, your session has expired. please refresh and try again.'
                }
                alert(message)
            }

            let $jdDataTable = $('.jd-datatable').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                ajax: {
                    url: @json(['url' => request()->fullUrl()]).url,
                    data: function(d){
                        filterForm
                            .serializeArray()
                            .forEach(function(element){
                                d[element.name] = element.value;
                            });
                    },
                },
                columns: @json($table_columns ?? []),
                columnDefs: [
                    { orderable: false, targets: 'no-sort' },
                    { visible: false, targets: 'no-show' },
                    { searchable: false, targets: 'no-search' },
                ],
                order: [[ {{ $sorting_column ?? 0 }}, "desc" ]],
                stateSaveCallback: function(settings, data) {
                    try {
                        (settings.iStateDuration === -1 ? sessionStorage : localStorage).setItem(
                            'DataTables_' + settings.sInstance + '_' + location.pathname +  location.search,
                            JSON.stringify(data)
                        );
                    } catch (e) {}
                },
                stateLoadCallback: function(settings) {
                    try {
                        return JSON.parse(
                            (settings.iStateDuration === -1 ? sessionStorage : localStorage).getItem(
                                'DataTables_' + settings.sInstance + '_' + location.pathname +  location.search
                            )
                        );
                    } catch (e) {
                        return {};
                    }
                },
            });

            if(window.afterJdDataTableInit && typeof window.afterJdDataTableInit === 'function'){
                window.afterJdDataTableInit($jdDataTable);
            }

            $jdDataTable.on('xhr', function(e, settings, json, xhr){
                ajax_status = xhr.status
            });

            filterForm.on('change', debounce(function(e) {
                $jdDataTable.ajax.reload();

                updateQueryParams(filterForm.serializeArray());

                updateCreateLink();                
            }));
        });
    </script>
@endpush