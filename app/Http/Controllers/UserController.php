<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Http\Resources\TasksCollection;
use App\Http\Resources\UserResource;
use App\Task;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function index(){
        $this->middleware('admin');
        $users = User::orderBy('created_at', 'desc')->paginate(5);
        return UserResource::collection($users);
    }

    public function all(){
        $this->middleware('api-auth');
        $users = User::orderBy('created_at', 'desc')->get();
        return UserResource::collection($users);
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
        $this->middleware('admin');
        $edit = true;
        if($request->isMethod('put')){
            $user = User::findOrFail($request->get('id'));
        }else{
            $user = new User;
            $edit = false;
        }
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        if($edit == false) {
            //Tikrinam ar useris egzistuoja
            $check = User::where('email', $request->get('email'))->get();
            if (Count($check) > 0) {
                return response(['error' => 'User already exists'], 500);
            }
        }
        if(!empty($request->get('password'))) {
            $user->password = bcrypt($request->get('password'));
        }
        if($user->save()) {
            return new UserResource($user);
        }else{
            return response(['error' => 'Can\'t add new user (Unknown error)'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     */
    public function show($id)
    {
        $this->middleware('admin');
        $user = User::findOrFail($id);
        return new UserResource($user);
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
     * @param  \App\User  $user
     */
    public function update(Request $request, User $user)
    {
        $this->middleware('admin');
        $user->update($request->toArray());
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id)
    {
        $this->middleware('admin');
        $user = User::findOrFail($id);
        if($user->delete()){
            return new UserResource($user);
        }else return $id;
    }
}
