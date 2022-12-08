<?php

namespace App\Http\Livewire\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Livewire\Component;

class EmailVerificationPromptController extends Component
{
    public function mount(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }
    }

    /**
     * Display the email verification prompt.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('auth.verify-email');
    }
}
