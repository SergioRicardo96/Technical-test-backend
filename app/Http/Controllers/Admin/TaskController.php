<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libs\Flash;
use App\Libs\Translation;
use App\Libs\Validator;
use App\Models\CatalogTaskStatus;
use App\Models\Task;
use App\Policies\TaskPolicy;

class TaskController extends Controller
{
    public function index()
    {
        $userId = $_SESSION['user']['id'] ?? 0;
        $tasks = (new Task())->where('user_id', $userId)->get();
        $taskStatuses = (new CatalogTaskStatus())->all();

        $statusMap = [];
        foreach ($taskStatuses as $status) {
            $statusMap[$status['id']] = $status;
        }

        $tasks = array_map(function ($task) use ($statusMap) {
            $task['catalog_task_status'] = $statusMap[$task['catalog_task_status_id']] ?? null;
            return $task;
        }, $tasks);

        return $this->view('admin.tasks.index', compact('tasks', 'taskStatuses'));
    }

    public function create()
    {
        $taskStatuses = (new CatalogTaskStatus())->all();

        return $this->view('admin.tasks.create', compact('taskStatuses'));
    }

    public function store()
    {   
        $data = [
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'catalog_task_status_id' => $_POST['catalog_task_status_id'] ?? '',
        ];

        $rules = [
            'title' => 'required|min:5|max:255',
            'description' => 'required|min:8|max:255',
            'catalog_task_status_id' => 'required|numeric'
        ];

        $fieldNames = [
            'title' => strtolower(Translation::trans('tasks', 'title')),
            'description' => strtolower(Translation::trans('tasks', 'description')),
            'catalog_task_status_id' => strtolower(Translation::trans('tasks', 'task_status')),
        ];

        $validator = new Validator($data, $rules, $fieldNames);

        if (!$validator->validate()) {
            $errors = $validator->errors();
            $taskStatuses = (new CatalogTaskStatus())->all();
            return $this->view('admin.tasks.create', compact('data', 'errors', 'taskStatuses'));
        }

        $data['user_id'] = $_SESSION['user']['id'] ?? 0;
        $taskModel = new Task();
        $taskModel->create($data);

        Flash::set('success', Translation::trans('tasks', 'task_success_create'));
        return $this->redirect('/admin/tasks');
    }

    public function edit($id)
    {
        $taskStatuses = (new CatalogTaskStatus())->all();
        $task = (new Task())->find($id);

        return $this->view('admin.tasks.edit', compact('taskStatuses', 'task'));
    }

    public function update($id)
    {
        $data = [
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'catalog_task_status_id' => $_POST['catalog_task_status_id'] ?? '',
        ];

        $rules = [
            'title' => 'required|min:5|max:255',
            'description' => 'required|min:8|max:255',
            'catalog_task_status_id' => 'required|numeric'
        ];

        $fieldNames = [
            'title' => strtolower(Translation::trans('tasks', 'title')),
            'description' => strtolower(Translation::trans('tasks', 'description')),
            'catalog_task_status_id' => strtolower(Translation::trans('tasks', 'task_status')),
        ];

        $validator = new Validator($data, $rules, $fieldNames);

        if (!$validator->validate()) {
            $errors = $validator->errors();
            $taskStatuses = (new CatalogTaskStatus())->all();
            return $this->view('admin.tasks.create', compact('data', 'errors', 'taskStatuses'));
        }

        $user = $_SESSION['user'] ?? [];
        $task = (new Task())->find($id);
        $this->authorize(TaskPolicy::class, 'update', $user, $task);

        (new Task())->update($id, $data);

        Flash::set('success', Translation::trans('tasks', 'task_success_edit'));
        return $this->redirect('/admin/tasks');
    }

    public function destroy($id)
    {
        $user = $_SESSION['user'] ?? [];
        $task = (new Task())->find($id);
        $this->authorize(TaskPolicy::class, 'delete', $user, $task);

        (new Task())->delete($id);
        Flash::set('success', Translation::trans('tasks', 'task_success_destroy'));
        return $this->redirect('/admin/tasks');
    }
}