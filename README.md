# New Framework Documentation

## Introduction

Welcome to the New Framework! This framework is designed to be lightweight, flexible, and easy to use. It provides a solid foundation for building web applications with PHP.

## Requirements

- PHP 8.0 or higher
- Composer
- MySQL or any other supported database

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

4. Copy the `.env.example` file to `.env` and configure your environment variables:
    ```sh
    cp .env.example .env
    ```
5. Start the development server:
    ```sh
    php -S localhost:8000 -t public
    ```

## Directory Structure

- `app/`: Contains the application code (controllers, models, views).
- `config/`: Configuration files.
- `core/`: Core framework files.
- `public/`: Publicly accessible files (index.php, assets).
- `routes/`: Route definitions.
- `storage/`: Logs, cache, and other storage files.
- `vendor/`: Composer dependencies.

## Basic Usage

### Routing

Define your routes in the `routes/web.php` file. Here is an example:

```php
use Core\Route;

Route::get('/', [App\Controllers\HomeController::class, 'index']);
Route::post('/todos', [App\Controllers\TodoController::class, 'store']);
```

### Controllers

Create controllers in the `app/Controllers` directory. Here is an example of a `TodoController`:

```php
namespace App\Controllers;

use Core\Http\Request;
use Core\Http\Response;
use App\Models\Todo;

class TodoController
{
    public function index(Request $request): Response
    {
        $todos = Todo::all();
        return view('todos', ['todos' => $todos]);
    }

    public function store(Request $request): Response
    {
        $data = $request->body();
        Todo::create($data);
        return redirect('/todos');
    }
}
```

### Models

Create models in the `app/Models` directory. Here is an example of a `Todo` model:

```php
namespace App\Models;

use Core\Model;

class Todo extends Model
{
    protected string $table = 'todos';
    protected array $fillable = ['title', 'completed'];
}
```

### Views

Create views in the `app/views` directory. Here is an example of a `todos.twig` view:

```twig
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todos</title>
</head>
<body>
    <h1>Todos</h1>
    <ul>
        {% for todo in todos %}
            <li>{{ todo.title }} - {{ todo.completed ? 'Completed' : 'Pending' }}</li>
        {% endfor %}
    </ul>
</body>
</html>
```

### Environment Configuration

Configure your environment variables in the `.env` file. Here is an example:

```
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=new_framework
DB_USERNAME=root
DB_PASSWORD=
```

## Error Handling

The framework provides a custom error handler that captures and logs errors. You can find the error handler in `core/ErrorHandler.php`.

## Middleware

You can add middleware to your routes by using the `middleware` method. Here is an example:

```php
Route::get('/admin', [App\Controllers\AdminController::class, 'index'])->middleware('auth');
```

## Conclusion

This documentation provides a basic introduction to the New Framework. For more detailed information, please refer to the source code and comments within the framework. Happy coding!