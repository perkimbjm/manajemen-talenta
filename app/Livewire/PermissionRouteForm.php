<?php

namespace App\Livewire;

use App\Models\Permission;
use Illuminate\Support\Facades\Route as RouteFacade;
use Livewire\Attributes\Computed;
use Livewire\Component;

class PermissionRouteForm extends Component
{
    public Permission $permission;
    /** @var array<string> */
    public array $selectedRoutes = [];
    public array $routes = [];
    public string $search = '';

    public function mount()
    {
        $this->routes = $this->availableRoutes();
        $selected = collect($this->permission->routes)
            ->map(fn ($name) => (string) $name)
            ->toArray();

        $this->routes = array_values(array_unique(array_merge($this->routes, $selected)));
        $this->selectedRoutes = $selected;
    }

    #[Computed]
    public function filteredRoutes(): array
    {
        if ($this->search === '') {
            return $this->routes;
        }

        $keyword = mb_strtolower($this->search);

        return collect($this->routes)
            ->filter(fn ($route) => str_contains(mb_strtolower($route), $keyword))
            ->values()
            ->all();
    }

    public function submit()
    {
        $routes = collect($this->selectedRoutes)
            ->map(fn ($route) => (string) $route)
            ->filter()
            ->unique()
            ->values();

        $this->permission->update([
            'routes' => $routes->all(),
        ]);

        $this->dispatch('notifications', [
            'type' => 'success',
            'message' => 'Berhasil memperbarui route untuk permission',
        ]);

        $this->dispatch('close-modal');
        $this->dispatch('pg:eventRefresh-PermissionTable');
    }

    public function render()
    {
        return view('livewire.permission-route-form');
    }

    private function availableRoutes(): array
    {
        return collect(RouteFacade::getRoutes())
            ->filter(fn ($route) => $route->getName())
            ->map(fn ($route) => $route->getName())
            ->unique()
            ->sort()
            ->values()
            ->all();
    }
}
