<div>
    <div class="space-y-4">
        <x-input label="Kode Unit" placeholder="Masukkan kode unit" wire:model="form.code" />
        
        <x-input label="Nama Unit" placeholder="Masukkan nama unit" wire:model="form.name" />
        
        <x-input label="Singkatan" placeholder="Masukkan singkatan unit" wire:model="form.acronym" />
        
        <x-select
            label="Tipe Unit"
            placeholder="Pilih tipe unit"
            wire:model="form.type"
            :options="[
                ['label' => 'OPD', 'value' => 'OPD'],
                ['label' => 'Sub Unit', 'value' => 'SUB'],
            ]"
            option-label="label"
            option-value="value"
        />

        <x-select
            label="Parent Unit"
            placeholder="Pilih unit induk"
            wire:model="form.parent_code"
            :options="$parentUnits->map(fn($unit) => [
                'label' => $unit->name,
                'value' => $unit->code
            ])"
            option-label="label"
            option-value="value"
        />

        <x-textarea label="Deskripsi" placeholder="Masukkan deskripsi unit" wire:model="form.description" />
    </div>

    <div class="mt-4 flex justify-end gap-x-2">
        <x-button flat label="Batal" wire:click="closeModal" />
        <x-button primary label="Simpan" wire:click="save" />
    </div>
</div>