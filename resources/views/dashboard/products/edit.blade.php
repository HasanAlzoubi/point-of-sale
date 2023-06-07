@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.categories')</h1>

            <ol class="breadcrumb">
                <li ><a href="{{asset(route('dashboard.index'))}}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a> </li>
                <li><a href="{{ route('dashboard.products.index') }}"> @lang('site.products')</a></li>
                <li class="active">@lang('site.edit')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header">
                    <h3 class="box-title">@lang('site.edit')</h3>
                </div><!-- end of box header -->

                <div class="box-body">

                    @include('partials._errors')

                    <form action="{{ route('dashboard.products.update', $product->id) }}" method="post" enctype="multipart/form-data">

                        @csrf
                        @method('put')

                        <div class="form-group">
                            <label>@lang('site.categories')</label>
                            <select name="category_id" class="form-control" style="padding: 0px 8px;">
                                <option value="" disabled>@lang('site.all_categories')</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ $category->id == $product->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>


                        @foreach (config('translatable.locales') as $locale)
                            <div class="form-group">
                                <label>@lang('site.' . $locale . '.name')</label>
                                <input type="text" name="{{ $locale }}[name]" class="form-control" value="{{ $product->translate($locale)->name}}">
                            </div>
                        @endforeach

                        @foreach (config('translatable.locales') as $locale)
                            <div class="form-group">
                                <label>@lang('site.' . $locale . '.description')</label>
                                <textarea name="{{ $locale }}[description]" class="form-control ckeditor" >{{ $product->translate($locale)->description}}</textarea>
                            </div>
                        @endforeach

                        <div class="form-group" >
                            <label>@lang('site.image')</label>
                            <input type="file" name="image" id="image" class="form-control">
                        </div>

                        <div class="form-group">
                            <img id="image-preview" src="{{ asset('uploads/product_images/'.$product->image) }}" style="width: 80px" class="thumbnail">
                        </div>

                        <div class="form-group" >
                            <label>@lang('site.purchase_price')</label>
                            <input type="number" name="purchase_price" class="form-control" value="{{$product->purchase_price}}">
                        </div>

                        <div class="form-group" >
                            <label>@lang('site.sell_price')</label>
                            <input type="number" name="sell_price" class="form-control" value="{{ $product->sell_price}}">
                        </div>

                        <div class="form-group" >
                            <label>@lang('site.stock')</label>
                            <input type="number" name="stock" class="form-control" value="{{$product->stock}}">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> @lang('site.edit')</button>
                        </div>

                    </form><!-- end of form -->

                </div> <!-- end of box body -->

            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->

@endsection
