<?php

namespace App\Http\Controllers\server;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\User;
use App\Models\Employeeinfo;
use Hash;
use Image;
class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = EmployeeInfo::with('service','user')->get()->all();
        return view('server.employee.index')->with(compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = Service::get()->all();
        return view('server.employee.create')->with(compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name'=>'required',
            'service_id'=>'required',
            ];
        $this->validate($request,$rules);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->email);
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->type = $request->type;
        if($request->hasFile('image')){
            $image_temp = $request->file('image');
            if($image_temp->isValid()){
                //Get Image Extension
                $extension = $image_temp->getClientOriginalExtension();
                //Generate New Image Name
                $imageName = time().'.'.$extension;
                $imagePath = 'images/user/'.$imageName;
                Image::make($image_temp)->resize(600,600)->save($imagePath);
                $user->image = $imageName;
            }
        }
        // $service = Service::findorFail($request->service_id);
        // $service->status = 'Attach';
        // $service->update();

        $user->save();

        $info = new Employeeinfo();
        $info->user_id = $user->id;
        $info->service_id = $request->service_id;
        $info->save();
        return redirect(route('employee.index'))->with('success','New Worker Save Successfully!');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
