<?php

namespace App\Http\Controllers;

use App\Models\MeasurementUnit;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Session;

class MeasurementUnitController extends Controller {
    /**
     * MeasurementUnitController constructor.
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @return Application|Factory|RedirectResponse|View
     */
    public function show(Request $request) {
        if ($request->user()->authorizeRoles(['Manager'])) {
            $units = MeasurementUnit::all()->sortBy('name');

            return view('configs.units', compact('units'));
        }

        abort(401, 'You do not have the required authorization.');

        return redirect()->home();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse {
        if ($request->user()->authorizeRoles(['Manager'])) {
            $this->validate($request, [
                'name' => 'required|unique:measurement_units|max:50',
            ]);

            $unit = MeasurementUnit::create([
                'name' => $request['name'],
            ]);

            if ($unit) {
                Session::flash('success', 'Measurement unit has been saved successfully');

                return redirect()->route('showUnits');
            }
        }

        return redirect()->home();
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function edit(Request $request, $id): RedirectResponse {
        if ($request->user()->authorizeRoles(['Manager'])) {
            $unit = MeasurementUnit::find($id);
            $unit->name = $request['name'];
            if ($unit->save()) {
                Session::flash('success', 'Measurement unit has been updated successfully');

                return redirect()->route('showUnits');
            }
        }

        abort(401, 'You do not have the required authorization');

        return redirect()->home();
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     * @throws Exception
     */
    public function delete(Request $request, $id): RedirectResponse {
        if ($request->user()->authorizeRoles(['Manager'])) {
            /** @var MeasurementUnit $measurementUnit */
            $measurementUnit = MeasurementUnit::query()->where('id', '=', $id)->firstOrFail();
            $name = $measurementUnit->name;
            if ($measurementUnit->delete()) {
                session()->flash('success', Str::title($name) . ' has been deleted successfully');

                return redirect()->route('showUnits');
            }
        }

        abort(401, 'You do not have the required authorization!');

        return redirect()->home();
    }
}
