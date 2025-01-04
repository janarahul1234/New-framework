# New Framework

Welcome to the New Framework! This is a PHP framework designed to be simple, flexible, and easy to use.

## Table of Contents

- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Directory Structure](#directory-structure)
- [Contributing](#contributing)
- [License](#license)

## Installation

1. Clone the repository:
    ```sh
    git clone https://github.com/yourusername/new-framework.git
    ```

2. Navigate to the project directory:
    ```sh
    cd new-framework
    ```

3. Install dependencies using Composer:
    ```sh
    composer install
    ```

4. Copy the `.env.example` file to `.env` and update the environment variables as needed:
    ```sh
    cp .env.example .env
    ```

5. Generate the application key:
    ```sh
    php artisan key:generate
    ```

6. Start the development server:
    ```sh
    php -S localhost:8000 -t public
    ```

## Configuration

The configuration files are located in the `config` directory. Update these files as needed to configure your application.

## Usage

### Routing

Define your routes in the `routes/web.php` file. For example:
```php
use Core\Route;

Route::get('/', function () {
    return view('welcome');
});
```

### Controllers

Create controllers in the `app/Controllers` directory. Extend the `Core\Http\Controller` class to create a new controller.

### Views

Views are stored in the `app/views` directory. Use the Blade templating engine to create your views.

### Middleware

Middleware can be defined and applied to routes to handle requests before they reach the controller.

## Directory Structure

```
new-framework/
├── app/
│   ├── Controllers/
│   ├── views/
│   └── ...
├── config/
│   ├── cors.php
│   ├── database.php
│   └── ...
├── core/
│   ├── Http/
│   ├── packages/
│   ├── Database.php
│   ├── ErrorHandler.php
│   ├── Framework.php
│   ├── functions.php
│   ├── Model.php
│   ├── Route.php
│   ├── Router.php
│   └── View.php
├── public/
│   ├── .htaccess
│   ├── index.php
│   └── ...
├── routes/
│   ├── web.php
│   ├── api.php
│   └── ...
├── storage/
│   ├── logs/
│   └── ...
├── .env
├── .env.example
├── .gitignore
├── composer.json
├── composer.lock
└── README.md
```

## Contributing

Contributions are welcome! Please fork the repository and submit a pull request.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
