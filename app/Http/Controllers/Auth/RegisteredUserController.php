<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Permission;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'cpf' => ['required', 'string', 'cpf', 'max:14', 'unique:users'],
            'rg' => ['required', 'string'],
            'telefone' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'cpf'      => $request->cpf,
            'rg'       => $request->rg,
            'telefone' => $request->telefone,
            'codpes'   => User::gerarCodigoPessoaExterna(),
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        /* Monta as permissões do usuário */
        $permissions[] = Permission::where('guard_name', 'senhaunica')->where('name', 'user')->first();
        $permissions[] = Permission::where('guard_name', 'senhaunica')->where('name', 'Outros')->first();
       
        $user->syncPermissions($permissions);

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
