<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {   
        if(request('search_key')){
            $search_param = request()->all();
            
            $data['products'] = Product::whereHas('productVariants', function($q) use ($search_param) {
                if(!empty($search_param['variant'])){
                    $q->where('variant',$search_param['variant']);
                }
                
            })
            ->whereHas('productVariantPrices', function($q) use ($search_param) {
                if(!empty($search_param['price_from']) && !empty($search_param['price_to'])) {
                    $q->whereBetween('price', [$search_param['price_from'], $search_param['price_to']]);
                }
            })
            ->where(function($query) use($search_param){
                if(!empty($search_param['title'])){
                    $query->where('title','LIKE','%'.$search_param['title'].'%');
                }
                if(!empty($search_param['date'])){
                    $query ->whereDate('created_at','=',\Carbon\Carbon::parse($search_param['date'])->format('Y-m-d'));
                }

            })
            ->paginate(4);
        }else{
            $data['products'] = Product::paginate(4);
        }
        $data['varinats'] = Variant::all();
        return view('products.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $data['variants'] = Variant::all();
        $data['product'] =  Product::with('productVariantPrices','productVariantPrices.productVariantOne','productVariantPrices.productVariantTwo','productVariantPrices.productVariantThree')->find($product->id);

        return view('products.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $product->update($request->all());

        if($product){
            $product_variant_prices = count($request->product_variant_prices);
            ProductVariantPrice::where('product_id', $product->id)->delete();
            if($product_variant_prices > 0){
                for ($i=0; $i <$product_variant_prices ; $i++) { 
                    $product_variant = ProductVariantPrice::create( [
                        'product_id' => $product->id,            
                        'price' => $request->product_variant_prices[$i]['price'],         
                        'stock' => $request->product_variant_prices[$i]['stock'],         
                        'product_variant_one' => $request->product_variant_prices[$i]['product_variant_one'],         
                        'product_variant_two' => $request->product_variant_prices[$i]['product_variant_two'],         
                        'product_variant_three' => $request->product_variant_prices[$i]['product_variant_three'],         
                    ]);
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
