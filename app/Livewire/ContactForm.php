<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Validate;
use Livewire\Component;
use App\Mail\Contact;

class ContactForm extends Component
{
    #[Validate('required|string')]
    public $name;

    #[Validate('required|email')]
    public $email;

    #[Validate('required|string')]
    public $object;

    #[Validate('required|string')]
    public $message;

    public function submit()
    {
        $this->validate();
        Mail::to(env('MAIL_TO_ADDRESS'))->send(new Contact($this->name, $this->email, $this->object, $this->message));
        $this->reset(['name', 'email', 'object', 'message']);
        $this->js('alert(`Your message has been sent successfully.`);');
    }
}
