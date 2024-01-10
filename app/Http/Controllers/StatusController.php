<?php

namespace App\Http\Controllers;

use App\Status;
use App\Task;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => ['required', 'string', 'max:56']
        ]);

        $request['order'] = (!empty(Status::max('order'))) ? Status::max('order') + 1 : 1;
        $res = Status::create($request->only('title',  'order'));
        if ($res)
            return Status::where('id', $res['id'])->with('tasks')->get();
    }

    public function destroy(Status $status)
    {
        $status->tasks()->update(['status' => '0']);
        $status->tasks()->delete();
        $status->delete();
        return array('success' => true, 'message' => 'Column removed Successfully');
    }
}