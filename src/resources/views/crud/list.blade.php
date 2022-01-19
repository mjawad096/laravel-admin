@extends('laravel-admin::layouts.app')

@section('content')
    <!-- Zero configuration table -->
    <section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title w-100">
                            <div class="row align-items-center">
                                <div class="col">
                                    @if (!empty($filter_fields))
                                        <form name="table-filters" class="row">
                                            @foreach($filter_fields as $field)
                                                <div class="col-md-4">
                                                    @php
                                                        $field['default']  = request($field['name'], $field['default'] ?? null);
                                                    @endphp
                                                    
                                                    @include('laravel-admin::fields.field', $field)
                                                </div>
                                            @endforeach
                                        </form> 
                                    @endif
                                </div>

                                @if($links->create !== null)
                                    <div class="col-md-2">
                                        <div class="text-right">
                                            @if(!empty($links->create->html))
                                                {!! $links->create->html !!}
                                            @else
                                                <a href="{{ $links->create->link }}" class="btn btn-sm btn-success">{{ $links->create->text }}</a>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            {{-- <p class="card-text"></p> --}}
                            <div class="table-responsive">
                                <table class="table jd-datatable">
                                    <thead>
                                        <tr>
                                            @foreach($table_columns as $column)
                                                @php $column['title'] = $column['title'] ?? $column['name'] @endphp
                                                <th class="{{ ($column['hidden'] ?? false) ? 'no-show' : '' }} {{ !($column['searchable'] ?? true) ? 'no-search' : '' }} {{ ($column['sortable'] ?? $column['name'] !== 'actions' ) ? '' : 'no-sort' }}">{{ title_case($column['title']) }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@include('laravel-admin::inc.datatables-config')