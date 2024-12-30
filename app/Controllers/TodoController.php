<?php

namespace App\Controllers;

use App\Models\Todo;
use Core\Http\Controller;
use Core\Http\Request;

class TodoController extends Controller
{
    public function index(Request $request): string
    {
        $todos = Todo::all();

        $this->use('cookies')->set('name', 'John Doe', '1h');
        return response()->json(['todos' => $todos]);
    }

    public function store(Request $request): string
    {
        $data = $request->body();
        Todo::create(['title' => $data['title']]);
        
        return response()->json(['message' => 'Task created successfully.']);
    }

    public function update(Request $request, int $id): string
    {
        $data = $request->input();
        $todo = Todo::find(1);
        if (!$todo) {
            return response()->status(404)->json(['error' => 'Task not found.']);
        }

        $todo->update(['title' => $data['title'], 'completed' => $data['completed']]);
        return response()->json(['message' => 'Task updated successfully.']);
    }

    public function destroy(Request $request, int $id): string
    {
        $todo = Todo::find($id);
        if (!$todo) {
            return response()->status(404)->json(['error' => 'Task not found.']);
        }

        $todo->delete();
        return response()->json(['message' => 'Task deleted successfully.']);
    }
}
