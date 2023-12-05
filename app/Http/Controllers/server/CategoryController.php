<?php

namespace App\Http\Controllers\server;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::get();
        return view('server.category.index')->with(compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('server.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'category_name'=>'required',
            ];
        $this->validate($request,$rules);
        $catalogue = new Category();
        $catalogue->name = $request->category_name;
        $catalogue->save();
        return redirect(route('category.index'))->with('success','Category Create Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::where('id',$id)->get()->first();

        return view('server.category.edit')->with(compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'category_name'=>'required',
            ];
        $this->validate($request,$rules);
        $catalogue = Category::findorFail($id);
        $catalogue->name = $request->category_name;
        $catalogue->update();
        return redirect(route('category.index'))->with('success','Category Update Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Category::findorFail($id)->delete();
        return redirect(route('category.index'))->with('success','Category Delete Successfully!');
    }
}
