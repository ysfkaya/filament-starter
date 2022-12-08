<?php

namespace App\Http\Livewire\Auth;

use App\Providers\RouteServiceProvider;
use Livewire\Component;

class EmailVerificationNotification extends Component
{
    /**
     * The status of the email verification notification.
     *
     * @var string
     */
    public $status;

    /**
     * Send a new email verification notification.
     *
     * @return void|\Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $request = request();

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        $request->user()->sendEmailVerificationNotification();

        $this->status = 'verification-link-sent';
    }

    public function render()
    {
        // TODO: Add a view for this component
        return <<<'blade'

        blade;
    }
}
