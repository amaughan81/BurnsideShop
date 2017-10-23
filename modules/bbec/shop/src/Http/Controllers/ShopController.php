<?php

namespace bbec\Shop\Http\Controllers;

use App\Acl;
use App\BSG;
use App\Http\Controllers\Controller;
use App\TermDates;
use bbec\Shop\Models\Category;
use bbec\Shop\Models\Merit;
use bbec\Shop\Models\Product;
use Carbon\Carbon;

class ShopController extends Controller
{
    public function __construct()
    {
        Acl::init();
        Acl::allow('guest');
        Acl::allow('student');
        Acl::allow('manager');
        Acl::allow('staff');
        Acl::allow('ls_admin');
        Acl::allow('priv_user');
        Acl::allow('slt');
        Acl::allow('admin');
        Acl::allow('super_admin');

        $this->middleware('amacl');
    }
    
    
    /**
     * @param null $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function browse($slug = null)
    {
        if($slug != null) {
            $category = Category::slug($slug);
            $products = $category->products()->get();
        } else {
            $products = Product::all();
        }
        $categories = Category::all();

        return view('shop::shop.browse', compact('products', 'categories', 'slug'));
    }
    
    
    public function home()
    {
        $products = (new Product())->random();
        return view('shop::shop.home', compact('products'));
    }
}
