<?php

namespace App\Http\Controllers;

use App\Task;
use App\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Status::with('tasks')->get();
        return view('tasks.index', compact('tasks'));
    }

    public function getTasks(Request $request)
    {
        $task = Task::query();
        if ($request->has('date'))
            $task->whereDate('created_at', '=', $request->input('date'));
        if ($request->has('status') && $request->status == 0)
            $task->withTrashed();
        return $task->get();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => ['required', 'string', 'max:56'],
            'description' => ['nullable', 'string'],
            'status_id' => ['required', 'exists:statuses,id']
        ]);

        return Task::create($request->only('title', 'description', 'status_id'));
    }

    public function sync(Request $request)
    {
        $this->validate(request(), [
            'columns' => ['required', 'array']
        ]);

        foreach ($request->columns as $status) {
            foreach ($status['tasks'] as $i => $task) {
                $order = $i + 1;
                if ($task['status_id'] !== $status['id'] || $task['order'] !== $order) {
                    TASK::find($task['id'])
                        ->update(['status_id' => $status['id'], 'order' => $order]);
                }
            }
        }
        return Status::with('tasks')->get();
    }

    public function update(Request $request, Task $task)
    {
        $this->validate($request, [
            'title' => ['required', 'string', 'max:56'],
            'description' => ['nullable', 'string']
        ]);
        $task->update($request->only('title', 'description'));
        return $task;
    }

    public function getAllCards(Request $request)
    {
        $creationDate = $request->query('date');
        $status = $request->query('status');

        $cards = Task::query();

        if ($creationDate) {
            $dateTime = new DateTime($creationDate);
            $dateTime->modify($dateTime->format('Y-d-m'));
            $result = $dateTime->format('Y-m-d');
            $cards->whereDate('created_at', $result);
        }

        if ($status !== null) {
            if ($status == 0) {
                $cards->withTrashed();
            } else {
                $cards->where('status', $status);
            }
        }

        $result = $cards->get();

        $formattedResult = $result->map(function ($card) {
            return [
                'id' => $card->id,
                'title' => $card->title,
                'created_at' => $card->created_at,
                'description' => $card->description,
                'deleted_at' => $card->deleted_at,
            ];
        });

        return response()->json($formattedResult);
    }
}