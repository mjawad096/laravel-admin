@extends('laravel-admin::layouts.app', [
    'menu_active' => 'settings',
    'page_title' => 'Settings',
    'breadcrumbs' => [
        'Settings' => route('laravel-admin.setting.edit'),
        'Update' => null,
    ],
])

@section('content')
    <form class="form form-vertical {{ $errors->count() ? 'error' : '' }}" method="post" action="{{ route('laravel-admin.setting.update') }}" enctype='multipart/form-data' novalidate>
        <div class="form-body">
            <div class="row match-height ">
                @csrf

                @method('PUT')

                <input type="hidden" name="truncate" value="{{ request('truncate') ? 1 : 0 }}">
                @foreach ($field_groups ?? [] as $group)
                    <div class="col-md-{{ $group['cols'] ?? 6 }}">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{ $group['title'] ?? null }}</h4>
                            </div>

                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        @foreach ($group['fields'] ?? [] as $field)
                                            @php
                                                $setting_key = str_replace('-', '.', $field['name']);
                                                $field['default'] = setting($setting_key, $field['default'] ?? null);
                                            @endphp
                                            <div class="col-12">
                                                @include('laravel-admin::fields.field', $field)
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="col-12 text-right">
                    <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">Save</button>
                </div>
            </div>
        </div>
    </form>
@endsection
