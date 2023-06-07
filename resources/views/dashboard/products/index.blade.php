@extends('layouts.dashboard.app')
<style>
    .description > * {
        font-size: 16px;
        margin: 0;
    }
</style>
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>@lang('site.products')</h1>
            <ol class="breadcrumb">
                <li><a href="{{route('dashboard.index')}}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a>
                </li>
                <li class="active"> @lang('site.products') </li>
            </ol>
        </section>

        <section class="content">
            <div class="box box-primary">

                <div class="box-header with-border">
                    <h3 class="box-title" style="margin-bottom:10px">  @lang('site.products')
                        : {{ $products->total() }} </h3>
                    <form action="{{ route('dashboard.products.index')}}" method="get">

                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" value="{{request()->search}}"
                                       placeholder="@lang('site.search')">
                            </div>

                            <div class="col-md-4">
                                <select name="category_id" id="" class="form-control" style="padding: 0 8px;">
                                    <option value="" disabled>@lang('site.all_categories')</option>
                                    @foreach($categories as $category)
                                        <option
                                            value="{{ $category->id }}" {{$category->id==$category_id ? 'selected' : '' }} >{{ $category->name }}</option>
                                    @endforeach

                                </select>

                            </div>

                            <div class="add-search-icon col-md-4 mt-5">
                                <button type="submit" class="btn btn-primary"><i
                                        class="fa fa-search"></i> @lang('site.search')</button>

                                @if(auth()->user()->hasPermission('products_create'))
                                    <a href="{{ route('dashboard.products.create') }}" class="btn btn-primary"><i
                                            class="fa fa-plus"></i> @lang('site.add')</a>
                                @else

                                    <a href="#" class="btn btn-primary disabled"><i
                                            class="fa fa-plus "></i> @lang('site.add')</a>

                                @endif
                            </div>
                        </div>

                    </form>

                </div>

                <div class="box-body">
                    @if( $products->count() > 0)
                        <table class="table table-hover">

                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('site.name')</th>
                                <th>@lang('site.description')</th>
                                <th>@lang('site.image')</th>
                                <th>@lang('site.purchase_price')</th>
                                <th>@lang('site.sell_price')</th>
                                <th>@lang('site.profit_percent')</th>
                                <th>@lang('site.stock')</th>
                                <th>@lang('site.action')</th>
                            </tr>
                            </thead>

                            <tbody>

                            @foreach($products as $index=>$product)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td class="description"> {!! $product->description !!}</td>
                                    <td><img src="{{asset('uploads/product_images/'.$product->image)}}"
                                             style="width:80px"></td>
                                    <td>{{ $product->purchase_price }}</td>
                                    <td>{{ $product->sell_price }}</td>
                                    <td>{{$product->profit_percent }} %</td>
                                    <td>{{ $product->stock }}</td>

                                    <td class="edit-delete">

                                        @if(auth()->user()->hasPermission('products_update'))
                                            <a href="{{ asset(route('dashboard.products.edit',$product->id))}}"
                                               class="btn btn-info btn-sm" style="margin-bottom:5px"><i
                                                    class="fa fa-edit"></i> @lang('site.edit')
                                            </a>

                                        @else
                                            <a href="#" class="btn btn-info btn-sm disabled"><i
                                                    class="fa fa-edit"></i> @lang('site.edit') </a>
                                        @endif

                                        @if(auth()->user()->hasPermission('products_delete'))

                                            <form
                                                action="{{asset(route('dashboard.products.destroy',$product->id))}}"
                                                method="POST" style="display:inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger delete btn-sm padd"
                                                        id="delete"><i
                                                        class="fa fa-trash"></i> @lang('site.delete')</button>

                                            </form>
                                        @else
                                            <a href="#"
                                               class="btn btn-danger btn-sm disabled"> @lang('site.delete') </a>

                                        @endif
                                    </td>
                                </tr>

                            @endforeach

                            </tbody>

                        </table>

                        {{ $products->appends(request()->query())->links() }}

                    @else
                        <h2>@lang('site.no_data_found')</h2>
                    @endif
                </div>

            </div>
        </section>
    </div>

@endsection
