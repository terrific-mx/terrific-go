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

    #[Computed]
    public function links()
    {
        return $this->currentOrganization
            ? $this->currentOrganization->links()->latest()->paginate(10)
            : collect();
    }

    public function createLink()
    {
        $this->validate();

        $this->currentOrganization->links()->create([
            'destination_url' => $this->destination_url,
        ]);

        $this->reset('destination_url');
        $this->dispatch('close-modal', name: 'create-link');
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

    <flux:table :paginate="$this->links">
        <flux:table.columns>
            <flux:table.column>{{ __('Short Link') }}</flux:table.column>
            <flux:table.column>{{ __('Destination URL') }}</flux:table.column>
            <flux:table.column>{{ __('Created At') }}</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach ($this->links as $link)
                <flux:table.row>
                    <flux:table.cell>
                        {{ url('/l/' . $link->id) }}
                    </flux:table.cell>
                    <flux:table.cell>
                        {{ $link->destination_url }}
                    </flux:table.cell>
                    <flux:table.cell>
                        {{ $link->created_at->format('Y-m-d H:i') }}
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
