@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>


    <div class="card">
        <form action="" method="get" class="card-header">
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="hidden" name="search_key" value="1" class="form-control">
                    <input type="text" value="{{@request('title')}}" name="title" placeholder="Product Title" class="form-control">
                </div>
                <div class="col-md-2">
                    <select name="variant" id="" class="form-control">
                        @foreach($varinats as $variant)
                        <option value="">Select Variant</option>
                            <optgroup label="{{$variant->title}}">
                                @foreach($variant->productVariants as $productVariant)
                                @php
                                    if($productVariant->variant == @request('variant')){
                                        $selected = 'selected';
                                    }else{
                                        $selected = '';
                                    }
                                @endphp
                                    <option value="{{$productVariant->variant}}" {{$selected}}>{{$productVariant->variant}}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input type="text" value="{{@request('price_from')}}" name="price_from" aria-label="First name" placeholder="From" class="form-control">
                        <input type="text" value="{{@request('price_to')}}" name="price_to" aria-label="Last name" placeholder="To" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" value="{{@request('date')}}" name="date" placeholder="Date" class="form-control">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="table-response">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th width="250px">Description</th>
                        <th>Variant</th>
                        <th width="150px">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($products as $key=>$product)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$product->title}} <br> Created at : {{$product->created_at->diffForHumans() }}</td>
                        <td>{{Str::limit($product->description,150)}}</td>
                        <td>
                            
                            <dl class="row mb-0" style="height: 80px; overflow: hidden" id="variant_{{$key}}">
                                @foreach($product->productVariantPrices as $productVariantPrice)
                                <dt class="col-sm-3 pb-0"> 
                                    {{@$productVariantPrice->productVariantOne->variant}}/ {{@$productVariantPrice->productVariantTwo->variant}}/ {{@$productVariantPrice->productVariantThree->variant}}
                                </dt>
                                <dd class="col-sm-9">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-4 pb-0">Price : {{ number_format($productVariantPrice->price,2) }}</dt>
                                        <dd class="col-sm-8 pb-0">InStock : {{ number_format($productVariantPrice->stock,2) }}</dd>
                                    </dl>
                                </dd>
                                @endforeach
                            </dl>
                            
                            <button onclick="$('#variant_{{$key}}').toggleClass('h-auto')" class="btn btn-sm btn-link">Show more</button>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('product.edit', $product->id) }}" class="btn btn-success">Edit</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    </tbody>

                </table>
            </div>

        </div>

        <div class="card-footer">
            <div class="row justify-content-between">
                <div class="col-md-6">
                    <p>Showing {{($products->currentpage()-1)*$products->perpage()+1}} to {{$products->currentpage()*$products->perpage()}}
                        out of  {{$products->total()}}</p>
                </div>
                <div class="col-md-6">
                    <div class="float-right">{{$products->links()}}</div> 
                </div>
            </div>
        </div>
    </div>

@endsection
