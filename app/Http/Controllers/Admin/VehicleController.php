<?php

namespace App\Http\Controllers\Admin;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\CentralLogics\Helpers;
use App\Models\DeliveryMan;
use Illuminate\Support\Facades\Validator;
class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $key = explode(' ', $request['search']);
        $vehicles = Vehicle::when(isset($key) ,function ($q) use ($key) {
            foreach ($key as $value) {
                $q->where('type', 'like', "%{$value}%");
            }
        })->latest()->paginate(config('default_pagination'));
        return view('admin-views.vehicle.list', compact('vehicles') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin-views.vehicle.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|max:254|unique:vehicles',
            'extra_charges' => 'required|numeric|between:0,999999999999.99',
            'starting_coverage_area' => 'required|numeric|between:0,999999999999.99',
            'maximum_coverage_area' => 'required|numeric|between:.01,999999999999.99|gt:starting_coverage_area',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $starting_coverage_area= $request->starting_coverage_area;
        $maximum_coverage_area= $request->maximum_coverage_area;

        $temp = Vehicle::where(function ($query) use ($starting_coverage_area) {
            $query->where('starting_coverage_area', '<=', $starting_coverage_area)->where('maximum_coverage_area', '>=', $starting_coverage_area);
        })->orWhere(function ($query) use ($maximum_coverage_area) {
            $query->where('starting_coverage_area', '<=', $maximum_coverage_area)->where('maximum_coverage_area', '>=', $maximum_coverage_area);
        })->orWhere(function ($query) use ($starting_coverage_area,$maximum_coverage_area) {
            $query->where('starting_coverage_area', '>=', $starting_coverage_area)->where('maximum_coverage_area', '<=', $maximum_coverage_area);
        })
        ->first();

        if (isset($temp)) {
            return response()->json(['errors' => [
                ['code' => 'Vehicle_overlaped', 'message' => translate('messages.Coverage_area_overlapped')]
            ]]);
        }


        $vehicle = new Vehicle();
        $vehicle->type = $request->type;
        $vehicle->status = 1;
        $vehicle->extra_charges = $request->extra_charges;
        $vehicle->starting_coverage_area = $request->starting_coverage_area;
        $vehicle->maximum_coverage_area = $request->maximum_coverage_area;
        $vehicle->save();

        return response()->json(['success' => translate('messages.Vehicle_created')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function view(Vehicle $vehicle, Request $request)
    {
        $key = explode(' ', $request['search']);
        $delivery_men = DeliveryMan::when(isset($key),function($query)use($key){
            $query->where(function($query)use($key){
                foreach ($key as $value) {
                    $query->orWhere('f_name', 'like', "%{$value}%")->orWhere('l_name', 'like', "%{$value}%");
                }
            });
        })
        ->with('vehicle')
        ->where('vehicle_id',$vehicle->id)
        ->latest()->paginate(config('default_pagination'));
        return view('admin-views.vehicle.view', compact('vehicle','delivery_men') );
    }



    public function status($id, $status)
    {
        $Vehicle = Vehicle::findOrFail($id);
        $Vehicle->status = $status;
        $Vehicle->save();
        Toastr::success(translate('messages.Vehicle_status_updated'));
        return back();
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function edit(Vehicle $vehicle)
    {
        return view('admin-views.vehicle.edit', compact('vehicle') );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|max:254|unique:vehicles,type,'.$vehicle->id,
            'extra_charges' => 'required|numeric|between:0,999999999999.99',
            'starting_coverage_area' => 'required|numeric|between:0,999999999999.99',
            'maximum_coverage_area' => 'required|numeric|between:.01,999999999999.99|gt:starting_coverage_area',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }
        $starting_coverage_area= $request->starting_coverage_area;
        $maximum_coverage_area= $request->maximum_coverage_area;

        $temp = Vehicle::where('id' ,'!=', $vehicle->id)->where(function ($q) use ($starting_coverage_area,$maximum_coverage_area ){
            $q->where(function ($query) use ($starting_coverage_area) {
                $query->where('starting_coverage_area', '<=', $starting_coverage_area)->where('maximum_coverage_area', '>=', $starting_coverage_area);
            })->orWhere(function ($query) use ($maximum_coverage_area) {
                $query->where('starting_coverage_area', '<=', $maximum_coverage_area)->where('maximum_coverage_area', '>=', $maximum_coverage_area);
            })->orWhere(function ($query) use ($starting_coverage_area, $maximum_coverage_area) {
                $query->where('starting_coverage_area', '>=', $starting_coverage_area)->where('maximum_coverage_area', '<=', $maximum_coverage_area);
            });
        })
        ->first();
        if (isset($temp)) {
            return response()->json(['errors' => [
            ['code' => 'Vehicle_overlaped', 'message' => translate('messages.Coverage_area_overlapped')]
            ]]);
        }

        $vehicle->type = $request->type;
        $vehicle->extra_charges = $request->extra_charges;
        $vehicle->starting_coverage_area = $request->starting_coverage_area;
        $vehicle->maximum_coverage_area = $request->maximum_coverage_area;
        $vehicle->save();

        return response()->json(['success' => translate('messages.Vehicle_updated')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $vehicle = Vehicle::findOrFail($request->vehicle);
        $vehicle->delete();
        Toastr::success(translate('messages.vehicle') . ' ' . translate('messages.removed'));
        return back();
    }
}
