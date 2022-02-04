<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\DeliveryBoy;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveryBoyController extends Controller
{
    public function add_new()
    {
        return view('admin-views.delivery-boy.add-new');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'unique:delivery_boys',
            'commission' => 'required',
            'phone' => 'required|unique:delivery_boys',
            'address' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'password' => 'required',
            'image' => 'required',
        ], [
            'name.required' => 'Name is required!',
            'image.required' => 'Image is Required',
            'commission.required' => 'Delivery charge is Required',
            'phone.required' => 'Phone is Required',
            'address.required' => 'Address is Required',
            'address.required' => 'Address is Required',
            'lat.required' => 'Lattitude is Required',
            'lng.required' => 'Longitude is Required',
        ]);

        DB::table('delivery_boys')->insert([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'sales_commission_percentage' => $request->commission,
            'address' => $request->address,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'vehicle' => $request->vehicle,
            'vehicle_name' => $request->vehicle_name,
            'rc_no' => $request->rc_no,
            'insurance_no' => $request->insurance_no,
            'bank_name' => $request->bank_name,
            'branch' => $request->branch,
            'holder_name' => $request->holder_name,
            'account_no' => $request->account_no,
            'status' => 'approved',
            'password' => md5($request->password),
            'image' => ImageManager::upload('admin/', 'png', $request->file('image')),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Toastr::success('Delivery boy added successfully!');
        return redirect()->route('admin.delivery-boy.list');
    }

    function list()
    {
        $em = DeliveryBoy::paginate(Helpers::pagination_limit());
        
        return view('admin-views.delivery-boy.list', compact('em'));
    }

    public function edit($id)
    {
        $e = DeliveryBoy::where(['id' => $id])->first();
        return view('admin-views.delivery-boy.edit', compact('e'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'commission' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ], [
            'name.required' => 'Name is required!',
            'commission.required' => 'Delivery charge is Required',
            'phone.required' => 'Phone is Required',
            'address.required' => 'Address is Required',
            'lat.required' => 'Lattitude is Required',
            'lng.required' => 'Longitude is Required',
        ]);

        $e = DeliveryBoy::find($id);

        if ($request['password'] == null) {
            $pass = $e['password'];
        } else {
            $pass = md5($request['password']);
        }

        if ($request->has('image')) {
            $e['image'] = ImageManager::update('admin/', $e['image'], 'png', $request->file('image'));
        }

        DB::table('delivery_boys')->where(['id' => $id])->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'sales_commission_percentage' => $request->commission,
            'address' => $request->address,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'password' => $pass,
            'vehicle' => $request->vehicle,
            'vehicle_name' => $request->vehicle_name,
            'rc_no' => $request->rc_no,
            'insurance_no' => $request->insurance_no,
            'bank_name' => $request->bank_name,
            'branch' => $request->branch,
            'holder_name' => $request->holder_name,
            'account_no' => $request->account_no,
            'image' => $e['image'],
            'updated_at' => now(),
        ]);

        Toastr::success('Delivery boy updated successfully!');
        return back();
    }
}
