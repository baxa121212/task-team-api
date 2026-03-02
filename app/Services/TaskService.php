<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;

class TaskService
{
    public function createForUser(User $user, array $data): Task
    {
        return $user->tasks()->create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'is_completed' => $data['is_completed'] ?? false,
        ]);
    }

    public function update(Task $task, array $data): Task
    {
        $task->fill([
            'title' => $data['title'] ?? $task->title,
            'description' => array_key_exists('description', $data) ? $data['description'] : $task->description,
            'is_completed' => $data['is_completed'] ?? $task->is_completed,
        ])->save();

        return $task;
    }
}
