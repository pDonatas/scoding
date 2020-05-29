<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TaskResource;
use App\Http\Resources\TasksCollection;
use App\Task;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth-api');
    }

    public function index(Request $request){
        $user = User::where('api_token', $request->token)->first();

        $tasks = [];
        if($user->type != 1) {
            $tasks = Task::where('user', $user->id)->orderBy($request->order, 'desc')->paginate(5);
        }else{
            $tasks = Task::orderBy($request->order, 'desc')->paginate(5);
        }

        return TaskResource::collection($tasks);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        $this->middleware(['admin']);
        $task = $request->isMethod('put') ? Task::findOrFail($request->get('id')) : new Task;
        $task->status = $request->input('status');
        $task->task = $request->input('task');
        $task->user = $request->input('user');
        if($task->save()) {
            return new TaskResource($task);
        }else{
            return 'Error';
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     */
    public function show($id)
    {
        $task = Task::findOrFail($id);
        return new TaskResource($task);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     */
    public function update(Request $request, Task $task)
    {
        $this->middleware('auth-api');
        $task->update($request->toArray());
        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        if($task->delete()){
            return new TaskResource($task);
        }else return $id;
    }
}
