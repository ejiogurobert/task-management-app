<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Task Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui/1.12.1/jquery-ui.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui/1.12.1/jquery-ui.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Task Management</h1>

    <!-- Task Creation Form -->
    <form action="{{ route('tasks.store') }}" method="POST" class="mb-4">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <input type="text" name="name" class="form-control" placeholder="Task Name" required>
            </div>
            <div class="col-md-3">
                <select name="project_id" class="form-control" required>
                    <option value="" selected disabled>Select Project</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Add Task</button>
            </div>
        </div>
    </form>

    <!-- Task Table -->
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Task Name</th>
            <th>Priority</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody id="sortable">
        @foreach($tasks as $task)
            <tr data-id="{{ $task->id }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $task->name }}</td>
                <td>{{ $task->priority }}</td>
                <td>
                    <!-- Edit Task -->
                    <form action="{{ route('tasks.update', $task->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <input type="text" name="name" value="{{ $task->name }}" required>
                        <button type="submit" class="btn btn-sm btn-success">Update</button>
                    </form>

                    <!-- Delete Task -->
                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<!-- Drag-and-Drop Sorting -->
<script>
    $(function () {
        $("#sortable").sortable({
            update: function (event, ui) {
                let taskOrder = $(this).sortable('toArray', {attribute: 'data-id'});

                $.ajax({
                    url: "{{ route('tasks.reorder') }}",
                    method: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        tasks: taskOrder
                    },
                    success: function (response) {
                        if (response.success) {
                            alert('Task priorities updated!');
                            location.reload();
                        }
                    }
                });
            }
        });
    });
</script>

</body>
</html>
