<?php

namespace App\Http\Controllers\server;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Order;
use App\Models\User;
use Auth;
class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('service','employee','customer','approve')->get()->all();
        return view('server.order.index')->with(compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = Service::get()->all();
        $employees = User::where('type','Employee')->get()->all();
        return view('server.order.create')->with(compact('services','employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'order_date'=>'required',
            'service_id'=>'required',
            'employee_id'=>'required',
            'start_time'=>'required',
            'end_time'=>'required',
            'price'=>'required',
            ];
        $this->validate($request,$rules);
        if(Auth::check())
        {
            $service = Service::findorFail($request->service_id);
            if($service->price == $request->price)
            {
                $order = new Order();
                $order->service_id = $request->service_id;
                $order->customer_id = Auth::user()->id;
                $order->employee_id = $request->employee_id;
                $order->order_date = $request->order_date;
                $order->start_time = $request->start_time;
                $order->end_time = $request->end_time;
                $order->price = $request->price;
                $order->save();
                return redirect()->route('customer.history')->with('success','New Order Placed Successfully!');
            }
            else{
                return redirect()->back()->with('error','Transfer Balance not match the Service Price');
            }
        }
        else
        {
            return redirect()->route('login');
        }



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
