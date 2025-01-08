<x-layout>
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
                            <img src="@asset('assets/img/add-icon-2.svg')" alt="icon" class="btn__icon" />
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
</x-layout>
