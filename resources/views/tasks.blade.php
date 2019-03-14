@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="col-sm-offset-2 col-sm-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    New Task
                </div>
                <div class="panel-body">
                    <noscript class="text text-danger">
                        Javascript is disabled from your browser please enable
                    </noscript>
                    <!-- Display Validation Errors -->
                    @include('common.errors')
                    <!-- New Task Form -->
                    <form id="form" action="{{ isset($edit_tasks)?url('update'):url('task')}}" method="POST" class="form-horizontal">
                        {{ csrf_field() }}
                        <input type="hidden" name="edit_id" value="{{isset($edit_tasks->id) ? $edit_tasks->id:''}}">
                        <!-- Task Name -->
                        <div class="form-group">
                            <label for="task-name" class="col-sm-3 control-label">Task</label>
                            <div class="col-sm-6">
                            <input type="text" name="name" id="task-name" class="form-control" 
                            value="{{ isset($edit_tasks->name) ? $edit_tasks->name : ''}}">
                                                        </div>
                        </div>
                        <div class="form-group">
                            <label for="task_date" class="col-sm-3 control-label">
                            Task Date</label>
                            <div class="col-sm-6">
                            <input type="text" readonly="" name="task_date" id="task_date" class="form-control datepicker" 
                            value="{{ isset($edit_tasks->task_date) ? $edit_tasks->task_date : ''}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Due_date" class="col-sm-3 control-label">Due Date</label>
                            <div class="col-sm-6">
                                <input readonly="" type="text" name="Due_date" id="Due_date" class="form-control datepicker" value="{{ isset($edit_tasks->due_date) ? $edit_tasks->due_date : ''}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="reminder_date" class="col-sm-3 control-label">Reminder</label>
                            <div class="col-sm-3">
                                <input readonly="" type="text" name="reminder_date" id="reminder_date" class="form-control datepicker" 
                                value="{{ isset($edit_tasks->remind_date) ? $edit_tasks->remind_date : ''}}">
                           </div>
                            <div class="col-sm-3">
                                <input type="time" name="reminder_time" id="reminder_time" class="form-control" 
                                value="{{ isset($edit_tasks->remind_time) ? $edit_tasks->remind_time : ''}}">
                            </div>
                        </div>
                        <!-- Add Task Button -->
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-6">
                                <button type="submit" class="btn btn-default">
                                   @if (isset($edit_tasks))
                                   <i class="fa fa-btn fa-plus"></i>Update Task
                                    @else                                        
                                    <i class="fa fa-btn fa-plus"></i>Add Task
                                    @endif                                    
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
                <audio style="display: none;" controls>
                  <source src="resources/assets/Elephanttrumpetinganimals129.wav" 
                  type="audio/ogg">
                  <source src="resources/assets/Elephanttrumpetinganimals129.wav" type="audio/mpeg">
                  Your browser does not support the audio element.
                </audio>
            <!-- Current Tasks -->
            @if (isset($tasks))
                @if (count($tasks) > 0)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Current Tasks
                    </div>
                    
                    <div class="panel-body">
                        <table class="table table-striped task-table">
                            <thead>
                                <th>Task</th>
                                <th>Task Date</th>
                                <th>Due Date</th>
                                <th colspan="2" style="text-align: center;">Reminder</th>
                                <th width=20%>&nbsp;</th>
                            </thead>
                            <tbody>
                                <?php  
                                   // $reminder_date = date("d-m-Y H:i:s");
                                   // echo $reminder_date;
                                ?>
                                @foreach ($tasks as $task)
                                    <tr>
                                        <td class="table-text"><div>{{ $task->name }}</div></td>
                                        <td class="table-text">
                                            <div>{{ date('d-m-Y',strtotime($task->task_date)) }}</div>
                                        </td>
                                        <td class="table-text">
                                            <div>{{ date('d-m-Y',strtotime($task->due_date)) }}</div>
                                        </td>
                                        <td class="table-text">
                                            <div>{{ date('d-m-Y',strtotime($task->remind_date)) }}</div>
                                        </td>
                                        <td class="table-text">
                                            <div>{{ $task->remind_time }}</div>
                                        </td>
                                        <!-- Task Delete Button -->
                                        <td>
                                            <form action="{{ url('task/'.$task->id) }}" method="POST">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}           
                                           <a href="edit/{{ $task->id }}" class="btn btn-primary btn-xs">  <i class="fa fa-btn fa-pencil"></i> Edit </a>                     
                                                <button type="submit" class="btn btn-danger btn-xs">
                                                    <i class="fa fa-btn fa-trash"></i>Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            @endif
        </div>
    </div>
    <div id="Modal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Reminder</h4>
      </div>
      <div class="modal-body">
        <p class="text-warning" id="reminder"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
    <script type="text/javascript"> 

    $(document).ready(function(){  

        // $('audio').trigger('play');

        $('.datepicker').datepicker({ 
            format: 'yyyy-mm-dd', 
            startDate: '-3d',
            autoclose: true,  
        });
        $('#form').validate();
        setInterval(function(){
            var date = get_current_date().trim();
            // console.log(date);
                $('.table-striped tbody tr').each(function(i,e){
                var reminder = $(this).children('td').eq(3).children('div').html()+' '+$(this).children('td').children('div').eq(4).html()
                var task = $(this).children('td').eq(0).children('div').html();
                // debugger;
                if(date == reminder){
                    $('#reminder').html(task);
                     $('audio').trigger('play');
                    $('#Modal').modal('show');
                }
            });
        }, 1000);
    });
    function get_current_date(){
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth() + 1;        
            var yyyy = today.getFullYear();
            var hours = today.getHours();
            var minutes = today.getMinutes();
            var seconds = today.getSeconds();
            if (dd < 10) {
              dd = '0' + dd;
            } 
            if (mm < 10) {
              mm = '0' + mm;
            }if (seconds < 10) {
              seconds = '0' + seconds;
            }if (minutes < 10) {
              minutes = '0' + minutes;
            }if (hours < 10) {
              hours = '0' + hours;
            } 
            current_date = dd+"-"+mm+"-"+yyyy+" "+hours+":"+minutes+":"+seconds;
            return current_date;
    }
</script>
@endsection


