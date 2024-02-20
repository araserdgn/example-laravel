<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TodoService;
use App\Http\Resources\TodoResource;
use App\Models\Todo;
use Illuminate\Support\Facades\Validator;
use App\Http\Middleware\CheckAuthStatus;

class TodoController extends Controller
{
    protected $todoService;

    public function __construct(TodoService $todoService) {
        $this->todoService = $todoService;
    }


    //! Scope
    public function index(Request $request)
    {
        $user = $request->user();
        $todos = Todo::UserTodo($user->id)->get();

        return apiResponse('User todos with SCOPE control', 200, $todos);
    }

    public function completedTodo(Request $request)
    {
        $user = $request->user();
        $completedTodos = Todo::UserTodo($user->id)->completedTodo()->get();

        return apiResponse('Completed todos with SCOPE', 200, $completedTodos);
    }

    //!

    public function list(Request $request) {
        return $todos= $this->todoService->getAll(); //!
        return 'Kullanıcı Girişli , o yüzden liste görebilir.';
    }

    public function first_data($id ,Request $request) {
        return $this->todoService->find($id);
    }

    public function relation(Request $request)
    {
        $user = $request->user();
        $todos = $user->todos;

        return TodoResource::collection($todos); //! API resource
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'completed' => 'integer',
        ]);

        // Adı küçük harfe çEVİRİYORUZ
        $todo = new Todo([
            'name' => strtolower($request->name),
            'slug' => $request->slug,
            'completed' => $request->completed,
        ]);

    $user->todos()->save($todo);

    return new TodoResource($todo);
    }

     public function update($id,Request $request) {
        $todo = Todo::findOrFail($id);
        $this->authorize('update', $todo); //! authorization

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'completed' => 'boolean',
        ]);

        $todo->update([
            'name' => $request->input('name'),
            'completed' => $request->input('completed', false),
        ]);

        return new TodoResource($todo);
    }

    public function destroy($id) {
        $this->todoService->destroy($id);
        return apiResponse(__('Todo Silme'),200);
    }




}
