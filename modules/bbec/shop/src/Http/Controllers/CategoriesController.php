<?php

namespace bbec\Shop\Http\Controllers;

use App\Acl;
use App\Http\Controllers\Controller;
use bbec\Shop\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function __construct()
    {
        Acl::init();
        Acl::deny('guest');
        Acl::deny('student');
        Acl::allow('manager');
        Acl::deny('staff');
        Acl::deny('ls_admin');
        Acl::deny('priv_user');
        Acl::deny('slt');
        Acl::allow('admin');
        Acl::allow('super_admin');

        $this->middleware('amacl');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return view('shop::categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('shop::categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->validate([
            'name' => 'required'
        ]);
        Category::create([
            'name' => $name
        ]);

        flash('Success - Category has been created')->success();

        return redirect('/categories');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Category $category
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function edit(Category $category)
    {
        return view('shop::categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Category $category
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(Request $request, Category $category)
    {
        $name = $request->validate([
            'name' => 'required'
        ]);

        $category->update([
            'name' => $name
        ]);

        flash('Success - Category has been updated')->success();

        return redirect('/categories');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $json = ['result' => false];
        if(Category::destroy($id)) {
            $json['result'] = true;
        }
        return $json;
    }
}
