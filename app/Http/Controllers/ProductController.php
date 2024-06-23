<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index() {
        $products = Product::orderBy('created_at','DESC')->get();

        return view('products.list',[
            'products' => $products
        ]);
    }

    public function create() {
        return view('products.create');
    }

    public function store(Request $request) {
        $rules = [
            'title' => 'required|min:5',
        ];

        $validator = Validator::make($request->all(),$rules);

        if ($validator->fails()) {
            return redirect()->route('products.create')->withInput()->withErrors($validator);
        }

        $product = new Product();
        $product->title = $request->title;
        $product->description = $request->description;
        $product->save();


        return redirect()->route('products.index')->with('success','Product added successfully.');
    }

    public function edit($id) {
        $product = Product::findOrFail($id);
        return view('products.edit',[
            'product' => $product
        ]);
    }

    public function update($id, Request $request) {

        $product = Product::findOrFail($id);

        $rules = [
            'title' => 'required|min:5',
        ];

        

        $validator = Validator::make($request->all(),$rules);

        if ($validator->fails()) {
            return redirect()->route('products.edit',$product->id)->withInput()->withErrors($validator);
        }

        $product->title = $request->title;
        $product->description = $request->description;
        $product->save();

              

        return redirect()->route('products.index')->with('success','Product updated successfully.');
    }

    public function destroy($id) {
        $product = Product::findOrFail($id);


       $product->delete();

       return redirect()->route('products.index')->with('success','Product deleted successfully.');
    }
}
