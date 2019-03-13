<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

use App\Task;
use Illuminate\Http\Request;

Route::group(['middleware' => ['web']], function () {
    /**
     * Show Task Dashboard
     */
    Route::get('/', function () {
        return view('tasks', [
            'tasks' => Task::orderBy('created_at', 'asc')->get()
        ]);
    });
    /**
     * Add New Task
     */
    Route::post('/task', function (Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'Due_date' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/')
                ->withInput()
               
                ->withErrors($validator);
        }
        $task = new Task;
        $task->name = $request->name;
        $task->due_date = $request->Due_date;
        $task->task_date = $request->task_date;
        $task->remind_date = $request->reminder_date;
        $task->remind_time = $request->reminder_time;
        $task->save();

        return redirect('/');
    });
    Route::get('edit/{id}', function ($id) {
        return view('tasks', [
            'edit_tasks' => Task::find($id)
        ]);
    });
    Route::get('reminder', function () {         
            echo date("d-m-Y H:i:s");        
    });
    Route::post('/update', function (Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'Due_date' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect('/')
                ->withInput()
               
                ->withErrors($validator);
        }
        $task = Task::findOrFail($request->edit_id);
        $task->name = $request->name;
        $task->due_date = $request->Due_date;
        $task->task_date = $request->task_date;
        $task->remind_date = $request->reminder_date;
        $task->remind_time = $request->reminder_time;
        $task->save();
        return redirect('/');
    });
    /**
     * Delete Task
     */
    Route::delete('/task/{id}', function ($id) {
        Task::findOrFail($id)->delete();
        return redirect('/');
    });
});
