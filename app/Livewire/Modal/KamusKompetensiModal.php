<?php

namespace App\Livewire\Modal;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class KamusKompetensiModal extends Component
{
    public $name = '';
    public $apiType = 'jft'; // jft or jfu
    public $competencyData = [];
    public $loading = false;
    public $showModal = false;

    public function mount()
    {
        // Initialize modal as hidden
        $this->showModal = false;
    }

    #[On('show-kamus-kompetensi')]
    public function showModal(array $data = [])
    {
        $this->name = trim($data['name'] ?? $data['positionName'] ?? '');
        $this->apiType = $data['apiType'] ?? 'jft';
        $this->showModal = true;
        $this->fetchCompetencyData();
    }

    #[On('closeModal')]
    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['name', 'competencyData', 'loading']);
    }

    private function fetchCompetencyData()
    {
        $this->name = trim($this->name);

        if ($this->name === '') {
            return;
        }

        $this->loading = true;

        $cacheKey = sprintf(
            'kamus_kompetensi_modal_%s_%s',
            $this->apiType,
            md5($this->name)
        );
        $this->competencyData = Cache::remember($cacheKey, now()->addMinutes(30), function () {
            try {
                $url = $this->apiType === 'jft'
                    ? 'https://api-kesejahteraan.bkn.go.id/sikejab/jft'
                    : 'https://api-kesejahteraan.bkn.go.id/sikejab/jfu';

                $fullUrl = $url . '?nama=' . urlencode($this->name) . '&offset=0';

                // Log the URL being called on the server for reference
                Log::info('Kamus Kompetensi API Call', [
                    'url' => $fullUrl,
                    'position_name' => $this->name,
                    'api_type' => $this->apiType,
                ]);

                // Dispatch browser event so developers can inspect the URL via console
                $this->dispatch('kamus-kompetensi-url', [
                    'url' => $fullUrl,
                    'position_name' => $this->name,
                    'api_type' => $this->apiType,
                ]);

                $response = Http::timeout(30)->get($url, [
                    'nama' => $this->name,
                    'offset' => 0
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $results = $data['data'] ?? [];

                    // If no exact match, try partial search with first word
                    if (empty($results)) {
                        $words = explode(' ', $this->name);
                        $searchTerm = $words[0];

                        $response = Http::timeout(30)->get($url, [
                            'nama' => $searchTerm,
                            'offset' => 0
                        ]);

                        if ($response->successful()) {
                            $data = $response->json();
                            $results = $data['data'] ?? [];
                        }
                    }

                    return $results;
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
        return view('livewire.modal.kamus-kompetensi-modal');
    }
}
