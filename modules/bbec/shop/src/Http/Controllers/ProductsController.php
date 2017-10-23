<?php

namespace bbec\Shop\Http\Controllers;

use App\Acl;
use App\Http\Controllers\Controller;
use bbec\Shop\Models\Category;
use bbec\Shop\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller 
{
    public function __construct()
    {
        Acl::init();
        Acl::allow('guest', ['show']);
        Acl::allow('student', ['show']);
        Acl::allow('staff', ['show']);
        Acl::allow('ls_admin', ['show']);
        Acl::allow('priv_user', ['show']);
        Acl::allow('slt', ['show']);
        Acl::allow('manager');
        Acl::allow('admin');
        Acl::allow('super_admin');

        $this->middleware('amacl');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $title = 'Products Page';
        $products = Product::all();

        return view('shop::products.index', compact('title','products'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::all();

        return view('shop::products.create', compact('categories'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $product = Product::create($request->all());
        flash('Success - A new product has been created')->success()->important();
        return redirect('/products/'.$product->id.'/edit');
    }

    /**
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('shop::products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->all());
        flash('Success - Product has been updated')->success()->important();
        return redirect('/products/'.$product->id.'/edit');
    }

    /**
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($slug)
    {
        // Get the product
        $products = new Product();

        $product = $products->where('slug',$slug)->firstOrFail();
        $similar = $products->similar($slug, $product->category_id)->random(4);
        //dd($similar);

        return view('shop::products.show', compact('product', 'similar'));
    }

    /**
     * @param $id
     * @return array
     */
    public function destroy($id)
    {
        $json = ['result' => false];
        if(Product::destroy($id)) {
            $json['result'] = true;
        }
        return $json;
    }
}