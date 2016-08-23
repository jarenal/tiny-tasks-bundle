// Function for to load tasks into the table
function loadTasks(){

    // GET /api/tasks
    $.ajax('/api/tasks', {
        type: 'GET',
        accepts: 'application/json',
        dataType: 'json',
        error: function(jqXHR,textStatus,error){},
        success: function(data, textStatus, jqXHR){
            $.Mustache.addFromDom();

            $("#tasks-table").html("");

            $.Mustache.load('/bundles/jarenaltinytasks/templates/tasks.html')
                .done(function () {
                // Iterate records...
                $.each(data, function(index, task){
                    task.status = '';

                    if(task.hasOwnProperty('state'))
                    {
                        switch(task.state.id)
                        {
                            case 1:
                                task.status = 'danger';
                                break;
                            case 2:
                                task.status = 'info';
                                break;
                            case 3:
                                task.status = 'success';
                                break;
                        }
                    }


                    $('#tasks-table').mustache('task-row', task, { method: 'append' });
                });
            });
        },
        cache: false
    });
}

// Function for remove a task by id
function removeTask(idtask){

    // DELETE /api/tasks/{id}
    $.ajax('/api/tasks/'+idtask, {
        type: "DELETE",
        accepts: 'application/json',
        dataType: 'json',
        error: function(jqXHR,textStatus,error){},
        success: function(data, textStatus, jqXHR){
            loadTasks();
        }
    });
}

// OnReady...
$(function(){

    // load tasks into the table
    loadTasks();

    // Click in save button ( Here we manage POST & PUT request)
    $(document).on('click', '#btn-save-task', function(e){
        e.preventDefault();

        idtask = $('#task_id').val();

        var params = {};
        params.description = $('#task_description').val();
        params.status = $('#task_status').val();

        // Validation
        if(!params.description.length)
        {
            $('#popup-form-task .alert').html("Please, enter the description.").fadeIn();
            return false;
        }

        if(!params.status.length)
        {
            $('#popup-form-task .alert').html("Please, select a status.").fadeIn();
            return false;
        }

        // If we have idtask, then is PUT
        if(idtask.length)
        {
            // PUT /api/tasks/{id}
            $.ajax('/api/tasks/'+idtask, {
                type: "PUT",
                accepts: 'application/json',
                dataType: 'json',
                error: function(jqXHR,textStatus,error){},
                success: function(data, textStatus, jqXHR){
                    $('#popup-form-task').modal('hide');
                    loadTasks();
                },
                data: params
            });
        }
        else // If we don't have idtask then is POST
        {
            // POST /api/tasks
            $.ajax('/api/tasks', {
                type: "POST",
                accepts: 'application/json',
                dataType: 'json',
                error: function(jqXHR,textStatus,error){},
                success: function(data, textStatus, jqXHR){
                    $('#popup-form-task').modal('hide');
                    loadTasks();
                },
                data: params
            });
        }


    });

    // Click in edit button.
    $(document).on('click', '.btn-edit-task', function(e){
        e.preventDefault();
        $('#popup-label').html('Edit task');
        var idstatus = $(this).data('state');

        // We fill the form with data from the task.
        $('#task_id').val( $(this).data('id') );
        $('#task_description').val( $(this).data('description') );
        $('#task_status option').filter(function() {
            return ($(this).val() ==  idstatus);
        }).prop('selected', true);

        // Show the form
        $('#popup-form-task').modal('show');

    });

    // Try to delete a task
    $(document).on('click', '.try-delete-task', function(e){
        e.preventDefault();
        var idtask = $(this).data('idtask');
        $('#task-number').html(idtask);

        // Show the confirmation popup
        $('#popup-confirm-delete-task').modal('show');

    });

    // Click in confirm deletion.
    $(document).on('click', '#btn-confirm-deletion-task', function(e){
        e.preventDefault();
        var idtask = $('#task-number').html();
        // Execute deletion.
        removeTask(idtask);
        $('#task-number').html("");
        $('#popup-confirm-delete-task').modal('hide');
    });

    // We clean the popup when on fire the hidden event
    $('#popup-form-task').on('hide.bs.modal', function (e) {
        $('#task_id').val("");
        $('#task_description').val("");
        $('#task_status').val( $('#task_status option:first').val() );
        $('#popup-form-task .alert').hide();
        $('#popup-label').html('New task');
    });
});