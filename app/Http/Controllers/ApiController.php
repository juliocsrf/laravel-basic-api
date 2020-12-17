<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Todo;

class ApiController extends Controller
{
    public function createTodo(Request $request)
    {
        $array = ['error' => ''];

        $rules = [
            'title' => ['required', 'min:3']
        ];
        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            $array['error'] = $validator->messages();
            return $array;
        }

        $title = $request->input('title');

        $todo = new Todo();
        $todo->title = $title;
        $todo->save();

        return $array;
    }

    public function readAllTodos()
    {
        $array = ['error' => ''];

        $todos = Todo::simplePaginate(1);

        $array['list'] = $todos->items();
        $array['current_page'] = $todos->currentPage();


        return $array;
    }

    public function readTodo($id)
    {
        $array = ['error' => ''];

        $todo = Todo::find($id);
        if($todo){
            $array['todo'] = $todo;
        }else{
            $array['error']  = "Tarefa $id não encontrada";
        }

        return $array;
    }

    public function updateTodo($id, Request $request)
    {
        $array = ['error' => ''];

        $rules = [
            'title' => ['min:3'],
            'done' => ['boolean']
        ];
        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            $array['error'] = $validator->messages();
            return $array;
        }
        $title = $request->input('title');
        $done = $request->input('done');

        $todo = Todo::find($id);
        if($todo){
            $todo->title = $title ?: $todo->title;
            $todo->done = $done ?? $todo->done;
            $todo->save();
        } else {
            $array['error'] = "Tarefa $id não encontrada";
        }

        return $array;
    }

    public function deleteTodo($id)
    {
        $array = ['error' => ''];

        $todo = Todo::find($id);
        if($todo) {
            $todo->delete();
        }else{
            $array['error'] = "Tarefa $id não encontrada";
        }
        return $array;
    }
}
