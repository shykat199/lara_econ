<aside class="app-sidebar sticky" id="sidebar">

    <div class="main-sidebar-header">
        <a href="{{ route('dashboard') }}" class="header-logo">
            <img src="{{ asset('build/assets/images/brand-logos/desktop-logo.png') }}" alt="logo" class="desktop-logo">
            <img src="{{ asset('build/assets/images/brand-logos/toggle-dark.png') }}" alt="logo" class="toggle-dark">
            <img src="{{ asset('build/assets/images/brand-logos/desktop-dark.png') }}" alt="logo" class="desktop-dark">
            <img src="{{ asset('build/assets/images/brand-logos/toggle-logo.png') }}" alt="logo" class="toggle-logo">
        </a>
    </div>

    <div class="main-sidebar" id="sidebar-scroll">
        <nav class="main-menu-container nav nav-pills flex-column sub-open">
            <div class="slide-left" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                </svg>
            </div>

            <ul class="main-menu">
                <li class="slide__category"><span class="category-name">Main</span></li>

                <li class="slide">
                    <a href="{{ route('dashboard') }}" class="side-menu__item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="32" height="32" viewBox="0 0 256 256">
                            <path d="M112,56v48a8,8,0,0,1-8,8H56a8,8,0,0,1-8-8V56a8,8,0,0,1,8-8h48A8,8,0,0,1,112,56Zm88-8H152a8,8,0,0,0-8,8v48a8,8,0,0,0,8,8h48a8,8,0,0,0,8-8V56A8,8,0,0,0,200,48Zm-96,96H56a8,8,0,0,0-8,8v48a8,8,0,0,0,8,8h48a8,8,0,0,0,8-8V152A8,8,0,0,0,104,144Zm96,0H152a8,8,0,0,0-8,8v48a8,8,0,0,0,8,8h48a8,8,0,0,0,8-8V152A8,8,0,0,0,200,144Z" opacity="0.2"></path>

                            <path d="M200,136H152a16,16,0,0,0-16,16v48a16,16,0,0,0,16,16h48a16,16,0,0,0,16-16V152A16,16,0,0,0,200,136Zm0,64H152V152h48v48ZM104,136H56a16,16,0,0,0-16,16v48a16,16,0,0,0,16,16h48a16,16,0,0,0,16-16V152A16,16,0,0,0,104,136Zm0,64H56V152h48v48ZM104,40H56A16,16,0,0,0,40,56v48a16,16,0,0,0,16,16h48a16,16,0,0,0,16-16V56A16,16,0,0,0,104,40Zm0,64H56V56h48v48Zm96-64H152a16,16,0,0,0-16,16v48a16,16,0,0,0,16,16h48a16,16,0,0,0,16-16V56A16,16,0,0,0,200,40Zm0,64H152V56h48v48Z"></path>
                        </svg>
                        <span class="side-menu__label">Dashboard</span>
                    </a>
                </li>

                @can('products.view')
                    <li class="slide">
                        <a href="{{ route('products.index') }}" class="side-menu__item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="32" height="32" viewBox="0 0 256 256">
                                <path d="M223.68,66.15,135.68,18a15.88,15.88,0,0,0-15.36,0l-88,48.17a16,16,0,0,0-8.32,14v95.64a16,16,0,0,0,8.32,14l88,48.17a15.88,15.88,0,0,0,15.36,0l88-48.17a16,16,0,0,0,8.32-14V80.18A16,16,0,0,0,223.68,66.15Z" opacity="0.2"></path>
                                <path d="M228.66,58.63,140.66,10.5a24,24,0,0,0-23.32,0l-88,48.13a24,24,0,0,0-12.5,21v96.79a24,24,0,0,0,12.5,21l88,48.13a24,24,0,0,0,23.32,0l88-48.13a24,24,0,0,0,12.5-21V79.6A24,24,0,0,0,228.66,58.63ZM128,120,40.36,72.61,128,25.4l87.64,47.21Zm8,110.28V143.14l88-47.4v87.14ZM24,105.74l88,47.4v87.14l-88-47.4Z"></path>
                            </svg>
                            <span class="side-menu__label">Products</span>
                        </a>
                    </li>
                @endcan

                @can('categories.view')
                    <li class="slide">
                        <a href="{{ route('categories.index') }}" class="side-menu__item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="32" height="32" viewBox="0 0 256 256">
                                <path d="M112,56v48a8,8,0,0,1-8,8H56a8,8,0,0,1-8-8V56a8,8,0,0,1,8-8h48A8,8,0,0,1,112,56Zm88-8H152a8,8,0,0,0-8,8v48a8,8,0,0,0,8,8h48a8,8,0,0,0,8-8V56A8,8,0,0,0,200,48Zm-96,96H56a8,8,0,0,0-8,8v48a8,8,0,0,0,8,8h48a8,8,0,0,0,8-8V152A8,8,0,0,0,104,144Zm96,0H152a8,8,0,0,0-8,8v48a8,8,0,0,0,8,8h48a8,8,0,0,0,8-8V152A8,8,0,0,0,200,144Z" opacity="0.2"></path>
                                <path d="M200,136H152a16,16,0,0,0-16,16v48a16,16,0,0,0,16,16h48a16,16,0,0,0,16-16V152A16,16,0,0,0,200,136Zm0,64H152V152h48v48ZM104,40H56A16,16,0,0,0,40,56v48a16,16,0,0,0,16,16h48a16,16,0,0,0,16-16V56A16,16,0,0,0,104,40Zm0,64H56V56h48v48Zm96-64H152a16,16,0,0,0-16,16v48a16,16,0,0,0,16,16h48a16,16,0,0,0,16-16V56A16,16,0,0,0,200,40Zm0,64H152V56h48v48Zm-96,32H56a16,16,0,0,0-16,16v48a16,16,0,0,0,16,16h48a16,16,0,0,0,16-16V152A16,16,0,0,0,104,136Zm0,64H56V152h48v48Z"></path>
                            </svg>
                            <span class="side-menu__label">Categories</span>
                        </a>
                    </li>
                @endcan

                @can('orders.view')
                    <li class="slide">
                        <a href="{{ route('orders.index') }}" class="side-menu__item {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="32" height="32" viewBox="0 0 256 256">
                                <path d="M216,80v128a8,8,0,0,1-8,8H48a8,8,0,0,1-8-8V80Z" opacity="0.2"></path>
                                <path d="M216,72H176V56a24,24,0,0,0-24-24H104A24,24,0,0,0,80,56V72H40A16,16,0,0,0,24,88V208a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V88A16,16,0,0,0,216,72ZM96,56a8,8,0,0,1,8-8h48a8,8,0,0,1,8,8V72H96ZM216,88v32H40V88Zm0,120H40V136H96v8a8,8,0,0,0,16,0v-8h32v8a8,8,0,0,0,16,0v-8h56v72Z"></path>
                            </svg>
                            <span class="side-menu__label">Orders</span>
                        </a>
                    </li>
                @endcan

                @can('customers.view')
                    <li class="slide">
                        <a href="{{ route('customers.index') }}" class="side-menu__item {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="32" height="32" viewBox="0 0 256 256">
                                <path d="M236,96a28,28,0,0,0-28-28H48A28,28,0,0,0,20,96v64a28,28,0,0,0,28,28H84l44,36V188h80a28,28,0,0,0,28-28Z" opacity="0.2"></path>
                                <path d="M208,60H48A36,36,0,0,0,12,96v64a36.07,36.07,0,0,0,32,35.77V216a8,8,0,0,0,13,6.25L106,188H208a36,36,0,0,0,36-36V96A36,36,0,0,0,208,60Zm20,92a20,20,0,0,1-20,20H104a8,8,0,0,0-5,1.75L76,193.82V180a8,8,0,0,0-8-8H48a20,20,0,0,1-20-20V96A20,20,0,0,1,48,76H208a20,20,0,0,1,20,20ZM88,128a12,12,0,1,1,12,12A12,12,0,0,1,88,128Zm36,0a12,12,0,1,1,12,12A12,12,0,0,1,124,128Zm36,0a12,12,0,1,1,12,12A12,12,0,0,1,160,128Z"></path>
                            </svg>
                            <span class="side-menu__label">{{ auth()->user()->isEmployee() ? 'My Customers' : 'Customers' }}</span>
                        </a>
                    </li>
                @endcan

                @can('users.view')
                    <li class="slide">
                        <a href="{{ route('users.index') }}" class="side-menu__item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="32" height="32" viewBox="0 0 256 256">
                                <path d="M117.25,157.92a60,60,0,1,0-66.5,0A95.83,95.83,0,0,0,3.53,195.63a8,8,0,1,0,13.4,8.74,80,80,0,0,1,134.14,0,8,8,0,0,0,13.4-8.74A95.83,95.83,0,0,0,117.25,157.92ZM40,108a44,44,0,1,1,44,44A44.05,44.05,0,0,1,40,108Zm210.14,98.7a8,8,0,0,1-11.07-2.33A79.83,79.83,0,0,0,172,168a8,8,0,0,1,0-16,44,44,0,1,0-16.34-84.87,8,8,0,1,1-5.94-14.85,60,60,0,0,1,55.53,105.64,95.83,95.83,0,0,1,47.22,37.71A8,8,0,0,1,250.14,206.7Z" opacity="0.2"></path>
                                <path d="M117.25,157.92a60,60,0,1,0-66.5,0A95.83,95.83,0,0,0,3.53,195.63a8,8,0,1,0,13.4,8.74,80,80,0,0,1,134.14,0,8,8,0,0,0,13.4-8.74A95.83,95.83,0,0,0,117.25,157.92ZM40,108a44,44,0,1,1,44,44A44.05,44.05,0,0,1,40,108Zm210.14,98.7a8,8,0,0,1-11.07-2.33A79.83,79.83,0,0,0,172,168a8,8,0,0,1,0-16,44,44,0,1,0-16.34-84.87,8,8,0,1,1-5.94-14.85,60,60,0,0,1,55.53,105.64,95.83,95.83,0,0,1,47.22,37.71A8,8,0,0,1,250.14,206.7Z"></path>
                            </svg>
                            <span class="side-menu__label">Users</span>
                        </a>
                    </li>
                @endcan

                @can('roles.manage')
                    <li class="slide">
                        <a href="{{ route('roles.index') }}" class="side-menu__item {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="32" height="32" viewBox="0 0 256 256">
                                <path d="M208,40H48A16,16,0,0,0,32,56v58.78c0,89.61,75.82,119.34,91,124.39a7.14,7.14,0,0,0,4,0c15.2-5.05,91-34.78,91-124.39V56A16,16,0,0,0,208,40Z" opacity="0.2"></path>
                                <path d="M223.68,66.15,135.68,18a15.88,15.88,0,0,0-15.36,0l-88,48.17a16,16,0,0,0-8.32,14v95.64a16,16,0,0,0,8.32,14l88,48.17a15.88,15.88,0,0,0,15.36,0l88-48.17a16,16,0,0,0,8.32-14V80.18A16,16,0,0,0,223.68,66.15Z" opacity="0"></path>
                                <path d="M208,40H48A16,16,0,0,0,32,56v58.78c0,89.61,75.82,119.34,91,124.39a7.68,7.68,0,0,0,4,0c15.2-5.05,91-34.78,91-124.39V56A16,16,0,0,0,208,40Zm8,74.78c0,78.34-63.94,104.62-80,110-16.06-5.4-80-31.65-80-110V56l160,0Z"></path>
                            </svg>
                            <span class="side-menu__label">Access Control</span>
                        </a>
                    </li>
                @endcan

            </ul>

            <div class="slide-right" id="slide-right">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
                </svg>
            </div>
        </nav>
    </div>

</aside>
