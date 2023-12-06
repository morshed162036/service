<?php

namespace App\Http\Controllers\server;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Service;
use App\Models\EmployeeInfo;
use Illuminate\Support\Facades\File;
use Image;
class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $details = Service::with('category')->get()->all();
        return view('server.service.details.index',compact('details'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::get()->all();
        return view('server.service.details.create')->with(compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name'=>'required',
            'category_id'=>'required',
            ];
        $this->validate($request,$rules);

        $detail = new Service();
        $detail->category_id = $request->category_id;
        $detail->description = $request->description;
        $detail->name = $request->name;
        $detail->price = $request->price;
        if($request->hasFile('image')){
            $image_temp = $request->file('image');
            if($image_temp->isValid()){
                //Get Image Extension
                $extension = $image_temp->getClientOriginalExtension();
                //Generate New Image Name
                $imageName = time().'.'.$extension;
                $imagePath = 'images/service/'.$imageName;
                Image::make($image_temp)->resize(1920,1080)->save($imagePath);
                $detail->image = $imageName;
            }
        }
        // $service = Service::findorFail($request->service_id);
        // $service->status = 'Attach';
        // $service->update();

        $detail->save();
        return redirect(route('service.index'))->with('success','New Service Save Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $service_title = Service::findorFail($id)->get('name')->first();
        //dd($service_title);
        $employees = EmployeeInfo::with('service','user')->where('service_id',$id)->get()->all();
        return view('server.service.details.show')->with(compact('employees','service_title'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $categories = Category::get()->all();
        $detail = Service::with('category')->where('id',$id)->get()->first();
        return view('server.service.details.edit')->with(compact('detail','categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'name'=>'required'
            ];
        $this->validate($request,$rules);

        $detail = Service::findorFail($id);
        if($request->category_id){
            $detail->category_id = $request->category_id;
        }
        $detail->description = $request->description;
        $detail->name = $request->name;
        $detail->price = $request->price;

        if($request->hasFile('image')){
            $exists = 'images/service/'.$detail->image;
            if(File::exists($exists))
            {
                File::delete($exists);
            }
            $image_temp = $request->file('image');
            if($image_temp->isValid()){
                //Get Image Extension
                $extension = $image_temp->getClientOriginalExtension();
                //Generate New Image Name
                $imageName = time().'.'.$extension;
                $imagePath = 'images/service/'.$imageName;
                Image::make($image_temp)->resize(1920,1080)->save($imagePath);
                $detail->image = $imageName;
            }
        }


        $detail->update();
        return redirect(route('service.index'))->with('success','Service Update Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $detail = Service_detail::with('service')->findorFail($id);
        $exists = 'images/service/'.$detail->image;
        if(File::exists($exists))
        {
            File::delete($exists);
        }
        $service = Service::findorFail($detail->service->id);
        $service->status = 'Empty';
        $service->update();

        $detail->delete();
        return redirect(route('service-detail.index'))->with('success','Service Details Delete Successfully!');
    }
}
