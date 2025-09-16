<?php

use Flux\Flux;
use Livewire\Volt\Component;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;

new class extends Component {
    public bool $use_custom_slug = false;

    #[Validate('required|url')]
    public string $destination_url = '';

    #[Validate('required_if:use_custom_slug,true|nullable|string|alpha_dash|unique:links,slug')]
    public ?string $slug = null;

    #[Computed]
    public function currentOrganization()
    {
        return Auth::user()->currentOrganization()->first();
    }

    #[Computed]
    public function links()
    {
        return $this->currentOrganization
            ? $this->currentOrganization->links()->latest()->paginate(30)
            : collect();
    }

    public function createLink()
    {
        $this->validate();

        $this->currentOrganization->links()->create([
            'destination_url' => $this->destination_url,
            'slug' => $this->slug,
        ]);

        $this->reset('destination_url', 'slug', 'use_custom_slug');

        Flux::modal('create-link')->close();

        Flux::toast(
            heading: __('Link created'),
            text: __('Your link was successfully created.'),
            variant: 'success'
        );
    }
}; ?>

<x-slot:breadcrumbs>
    <flux:breadcrumbs>
        <flux:breadcrumbs.item>{{ __('Links') }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>
</x-slot:breadcrumbs>

<div class="space-y-8">
    <div class="flex items-end justify-between gap-4">
        <flux:heading size="xl">{{ __('Links') }}</flux:heading>

        <flux:modal.trigger name="create-link">
            <flux:button variant="primary" class="-my-1">
                {{ __('Create Link') }}
            </flux:button>
        </flux:modal.trigger>
    </div>

    <flux:modal name="create-link" class="md:w-96">
        <form wire:submit="createLink">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __('Create Link') }}</flux:heading>
                    <flux:text class="mt-2">{{ __('Paste a long URL below to create a short link for your organization.') }}</flux:text>
                </div>
                 <flux:input
                    wire:model="destination_url"
                    name="destination_url"
                    type="url"
                    :label="__('Destination URL')"
                    :placeholder="__('Paste your long URL here')"
                    required
                />
                <flux:switch
                    wire:model="use_custom_slug"
                    :label="__('Use custom slug?')"
                />
                <flux:input
                    wire:model="slug"
                    name="slug"
                    type="text"
                    :label="__('Custom Slug')"
                    :placeholder="__('e.g. my-link')"
                    :disabled="!$this->use_custom_slug"
                />
                <div class="flex">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary">
                        {{ __('Create Link') }}
                    </flux:button>
                </div>
            </div>
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
                     {{ url('/l/' . ($link->slug ?? $link->hashed_id)) }}
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
