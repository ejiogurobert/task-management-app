<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $projectId = $request->get('project_id');
        $tasks = Task::where('project_id', $projectId)->orderBy('priority')->get();
        $projects = Project::all();

        return view('tasks.index', compact('tasks', 'projects', 'projectId'));
    }
    public function store(Request $request)
    {
       try {
        $request->validate([
            'name' => 'required|string|max:255',
            'projectId' => 'nullable|exists:projects, id',
        ]);

        $priority = Task::where('projectId', $request->project_id)->max('priority') + 1;

        Task::create([
            'name' => $request->name,
            'priority' => $priority,
            'projectId' => $request->project_id
        ]);

        return redirect()->back()->with('Success', 'Task was created successfully!');
       } catch (\Exception $e) {
        return redirect()->back()->with('Error', 'Failed to create task. Please try again');
    }
}

    public function update(Request $request, $id)
    {
    try {
        $validatedData = $request->validate([
            'name' => 'required'|'string'|'max:255',
        ]);

        $task = Task::findOrFail($id);

        $task->update($validatedData['name']);

        return redirect()->back()->with('Success', 'Task was updated successfully');
    } catch (\Exception $e) {
        return redirect()->back()->with('Error', 'Failed to update task. Please try again');
    }
    }

    public function destroy($id) {
        $task = Task::findOrFail($id);

        $task->delete();

        return redirect()->back()->with('Success', 'Task was deleted successfully');
    }

    public function reorder(Request $request) {
        foreach ($request->taskList as $index => $taskId) {
            Task::where('id', $taskId)->update(['priority' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
