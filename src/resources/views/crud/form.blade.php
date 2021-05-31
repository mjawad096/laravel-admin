@extends('laravel-admin::layouts.app')

@section('content')
    <section>
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ $editing_form ? 'Modify' : 'Add new' }} {{ $entery }}</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form form-vertical {{ $errors->count() ? 'error' : '' }}" enctype="multipart/form-data" method="post" action="{{ $editing_form ? route("{$route_base}.update", $item->id) : route("{$route_base}.store") }}" novalidate>
                                @csrf

                                @if($editing_form)
                                    @method('PUT')
                                @endif

                                <div class="form-body">
                                    <div class="row">
                                        @foreach($form_fields as $field)
                                            <div class="col-12">
                                                @include('admin.form_fields.field', $field)
                                            </div>
                                        @endforeach

                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection 
