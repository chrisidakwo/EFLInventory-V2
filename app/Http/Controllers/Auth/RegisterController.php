<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

class RegisterController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @return Application|Factory|RedirectResponse|Redirector|View
     */
    public function showRegistrationForm(Request $request) {
        try {
            if ($request->user()->authorizeRoles(['Manager'])) {
                return view('auth.register');
            }
        } catch (Exception $ex) {
            return redirect('/login');
        }

        abort(401, 'This action is unauthorized.');

        return redirect()->home();
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return Validator
     * @throws BindingResolutionException
     */
    protected function validator(array $data): Validator {
        return validator()->make($data, [
            'name' => 'required|string|max:255',
            'username' => 'required|string|username|max:12|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ], ['username' => 'Username already exists! Please try another!']);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     */
    protected function create(array $data): User {
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'password' => bcrypt($data['password']),
            'last_login' => ''
        ]);

        $user->roles()->attach(Role::where('name', 'Employee')->first());

        return $user;
    }
}
