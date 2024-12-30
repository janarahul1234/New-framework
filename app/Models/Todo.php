<?php

namespace App\Models;

use Core\Model;

class Todo extends Model
{
    protected string $table = 'todos';
    protected array $fillable = ['title', 'completed'];
}
