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
    <flux:modal.trigger name="create-link">
        <flux:button>
            {{ __('Create Link') }}
        </flux:button>
    </flux:modal.trigger>

    <flux:modal name="create-link" variant="default" dismissible="true">
        <form wire:submit="createLink">
            <flux:input
                wire:model="destination_url"
                name="destination_url"
                type="url"
                :label="__('Destination URL')"
                :placeholder="__('Paste your long URL here')"
                required
            />
            <flux:button type="submit">
                {{ __('Create Link') }}
            </flux:button>
        </form>
    </flux:modal>
</div>
