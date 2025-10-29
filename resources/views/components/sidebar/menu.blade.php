@props(['menu' => [], 'user'])

<div class="mb-1">
 @if (!empty($menu['items']) && !@$menu['route'])
  <!-- Section Header -->
  <div class="sidebar-section mb-2 px-4">
   <h6 class="sidebar-section-title mb-2 text-xs font-semibold uppercase tracking-wider text-slate-400">{{ $menu['title'] }}</h6>
   <div class="sidebar-section-divider h-px bg-gradient-to-r from-slate-600 to-transparent"></div>
  </div>
 @endif
 
 <ul class="space-y-1">
  @forelse (@$menu['items'] ?? [] as $item)
   <x-sidebar.menu-item
    :title="$item['title']"
    :link="@$item->link"
    :route="@$item['route']"
    :menus="@$item['items']"
    :image="@$item['image']"
    :icon="@$item['icon']"
   />
  @empty
   <x-sidebar.menu-item
    :title="$menu['title']"
    :link="@$menu->link"
    :route="@$menu['route']"
    :image="@$menu['image']"
    :icon="@$menu['icon']"
   />
  @endforelse
 </ul>
</div>
