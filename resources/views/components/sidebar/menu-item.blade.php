@use('App\Models\Menu', 'Menu')

@props(['title', 'icon' => null, 'image' => null, 'menus' => [], 'route' => null, 'link' => null])

@php
// Resolve url safely: only call route() if the named route exists
if (!empty($route) && \Illuminate\Support\Facades\Route::has($route)) {
    $url = route($route);
} elseif (!empty($link)) {
    $url = $link;
} else {
    $url = '#';
}

if (empty($menus)) {
    $active = !empty($route) ? request()->routeIs($route) : request()->fullUrlIs($link);
} else {
    $active = request()->routeIs(Menu::flatRoutes($menus));
}

// clickable when we have a valid url (named route exists or a provided link)
$clickable = ($url !== '#');

@endphp

<li x-data="{ active: @json($active) }">
    @if (empty($menus))
        @if ($clickable)
            <a
                href="{{ $url }}"
                title="{{ $title }}"
                class="sidebar-link group flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition-all duration-200 hover:bg-white/10 hover:text-white active:scale-95"
                x-bind:class="{
                    'bg-gradient-to-r from-blue-500/20 to-purple-500/20 text-white shadow-lg ring-1 ring-white/20': active,
                    'text-slate-300 hover:text-white': !active
                }"
                x-effect="() => {
                    if(active) {
                        $el.scrollIntoView({ block: 'center' })
                    }
                }"
                x-vision
            >
        @else
            <div
                role="link"
                aria-disabled="true"
                title="{{ $title }} (Coming soon)"
                class="sidebar-link group flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition-all duration-200 cursor-not-allowed opacity-60"
                x-bind:class="{
                    'bg-gradient-to-r from-blue-500/20 to-purple-500/20 text-white shadow-lg ring-1 ring-white/20': active,
                    'text-slate-300': !active
                }"
                x-effect="() => {
                    if(active) {
                        $el.scrollIntoView({ block: 'center' })
                    }
                }"
            >
        @endif

        <!-- Icon/Image Container -->
        <div class="sidebar-icon relative flex-shrink-0">
            @if (@$image)
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10 p-1.5 transition-colors group-hover:bg-white/20"
                     x-bind:class="{ 'bg-gradient-to-br from-blue-400/20 to-purple-400/20': active }">
                    <img src="{{ $image }}" alt="{{ $title }}" class="h-full w-full object-contain filter brightness-0 invert">
                </div>
            @elseif (@$icon)
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10 transition-colors group-hover:bg-white/20"
                     x-bind:class="{ 'bg-gradient-to-br from-blue-400/20 to-purple-400/20': active }">
                    <span class="{{ $icon }} h-5 w-5"></span>
                </div>
            @else
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10 transition-colors group-hover:bg-white/20"
                     x-bind:class="{ 'bg-gradient-to-br from-blue-400/20 to-purple-400/20': active }">
                    <span class="i-mdi-circle h-2 w-2"></span>
                </div>
            @endif

            <!-- Active Indicator -->
            <div class="sidebar-active-indicator absolute -right-1 -top-1 h-2.5 w-2.5 rounded-full bg-blue-400 opacity-0 transition-opacity" x-bind:class="{'opacity-100': active}"></div>
        </div>

        <!-- Menu Text -->
        <span class="sidebar-text flex-1 truncate transition-colors" x-bind:class="{'text-white font-semibold': active}">
            {{ $title }}
        </span>

        <!-- Hover Arrow -->
        <i class="sidebar-arrow i-mdi-chevron-right h-4 w-4 opacity-0 transition-all group-hover:translate-x-1 group-hover:opacity-100" x-bind:class="{'opacity-100 translate-x-1': active}"></i>

        @if ($clickable)
            </a>
        @else
            </div>
        @endif
    @else
        <!-- Submenu Container -->
        <div class="group" x-data="{ open: @json($active) }">
            <!-- Submenu Toggle -->
            <button
                type="button"
                class="sidebar-link flex w-full items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition-all duration-200 hover:bg-white/10 hover:text-white"
                x-bind:class="{ 'bg-white/10 text-white': open || active, 'text-slate-300': !open && !active }"
                x-on:click="
                    if (document.documentElement.classList.contains('sidebar-collapsed')) {
                        window.dispatchEvent(new CustomEvent('sidebar-expand'));
                        open = true;
                        return;
                    }

                    open = !open
                "
                title="{{ $title }}"
                x-bind:aria-expanded="open"
            >
                <!-- Icon Container -->
                <div class="sidebar-icon flex h-8 w-8 items-center justify-center rounded-lg bg-white/10 transition-colors group-hover:bg-white/20" x-bind:class="{ 'bg-gradient-to-br from-blue-400/20 to-purple-400/20': open || active }">
                    @if (@$icon)
                        <span class="{{ $icon }} h-5 w-5"></span>
                    @else
                        <span class="i-mdi-folder h-5 w-5"></span>
                    @endif
                </div>

                <!-- Menu Text -->
                <span class="sidebar-text flex-1 truncate text-left" x-bind:class="{'text-white font-semibold': open || active}">
                    {{ $title }}
                </span>

                <!-- Dropdown Arrow -->
                <i class="sidebar-arrow i-mdi-chevron-down h-4 w-4 transition-transform duration-200" x-bind:class="{'rotate-180': open}"></i>
            </button>

            <!-- Submenu Items -->
            <div class="sidebar-submenu-container overflow-hidden transition-all duration-300" x-bind:style="open ? 'max-height: ' + ($refs.submenu.scrollHeight + 'px') : 'max-height: 0px'">
                <ul class="sidebar-submenu mt-1 space-y-1 pl-11" x-ref="submenu">
                    @foreach ($menus as $menu)
                        <li>
                            <x-sidebar.menu-item
                                :title="$menu['title']"
                                :route="@$menu['route']"
                                :menus="@$menu['items']"
                                :icon="@$menu['icon']"
                                :image="@$menu['image']"
                            />
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
</li>
