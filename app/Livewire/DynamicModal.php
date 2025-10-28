<?php

namespace App\Livewire;

use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Reflector;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Mechanisms\ComponentRegistry;
use ReflectionClass;

class DynamicModal extends Component
{
  public bool $show = false;
  public bool $loading = false;
  public ?string $activeComponent = null;

  public array $components = [];

  public function showModal(?array $component = null)
  {
    if ($component) {
      $this->components[] = $this->resolve($component);
    }

    $this->show = true;
    $this->loading = false;
  }

  public function resolve(array $component)
  {
    if (!isset($component['arguments'])) {
      return $component;
    }

    $componentClass = app(ComponentRegistry::class)->getClass($component['name']);
    // $reflect = new ReflectionClass($componentClass);

    $arguments = collect($component['arguments'])
      ->merge($this->resolveComponentProps($component['arguments'], new $componentClass()))
      ->all();

    $component['arguments']  = $arguments;

    return $component;
  }

  public function resolveComponentProps(array $attributes, Component $component): Collection
  {
    return $this->getPublicPropertyTypes($component)
      ->intersectByKeys($attributes)
      ->map(function ($className, $propName) use ($attributes) {
        $resolved = $this->resolveParameter($attributes, $propName, $className);

        return $resolved;
      });
  }

  public function getPublicPropertyTypes($component): Collection
  {
    return collect($component->all())
      ->map(function ($value, $name) use ($component) {
        return Reflector::getParameterClassName(new \ReflectionProperty($component, $name));
      })
      ->filter();
  }

  protected function resolveParameter($attributes, $parameterName, $parameterClassName)
  {
    $parameterValue = $attributes[$parameterName];

    if ($parameterValue instanceof UrlRoutable) {
      return $parameterValue;
    }

    if (enum_exists($parameterClassName)) {
      $enum = $parameterClassName::tryFrom($parameterValue);

      if ($enum !== null) {
        return $enum;
      }
    }

    $instance = app()->make($parameterClassName);

    if (! $model = $instance->resolveRouteBinding($parameterValue)) {
      throw (new ModelNotFoundException())->setModel(get_class($instance), [$parameterValue]);
    }

    return $model;
  }

  public function closeModal(?string $componentId = null)
  {
    if (!$componentId) return;

    $this->show = false;
    $this->loading = false;
    $this->components = array_filter($this->components, fn($component) => $component['id'] !== $componentId);
    $this->dispatch('modal-removed', componentId: $componentId);
  }

  public function placeholder()
  {
    return <<<'HTML'
      <div></div>
      HTML;
  }

  public function render()
  {
    return view('livewire.dynamic-modal');
  }
}
