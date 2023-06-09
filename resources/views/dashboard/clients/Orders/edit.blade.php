@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.edit_order')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a>
                </li>
                <li><a href="{{ route('dashboard.orders.index') }}">@lang('site.orders')</a></li>
                <li class="active">@lang('site.edit_order')</li>
            </ol>
        </section>

        <section class="content">

            <div class="row">

                <div class="col-md-6">

                    <div class="box box-primary">

                        <div class="box-header">

                            <h3 class="box-title" style="margin-bottom: 10px">@lang('site.categories')</h3>

                        </div><!-- end of box header -->

                        <div class="box-body">

                            @foreach ($categories as $category)

                                <div class="panel-group">

                                    <div class="panel panel-info">

                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse"
                                                   href="#{{ str_replace(' ', '-', $category->name) }}">{{ $category->name }}</a>
                                            </h4>
                                        </div>

                                        <div id="{{ str_replace(' ', '-', $category->name) }}"
                                             class="panel-collapse collapse">

                                            <div class="panel-body">

                                                @if ($category->products->count() > 0)

                                                    <table class="table table-hover">
                                                        <tr>
                                                            <th>@lang('site.name')</th>
                                                            <th>@lang('site.stock')</th>
                                                            <th>@lang('site.price')</th>
                                                            <th>@lang('site.add')</th>
                                                        </tr>

                                                        @foreach ($category->products as $product)
                                                            <tr>
                                                                <td>{{ $product->name }}</td>
                                                                <td>{{ $product->stock }}</td>
                                                                <td>{{ $product->sell_price }}</td>
                                                                <td>
                                                                    @if($product->stock > 0)
                                                                        <a href=""
                                                                           id="product-{{ $product->id }}"
                                                                           data-name="{{ $product->name }}"
                                                                           data-stock="{{ $product->stock }}"
                                                                           data-id="{{ $product->id }}"
                                                                           data-price="{{ $product->sell_price }}"
                                                                           class="btn {{ in_array($product->id, $order->products->pluck('id')->toArray()) ? 'btn-default disabled' : 'btn-success add-product-btn' }} btn-sm">
                                                                            <i class="fa fa-plus"></i>
                                                                        </a>

                                                                    @else
                                                                        <a href=""
                                                                           class="btn btn-sm add-product-btn btn-default disabled">
                                                                            <i class="fa fa-plus"></i>
                                                                        </a>
                                                                    @endif


                                                                </td>
                                                            </tr>
                                                        @endforeach

                                                    </table><!-- end of table -->

                                                @else
                                                    <h5>@lang('site.no_records')</h5>
                                                @endif

                                            </div><!-- end of panel body -->

                                        </div><!-- end of panel collapse -->

                                    </div><!-- end of panel primary -->

                                </div><!-- end of panel group -->

                            @endforeach

                        </div><!-- end of box body -->

                    </div><!-- end of box -->

                </div><!-- end of col -->

                <div class="col-md-6">

                    <div class="box box-primary">

                        <div class="box-header">

                            <h3 class="box-title">@lang('site.orders')</h3>

                        </div><!-- end of box header -->

                        <div class="box-body">

                            @include('partials._errors')

                            <form
                                action="{{ route('dashboard.clients.orders.update', ['order' => $order->id, 'client' => $client->id]) }}"
                                method="post">

                                {{ csrf_field() }}

                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>@lang('site.product')</th>
                                        <th>@lang('site.quantity')</th>
                                        <th>@lang('site.price')</th>
                                    </tr>
                                    </thead>

                                    <tbody class="order-list">

                                    <input type="number" name="total_price" id="input-total-price-order" hidden>


                                    @foreach ($order->products as $product)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td><input type="number" name="products[{{ $product->id }}][quantity]"
                                                       data-price="{{ number_format($product->sell_price, 2) }}"
                                                       class="form-control input-sm product-quantity-edit" min="1"
                                                       value="{{ $product->pivot->quantity }}"></td>
                                            <td class="product-price">{{ number_format($product->sell_price * $product->pivot->quantity, 2) }}</td>
                                            <td class="product-stock" hidden>{{$product->stock}}</td>
                                            <td class="quantity-before-edit" hidden>{{ $product->pivot->quantity }}</td>
                                            <td>
                                                <button class="btn btn-danger btn-sm remove-product-btn"
                                                        data-id="{{ $product->id }}"><span class="fa fa-trash"></span>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>

                                </table><!-- end of table -->

                                <h4 class="check_quantity hidden" style="color:red" id="check_quantity">@lang('site.check_quantity')</h4>

                                <h4>@lang('site.total_price') : <span
                                        class="total-price">{{ number_format($order->total_price, 2) }}</span></h4>

                                <button class="btn btn-primary btn-block" id="add-order-form-btn"><i
                                        class="fa fa-edit"></i> @lang('site.edit_order')</button>

                            </form><!-- end of form -->

                        </div><!-- end of box body -->

                    </div><!-- end of box -->

                    @if ($client->orders->count() > 0)

                        <div class="box box-primary">

                            <div class="box-header">

                                <h3 class="box-title" style="margin-bottom: 10px">@lang('site.previous_orders')
                                    <small></small>
                                </h3>

                            </div><!-- end of box header -->

                            <div class="box-body">

                                {{--                                @foreach ($orders as $order)--}}

                                {{--                                    <div class="panel-group">--}}

                                {{--                                        <div class="panel panel-success">--}}

                                {{--                                            <div class="panel-heading">--}}
                                {{--                                                <h4 class="panel-title">--}}
                                {{--                                                    <a data-toggle="collapse" href="#{{ $order->created_at->format('d-m-Y-s') }}">{{ $order->created_at->toFormattedDateString() }}</a>--}}
                                {{--                                                </h4>--}}
                                {{--                                            </div>--}}

                                {{--                                            <div id="{{ $order->created_at->format('d-m-Y-s') }}" class="panel-collapse collapse">--}}

                                {{--                                                <div class="panel-body">--}}

                                {{--                                                    <ul class="list-group">--}}
                                {{--                                                        @foreach ($order->products as $product)--}}
                                {{--                                                            <li class="list-group-item">{{ $product->name }}</li>--}}
                                {{--                                                        @endforeach--}}
                                {{--                                                    </ul>--}}

                                {{--                                                </div><!-- end of panel body -->--}}

                                {{--                                            </div><!-- end of panel collapse -->--}}

                                {{--                                        </div><!-- end of panel primary -->--}}

                                {{--                                    </div><!-- end of panel group -->--}}

                                {{--                                @endforeach--}}

                                {{--                                {{ $orders->links() }}--}}

                            </div><!-- end of box body -->

                        </div><!-- end of box -->

                    @endif

                </div><!-- end of col -->

            </div><!-- end of row -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->

@endsection
