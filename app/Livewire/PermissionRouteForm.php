<?php

namespace App\Livewire;

use App\Models\Permission;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Component;

class PermissionRouteForm extends Component
{
    public Permission $permission;
    /** @var array<string> */
    public array $selectedRoutes = [];
    public array $routes = [];
    public string $search = '';
    public array $routeDescriptions = [];

    public function mount()
    {
        $this->routes = $this->availableRoutes();
        $selected = collect($this->permission->routes)
            ->map(fn ($name) => (string) $name)
            ->toArray();

        $this->routes = array_values(array_unique(array_merge($this->routes, $selected)));
        $this->selectedRoutes = $selected;
        $this->routeDescriptions = config('permission.route_descriptions', []);
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

    public function selectAll(): void
    {
        $this->selectedRoutes = collect($this->routes)
            ->map(fn ($route) => (string) $route)
            ->unique()
            ->values()
            ->all();
    }

    public function deselectAll(): void
    {
        $this->selectedRoutes = [];
    }

    public function descriptionFor(string $route): string
    {
        $description = $this->routeDescriptions[$route] ?? null;

        if ($description) {
            return $description;
        }

        $label = Str::of($route)
            ->replace('.', ' ')
            ->replace('-', ' ')
            ->headline();

        return sprintf('Mengatur akses ke route %s.', $label);
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
