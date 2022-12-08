<?php

namespace App\Http\Livewire\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class ConfirmablePasswordController extends Component
{
    /**
     * The password of the user.
     *
     * @var string
     */
    public $password;

    /**
     * Confirm the user's password.
     *
     * @return mixed
     */
    public function store()
    {
        $request = request();

        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Show the confirm password view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('auth.confirm-password');
    }
}
