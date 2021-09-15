<li class="navigation-header"><span>Example</span></li>

<li class="nav-item {{ $menu_active == 'example' ? 'active' : ''}}">
    <a href="/example">
        <i class="feather icon-archive"></i>
        <span class="menu-title">Example</span>
    </a>
</li>

<li class="navigation-header"><span>Site settings</span></li>
<li class="nav-item {{ $menu_active == 'settings' ? 'active' : ''}}">
    <a href="{{ route('laravel-admin.setting.edit') }}">
        <i class="feather icon-archive"></i>
        <span class="menu-title">Settings</span>
    </a>
</li>