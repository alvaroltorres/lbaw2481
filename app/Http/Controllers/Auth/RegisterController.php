<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
class RegisterController extends Controller
{
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
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    /**
     * Show the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }
    /**
     * Handle an incoming registration request.
     *
     * @return \Illuminate\View\View
     */
    public function register(Request $request)
    {
        // Validação dos dados
        $this->validator($request->all())->validate();

        // Criação do usuário
        event(new Registered($user = $this->create($request->all())));

        // Login automático do usuário recém-registrado
        Auth::login($user);

        // Redirecionar para a home page
        return redirect()->route('home');
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => ['required', 'string', 'max:50', 'unique:User,username'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:User,email'],
            'fullname' => ['required', 'string', 'max:100'],
            'nif' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'is_admin' => ['sometimes', 'boolean'],
            'is_enterprise' => ['sometimes', 'boolean'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'fullname' => $data['fullname'],
            'nif' => $data['nif'],
            'password_hash' => Hash::make($data['password']), // Hashing the password
            'is_admin' => false, // Set to false by default unless you change it
            'is_enterprise' => false, // Set to false by default unless you change it
        ]);
    }
}
