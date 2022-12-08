<?php

namespace App\Http\Livewire\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class PasswordResetLinkController extends Component
{
    /**
     * The email of the user.
     *
     * @var string
     */
    public $email;

    /**
     * The password reset link status.
     *
     * @var string
     */
    public $status = null;

    /**
     * Handle an incoming password reset link request.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store()
    {
        $this->validate([
            'email' => ['required', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only(['email'])
        );

        $this->status = $status == Password::RESET_LINK_SENT
                    ? __($status)
                    : throw ValidationException::withMessages([
                        'email' => __($status),
                    ]);
    }

    /**
     * Display the password reset link request view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('auth.forgot-password');
    }
}
