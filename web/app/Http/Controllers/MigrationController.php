<?php

namespace App\Http\Controllers;

use App\Helpers\ExcelMigrationHelper;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Exception;
use Session;

class MigrationController extends Controller {
    /**
     * MigrationController constructor.
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
            return view('configs.migrate');
        }

        abort(401, 'You do not have the required authorization.');

        return redirect()->home();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function store(Request $request): RedirectResponse {
        $file = $request->file('file-record');
        $table = $request->get('record');
        $success = ExcelMigrationHelper::migrate($table, $file);

        if (isset($success['success'])) {
            Session::flash('success', $success['success']);
        } else {
            Session::flash('error', $success['error']);
        }

        return redirect()->back();
    }
}
