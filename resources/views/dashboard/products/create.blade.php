@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">
            <h1>@lang('site.products')</h1>
            <ol class="breadcrumb">
                <li class=""><a href="{{route('dashboard.index')}}"><i
                            class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class=""><a href="{{route('dashboard.products.index')}}"><i
                            class="fa fa-dashboard"></i> @lang('site.products')</a></li>
                <li class="active">@lang('site.add') </li>
            </ol>
        </section>

        <section class="content">
            <div class="box box-primary">

                <div class="box-header">
                    <h3 class="box-title">@lang('site.add')</h3>
                </div>

                <div class="box-body">

                    @include('partials._errors')

                    <form action="{{asset(route('dashboard.products.store'))}}" method="post"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label>@lang('site.categories')</label>
                            <select name="category_id" class="form-control" style="padding: 0px 8px;">
                                <option value="" disabled>@lang('site.all_categories')</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>


                    @foreach (config('translatable.locales') as $locale)
                            <div class="form-group">
                                <label>@lang('site.' . $locale . '.name')</label>
                                <input type="text" name="{{ $locale }}[name]" class="form-control" value="{{ old($locale . '.name') }}">
                            </div>
                        @endforeach

                        @foreach (config('translatable.locales') as $locale)
                            <div class="form-group">
                                <label>@lang('site.' . $locale . '.description')</label>
                                <textarea name="{{ $locale }}[description]" class="form-control ckeditor" ></textarea>
                            </div>
                        @endforeach

                        <div class="form-group" >
                            <label>@lang('site.image')</label>
                            <input type="file" name="image" id="image" class="form-control">
                        </div>

                        <div class="form-group">
                            <img id="image-preview" src="{{ asset('uploads/product_images/default.png') }}" style="width: 80px" class="thumbnail">
                        </div>

                        <div class="form-group" >
                            <label>@lang('site.purchase_price')</label>
                            <input type="number" name="purchase_price" class="form-control" value="{{ old('purchase_price') }}">
                        </div>

                        <div class="form-group" >
                            <label>@lang('site.sell_price')</label>
                            <input type="number" name="sell_price" class="form-control" value="{{ old('sell_price') }}">
                        </div>

                        <div class="form-group" >
                            <label>@lang('site.stock')</label>
                            <input type="number" name="stock" class="form-control" value="{{ old('stock') }}">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"> @lang('site.add')</i>
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </section>
    </div>

@endsection
