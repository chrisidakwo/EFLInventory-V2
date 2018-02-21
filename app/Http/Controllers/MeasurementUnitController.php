<?php

namespace App\Http\Controllers;

use App\MeasurementUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Session;

class MeasurementUnitController extends Controller
{
    public function __construct() {
        $this->middleware("auth");
    }

    public function show(Request $request) {
        if($request->user()->authorizeRoles(["Manager"])) {
            $units = MeasurementUnit::all()->sortBy("name");
            return view("configs.units", compact("units"));
        }

        abort(401, "You do not have the required authorization.");
    }

    public function store(Request $request) {
        if($request->user()->authorizeRoles(["Manager"])) {
            $this->validate($request, [
                "name" => "required|unique:measurement_units|max:50",
            ]);

            $unit = MeasurementUnit::create([
                "name" => $request["name"],
            ]);

            if ($unit) {
                Session::flash("success", "Measurement unit has been saved successfully");
                return redirect()->route("showUnits");
            }
        }

        redirect()->home();
    }

    public function edit(Request $request, $id) {
        if($request->user()->authorizeRoles(["Manager"])) {
            $unit = MeasurementUnit::find($id);
            $unit->name = $request["name"];
            if($unit->save()) {
                \Session::flash("success", "Measurement unit has been updated successfully");
                return redirect()->route("showUnits");
            }
        }

        abort(401, "You do not have the required authorization");
    }

    public function delete(Request $request, $id) {
        if($request->user()->authorizeRoles(["Manager"])) {
            $name = $request["name"];
            $brand = new MeasurementUnit();
            $brand = $brand->all()->where("id", "=", $id);
            $name = $brand->name;
            if($brand->delete()) {
                \Session::flash("success", Str::title($name) . " has been deleted successfully");
                return redirect()->route("showUnits");
            }
        }

        abort(401, "You do not have the required authorization!");
    }
}
