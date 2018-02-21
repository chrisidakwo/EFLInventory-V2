<?php

namespace App\Http\Controllers;

use App\ActionHistory;
use App\Role;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Response;
use Session;

class UserController extends Controller {
    public function __construct() {
        $this->middleware("auth");
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request) {
        if($request->user()->authorizeRoles(["Manager"])) {
            $users = User::all();
            return view("configs.users", compact("users"));
        } else {
            abort(401, "You do not have the required authorization.");
        }
    }

    /**
     ** add a new user account
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request) {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:12|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $admin = $request["admin"] ?: false;
        $staff = $request["staff"] ?: false;

        $user = User::create([
            "name" => $request["name"],
            "username" => $request["username"],
            "password" => bcrypt($request["password"]),
            "is_superadmin" => boolval($admin),
            "is_staff" => boolval($staff),
            "last_login" => ""
        ]);

        // Attach roles
        if($user->is_superadmin == 1) {
            $manager_role = Role::where("name", "Manager")->first();
            $user->roles()->attach($manager_role);
        } else {
            $employee_role = Role::where("name", "Employee")->first();
            $user->roles()->attach($employee_role);
        }


        // Add action history
        $description = "Added new user account for {$user->name}";
        ActionHistory::AddNewActionHistory($description);

        Session::flash("success", "New user account created for {$user->name}");
        return back();
    }

    /**
     * @param Request $request
     * @param $username
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(Request $request, $username) {
        $user = User::all()->where("username", $username)->first();
        return Response::json(["user" => $user]);
    }

    /**
     ** update an existing user account
     *
     * @param Request $request
     * @param $username
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $username) {
        $name = $request["name"];
        $user_name = $request["username"];
        $password = $request["password"] ?: "";
        $admin = $request["admin"] ?: false;
        $staff = $request["staff"] ?: false;

        $user = User::where("username", $username)->first();

        // Get ready for action history
        $description = "Updated account details:";
        $update_list = "";
        $update_list .= ($name != $user->name) ? " name," : "";
        $update_list .= ($user_name != $username) ? " username," : "";
        $update_list .= ((int)$admin != $user->is_superadmin || (int)$staff != $user->is_staff) ? " status," : "";
        $update_list .= ($password != "") ? " password" : "";
        $update_list = trim($update_list, ",");

        $description .= "{$update_list} for {$user->name}";

        $user->username = trim($user_name);
        $user->name = $name;
        $user->password = ($password == "") ? $user->password : bcrypt($password);
        $user->is_superadmin = boolval($admin);
        $user->is_staff = boolval($staff);
        $user->save();

        // Add action history
        ActionHistory::AddNewActionHistory($description);

        if($user->is_superadmin) {
            // Update role_user table
            $manager_role = Role::where("name", "Manager")->first();

            \DB::table("role_user")
                ->where("user_id", $user->id)->update([
                    "role_id" => $manager_role->id
                ]);
        } else {
            if($user->hasRole("Manager")) {
                // Update role_user table
                $employee_role = Role::where("name", "Employee")->first();

                \DB::table("role_user")
                    ->where("user_id", $user->id)->update([
                        "role_id" => $employee_role->id
                    ]);
            }
        }

        Session::flash("success", "User details have been updated successfully");
        return back();
    }

    /**
     ** delete a user
     *
     * @param Request $request
     * @param $username
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request, $username) {
        $user = User::where("username", $username)->first();
        \DB::table("role_user")->where("user_id", $user->id)->delete();
        $user->delete();

        // Add action history
        $description = "Deleted user account for {$user->name}";
        ActionHistory::AddNewActionHistory($description);

        Session::flash("success", "{$user->name} has been deleted from your list of users");
        return back();
    }
}
