<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

#[Layout('components.layouts.authenticated')]
class KamusKompetensiPage extends Component
{
    public $search = '';
    public $competencyData = [];
    public $loading = false;
    public $selectedType = 'fungsional'; // default to fungsional

    protected $queryString = ['search'];

    public function mount()
    {
        $this->fetchCompetencyData();
    }

    public function updatedSearch()
    {
        $this->fetchCompetencyData();
    }

    public function updatedSelectedType()
    {
        $this->fetchCompetencyData();
    }

    private function fetchCompetencyData()
    {
        $this->loading = true;

        $cacheKey = "kamus_kompetensi_{$this->selectedType}_{$this->search}";
        $this->competencyData = Cache::remember($cacheKey, now()->addMinutes(30), function () {
            try {
                $url = $this->selectedType === 'fungsional'
                    ? 'https://api-kesejahteraan.bkn.go.id/sikejab/jft'
                    : 'https://api-kesejahteraan.bkn.go.id/sikejab/jfu';

                $params = [
                    'nama' => $this->search,
                    'offset' => 0
                ];

                $response = Http::timeout(30)->get($url, $params);

                if ($response->successful()) {
                    return $response->json();
                }

                return [];
            } catch (\Exception $e) {
                $this->dispatch('notifications', [
                    'type' => 'danger',
                    'message' => 'Error fetching competency data',
                    'description' => $e->getMessage(),
                ]);
                return [];
            }
        });

        $this->loading = false;
    }

    public function render()
    {
        return view('livewire.pages.kamus-kompetensi-page');
    }
}
