@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>@lang('site.dashboard')</h1>
            <ol class="breadcrumb">
                <li class="active"><i class="fa fa-dashboard"></i> @lang('site.dashboard') </li>
            </ol>
        </section>

        <section class="content">
            <h2 style="margin-top:0; margin-bottom: 20px">@lang('site.this_is_Dashboard')</h2>
            <div class="row">

                {{-- categories--}}
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-aqua" style="background-color: #00c0ef">
                        <div class="inner">
                            <h3>{{ $categories_count }}</h3>

                            <p>@lang('site.categories')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="{{ route('dashboard.categories.index') }}" class="small-box-footer">@lang('site.show') <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                {{--products--}}
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-green" style="background-color: #00a65a">
                        <div class="inner">
                            <h3>{{ $products_count }}</h3>

                            <p>@lang('site.products')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('dashboard.products.index') }}" class="small-box-footer">@lang('site.show') <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                {{--clients--}}
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-red" style="background-color: #dd4b39">
                        <div class="inner">
                            <h3>{{ $clients_count }}</h3>

                            <p>@lang('site.clients')</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-user"></i>
                        </div>
                        <a href="{{ route('dashboard.clients.index') }}" class="small-box-footer">@lang('site.show') <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                {{--users--}}
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-yellow" style="background-color: #f39c12">
                        <div class="inner">
                            <h3>{{ $users_count }}</h3>

                            <p>@lang('site.users')</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-users"></i>
                        </div>
                        <a href="{{ route('dashboard.users.index') }}" class="small-box-footer">@lang('site.show') <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

            </div><!-- end of row -->

            <div class="box box-solid">

                <div class="box-header">
                    <h3 class="box-title">Sales Graph</h3>
                </div>
                <div class="box-body border-radius-none">
                    <div class="chart" id="line-chart" style="height: 250px;"></div>
                </div>
                <!-- /.box-body -->
            </div>

        </section><!-- end of content -->
    </div>

@endsection
@push('scripts')

    <script>
        //line chart
        var line = new Morris.Line({
            element: 'line-chart',
            resize: true,
            data: [
                    @foreach ($sales_data as $data)
                {
                    ym: "{{ $data->year }}-{{ $data->month }}", sum: "{{ $data->sum }}"
                },
                @endforeach
            ],
            xkey: 'ym',
            ykeys: ['sum'],
            labels: ['@lang('site.total_price')'],
            lineWidth: 2,
            hideHover: 'auto',
            gridStrokeWidth: 0.4,
            pointSize: 4,
            gridTextFamily: 'Open Sans',
            gridTextSize: 11
        });
    </script>

@endpush
