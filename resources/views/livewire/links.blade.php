<?php

use Livewire\Volt\Component;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;

new class extends Component {
    #[Validate('required|url')]
    public string $destination_url = '';

    #[Computed]
    public function currentOrganization()
    {
        return Auth::user()->currentOrganization()->first();
    }

    public function createLink()
    {
        $this->validate();

        $this->currentOrganization->links()->create([
            'destination_url' => $this->destination_url,
        ]);
    }
}; ?>

<div>
    //
</div>
