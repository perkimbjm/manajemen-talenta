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
        $this->name = $data['name'] ?? '';
        $this->apiType = $data['apiType'] ?? 'Fungsional';
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
        if (empty($this->name)) {
            return;
        }

        $this->loading = true;

        $cacheKey = "kamus_kompetensi_modal_{$this->apiType}_{$this->name}";
        $this->competencyData = Cache::remember($cacheKey, now()->addMinutes(30), function () {
            try {
                $url = $this->apiType === 'jft'
                    ? 'https://api-kesejahteraan.bkn.go.id/sikejab/jft'
                    : 'https://api-kesejahteraan.bkn.go.id/sikejab/jfu';

                // Try exact match first
                // URL encode the position name to handle spaces and special characters
                $encodedName = urlencode($this->name);
                $fullUrl = $url . '?nama=' . $encodedName . '&offset=0';

                // Log the URL being called
                Log::info('Kamus Kompetensi API Call', [
                    'url' => $fullUrl,
                    'position_name' => $this->name,
                    'api_type' => $this->apiType
                ]);

                $response = Http::timeout(30)->get($url, [
                    'nama' => $encodedName,
                    'offset' => 0
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $results = $data['data'] ?? [];

                    // If no exact match, try partial search with first word
                    if (empty($results)) {
                        $words = explode(' ', $this->name);
                        $searchTerm = urlencode($words[0]); // URL encode first word

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
