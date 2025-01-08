<header class="header" id="header">
    <nav class="nav container">
        <a href="@route('pages.home')" class="nav__logo">
            <img src="@asset('/assets/img/logo.svg')" alt="logo" />
            <span>Shop</span>
        </a>

        <div class="nav__menu" id="nav-menu">
            <ul class="nav__list">
                <li>
                    <a href="#home" class="nav__link active-link">Home</a>
                </li>

                <li>
                    <a href="#about" class="nav__link">About</a>
                </li>

                <li>
                    <a href="#products" class="nav__link">Products</a>
                </li>
            </ul>

            <div>
                {{-- <details class="nav__dropdown">
                    <summary class="dropdown__icons">
                        <img src="@asset('assets/img/profile-icon.svg')" alt="icon" />
                        <img src="@asset('assets/img/arrow-icon.svg')" alt="icon" />
                    </summary>

                    <div class="dropdown__container">
                        <h2 class="dropdown__data">
                            <span>Welcome to,</span><br />
                            <span>John Doe</span>
                        </h2>

                        <a href="#" class="btn btn--outline">
                            <img src="@asset('assets/img/arrow-icon-red.svg')" alt="icon" />
                            <span class="btn__text">Log out</span>
                        </a>
                    </div>
                </details> --}}

                <a href="@route('pages.login')" class="nav__login">Log in</a>

                <a href="order.html" class="btn btn--outline" id="order-button">
                    <img src="@asset('assets/img/coffee-icon-1.svg')" alt="icon" class="btn__icon" />
                    <span class="btn__text">Order</span>
                    <span class="btn__number">25</span>
                </a>
            </div>
        </div>

        <!-- Toggle button -->
        <div class="nav__toggle" id="nav-toggle">
            <img src="@asset('assets/img/menu-icon.svg')" alt="icon" />
        </div>
    </nav>
</header>
