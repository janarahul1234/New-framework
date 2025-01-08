<x-layout>
    <!--==================== LOGIN ====================-->
    <section class="login section">
        <div class="form__container container grid">
            <div class="form__img">
                <img src="assets/img/section-img.png" alt="image" />
            </div>

            <div class="form__content">
                <h1 class="form__title">Log in your account</h1>

                <form action="#" autocomplete="off" class="form__body">
                    <div class="form__group">
                        <label for="email" class="form__lable">Email address</label>
                        <input type="email" class="form__input" id="email">
                    </div>

                    <div class="form__group">
                        <label for="password" class="form__lable">Password</label>

                        <div>
                            <input type="password" class="form__input" id="password">
                            <img src="assets/img/eye-icon-dark.svg" alt="icon" id="password-icon" />
                        </div>
                    </div>

                    <button class="button button-fill button-light">
                        <img src="assets/img/key-icon.svg" alt="icon" />
                        <span class="button__text">Log In</span>
                    </button>
                </form>

                <span class="form__subtext">
                    Don't have an account?
                    <a href="register.html" class="form__subtext-link">Sign Up</a>
                </span>
            </div>
        </div>
    </section>
</x-layout>
