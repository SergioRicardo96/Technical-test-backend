<?php

namespace App\Policies;

class TaskPolicy
{
    public function edit($user, $task)
    {
        return $user['id'] === $task['user_id'];
    }

    public function delete($user, $task)
    {
        return $user['id'] === $task['user_id'];
    }
}