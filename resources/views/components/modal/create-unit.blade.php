<x-modal title="Tambah Unit Kerja Baru" wire:model.live="showCreateModal">
    <div class="space-y-4">
        <x-input label="Kode Unit" placeholder="Masukkan kode unit" wire:model="form.code" />
        
        <x-input label="Nama Unit" placeholder="Masukkan nama unit" wire:model="form.name" />
        
        <x-input label="Singkatan" placeholder="Masukkan singkatan unit" wire:model="form.acronym" />
        
        <x-select
            label="Tipe Unit"
            placeholder="Pilih tipe unit"
            wire:model="form.type"
            :options="[
                ['name' => 'OPD', 'value' => 'OPD'],
                ['name' => 'Sub Unit', 'value' => 'SUB'],
            ]"
        />

        <x-select
            label="Parent Unit"
            placeholder="Pilih unit induk"
            wire:model="form.parent_code"
            :options="$parentUnits ?? []"
            option-label="name"
            option-value="code"
        />

        <x-textarea label="Deskripsi" placeholder="Masukkan deskripsi unit" wire:model="form.description" />
    </div>

    <x-slot name="footer">
        <div class="flex justify-end gap-x-2">
            <x-button flat label="Batal" x-on:click="close" />
            <x-button primary label="Simpan" wire:click="save" />
        </div>
    </x-slot>
</x-modal>