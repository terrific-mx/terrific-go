<?php

use Livewire\Volt\Component;

use App\Models\Link;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

new class extends Component {
    public string $destination_url = '';

    public function rules(): array
    {
        return [
            'destination_url' => ['required', 'url'],
        ];
    }

    public function createLink()
    {
        $this->validate();

        $organization = Auth::user()->currentOrganization()->first();

        Link::create([
            'organization_id' => $organization->id,
            'destination_url' => $this->destination_url,
        ]);
    }
}; ?>

<div>
    //
</div>
