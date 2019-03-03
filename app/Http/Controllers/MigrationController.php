<?php

namespace App\Http\Controllers;

use App\Helpers\ExcelMigrationHelper;
use Illuminate\Http\Request;

class MigrationController extends Controller {

    public function __construct() {
        $this->middleware("auth");
    }

    public function show(Request $request) {
        if($request->user()->authorizeRoles(["Manager"])) {
            return view("configs.migrate");
        } else {
            abort(401, "You do not have the required authorization.");
        }
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function store(Request $request) {
        $file = $request->file("file-record");
        $table = $request->get("record");
        $success =  ExcelMigrationHelper::migrate($table, $file);

        if(isset($success["success"])) {
            \Session::flash("success", $success["success"]);
        } else {
            \Session::flash("error", $success["error"]);
        }
        return back();
    }
}
