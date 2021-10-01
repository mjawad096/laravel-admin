@extends('laravel-admin::layouts.app')

@section('content')
    <section>
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ $entery }} detials</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <table class="table table-striped mb-0">
                                <tbody>
                                    @php
                                        $dates = $item->getDates();   
                                    @endphp

                                    @foreach ($item->toArray() as $key => $value)
                                        @php
                                            if(in_array($key, $dates)){
                                                $value = new \Carbon\Carbon($value);
                                            }                                            
                                        @endphp
                                        <tr>
                                            <td><strong>{{ title_case($key) }}</strong></td>
                                            <td>{{ toString($value) }}</td>
                                        </tr>                                
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="text-right">
                                            {!! $actions ?? null !!}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection