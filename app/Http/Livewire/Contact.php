<?php

namespace App\Http\Livewire;

use App\Enums\FormType;
use App\Models\Admin;
use App\Models\Form;
use App\Notifications\ContactFormNotification;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;

class Contact extends Component
{
    use SEOTools;

    public $subject;

    public $name;

    public $email;

    public $phone;

    public $message;

    public $status;

    public function mount()
    {
        $this->seo()->setTitle('Contact');
    }

    public function store()
    {
        $validated = $this->validate([
            'subject' => 'nullable|string',
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'message' => 'required|string',
        ]);

        $form = Form::create([
            'type' => FormType::CONTACT,
            ...$validated,
        ]);

        Admin::notification(new ContactFormNotification($form));

        $this->reset();

        $this->status = [
            'type' => 'success',
            'message' => 'Your message has been sent successfully.',
        ];
    }

    public function render()
    {
        return view('livewire.contact');
    }
}
