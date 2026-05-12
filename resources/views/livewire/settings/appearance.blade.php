<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component {
    //
}; ?>

<div class="flex flex-col items-start">
    @include('partials.settings-heading')

    <x-settings.layout heading="外觀設定" subheading="調整介面明暗模式">
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
            <flux:radio value="light" icon="sun">淺色</flux:radio>
            <flux:radio value="dark" icon="moon">深色</flux:radio>
            <flux:radio value="system" icon="computer-desktop">跟隨系統</flux:radio>
        </flux:radio.group>
    </x-settings.layout>
</div>
