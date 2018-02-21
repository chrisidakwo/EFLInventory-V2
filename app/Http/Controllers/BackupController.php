<?php

namespace App\Http\Controllers;

use App\BackupConfig;
use App\Helpers\GoogleUploadHelper;
use function GuzzleHttp\Psr7\str;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;

class BackupController extends Controller {

    /*public function __construct() {
        $this->middleware("auth");
    }

    public function create(Request $request) {
        $this->validate($request, [
            "email" => "required|string",
            "password"=>"required|string|min:6"
        ]);

        $email = $request["email"];
        $password = $request["password"];
        $folder_name = $request["folder_name"];

        if($folder_name == null || $folder_name == "") {
            // Get organisation name and use as folder name
            $folder_name = \DB::table("app_configs")->get()->first()->org_name;
        }

        $folder_name = str_replace(" ", "-", $folder_name);

        $config = BackupConfig::create([
            "account" => trim($email),
            "password" => password_hash($password, PASSWORD_BCRYPT, ["cost" => PASSWORD_BCRYPT_DEFAULT_COST]),
            "account_key" => "",
            "folder_name" => $folder_name
        ]);

        \Session::flash("success", "Backup account has been created successfully!");
        return back();
    }

    public function show(Request $request) {
        return view("backup.show");
    }

    public function settings(Request $request) {
        $configs = BackupConfig::all();
        return view("backup.settings", compact("configs"));
    }*/
}
