@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>@lang('site.clients')</h1>
            <ol class="breadcrumb">
                <li><a href="{{route('dashboard.index')}}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a>
                </li>
                <li class="active"> @lang('site.clients') </li>
            </ol>
        </section>

        <section class="content">
            <div class="box box-primary">

                <div class="box-header with-border">
                    <h3 class="box-title" style="margin-bottom:10px">  @lang('site.clients')
                        : {{ $clients->total() }}</h3>
                    <form action="{{ route('dashboard.clients.index')}}" method="get">
                        <div class="row">

                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" value="{{request()->search}}"
                                       placeholder="@lang('site.search')">
                            </div>

                            <div class="add-search-icon col-md-4 mt-5">
                                <button type="submit" class="btn btn-primary"><i
                                        class="fa fa-search"></i> @lang('site.search')</button>

                                @if(auth()->user()->hasPermission('clients_create'))
                                    <a href="{{ route('dashboard.clients.create') }}" class="btn btn-primary"><i
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
                    @if( $clients->count() > 0)
                        <table class="table table-hover">

                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('site.name')</th>
                                <th>@lang('site.phone')</th>
                                <th>@lang('site.address')</th>
                                <th>@lang('site.add_order')</th>
                                <th>@lang('site.action')</th>
                            </tr>
                            </thead>

                            <tbody>

                            @foreach($clients as $index=>$client)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $client->name }}</td>
                                    <td>{{ implode('-',array_filter($client->phone))}}</td>
                                    <td>{{ $client->address }}</td>
                                    <td>
                                        @if(auth()->user()->hasPermission('orders_create'))
                                            <a href="{{ asset(route('dashboard.clients.orders.create',$client->id))}}"
                                               class="btn btn-primary btn-sm">@lang('site.add_order')
                                            </a>

                                        @else
                                            <a href=" "
                                               class="btn btn-primary btn-sm disabled">@lang('site.add_order')
                                            </a>
                                        @endif
                                    </td>

                                    <td class="edit-delete">

                                        @if(auth()->user()->hasPermission('clients_update'))
                                            <a href="{{ asset(route('dashboard.clients.edit',$client->id))}}"
                                               class="btn btn-info btn-sm"><i
                                                    class="fa fa-edit"></i> @lang('site.edit')
                                            </a>

                                        @else
                                            <a href="#" class=" btn btn-info btn-sm disabled"><i
                                                    class=" fa fa-edit "></i> @lang('site.edit') </a>
                                        @endif

                                        @if(auth()->user()->hasPermission('clients_delete'))

                                            <form
                                                action="{{asset(route('dashboard.clients.destroy',$client->id))}}"
                                                method="POST" style="display:inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger delete btn-sm delete"
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

                        {{ $clients->appends(request()->query())->links() }}

                    @else
                        <h2>@lang('site.no_data_found')</h2>
                    @endif
                </div>

            </div>
        </section>
    </div>

@endsection
