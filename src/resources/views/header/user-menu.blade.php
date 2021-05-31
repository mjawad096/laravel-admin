<li class="dropdown dropdown-user nav-item">
    <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
        <div class="user-nav d-sm-flex d-none">
            <span class="user-name text-bold-600">{{ auth()->user()->name ?? '' }}</span>
            <span class="user-status">{{ auth()->user()->role ?? '' }}</span>
        </div>
        <span>
            <img class="round" src="{{ auth()->user()->avatar_url ?? '' }}" alt="avatar" height="40" width="40">
        </span>
    </a>
    <div class="dropdown-menu dropdown-menu-right">
        @if(app()->router->has('laravel-admin.profile.edit'))
            <a class="dropdown-item" href="{{ route(config('laravel-admin.routes.profile.edit', 'laravel-admin.profile.edit')) }}">
                <i class="feather icon-user"></i> Edit Profile
            </a>                           
            <div class="dropdown-divider"></div>
        @endif

        <a class="dropdown-item" href="" data-logout>
            <i class="feather icon-power"></i> Logout
        </a>
    </div>
</li>