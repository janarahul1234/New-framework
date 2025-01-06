<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!--==================== CSS ====================-->
    <link rel="stylesheet" href="@asset('assets/css/styles.css')">

    <!--==================== TITLE ====================-->
    <title>Coffee Shop</title>
</head>

<body>
    <!--==================== HEADER ====================-->
    <header class="header" id="header">
        <nav class="nav container">
            <a href="/" class="nav__logo">
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

                    <a href="login.html" class="nav__login">Log in</a>

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

    <!--==================== MAIN ====================-->
    <main class="main">
        <!--==================== HOME ====================-->
        <section class="home section" id="home">
            <div class="home__container container grid">
                <img src="@asset('assets/img/coffee-beans-cup.jpg')" alt="image" class="home__img" />

                <div class="home__content">
                    <h1 class="home__title">
                        <span>
                            A Hot Cup
                            <img src="@asset('assets/img/splash-icon.svg')" alt="image" class="home__title-icon" />
                        </span>
                        <br />
                        of Happiness.
                    </h1>

                    <p class="home__description">
                        What on earth could be more luxurious than a sofa,
                        a book, and a cup of coffee?
                    </p>
                </div>
            </div>
        </section>

        <!--==================== PRODUCTS ====================-->
        <section class="products section" id="products">
            <div class="products__container container grid">
                <form action="#" class="products__form">
                    <label for="category" class="form__label">Select you category</label>

                    <div>
                        <div class="form__group">
                            <img src="@asset('assets/img/arrow-icon.svg')" alt="icon" class="form__select-icon" />

                            <select name="category" id="category" class="select">
                                <option value="0">All products</option>
                                <option value="1">Hot coffee</option>
                                <option value="2">Cold coffee</option>
                            </select>
                        </div>

                        <button class="btn btn--dark">
                            <span class="btn__text">Select</span>
                        </button>
                    </div>
                </form>

                <div class="products__card-container grid">
                    <article class="probucts__card">
                        <img src="@asset('assets/img/product-img.jpg')" alt="image" class="products__img" />
                        <span class="products__category">Hot coffee</span>

                        <div class="products__data">
                            <h2 class="products__name">Milk Coffee</h2>

                            <div>
                                <span class="price__big">₹<span>100</span></span>
                                <span class="price__small">150</span>
                            </div>
                        </div>

                        <div class="products__buttons">
                            <button class="btn btn--dark">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="btn__icon">
                                    <path d="M7 12.1818L17.3636 12.1818M12.1818 7L12.1818 17.3636L12.1818 7Z"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span class="btn__text">Add</span>
                            </button>

                            <button class="btn btn--light">
                                <img src="@asset('assets/img/coffee-icon-2.svg')" alt="icon" class="btn__icon" />
                                <span class="btn__text">Order now</span>
                            </button>
                        </div>
                    </article>
                </div>
            </div>
        </section>
    </main>

    <!--==================== FOOTER ====================-->
    <footer class="footer">
        <div class="footer__container container">
            <span class="footer__copy">
                &#169; All Rights Reserved By
                <a href="#" class="footer__copy-link">Rahul Jana</a>.
            </span>
        </div>
    </footer>
</body>

</html>
