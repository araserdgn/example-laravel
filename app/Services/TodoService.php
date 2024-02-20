<?php

namespace App\Services;

use App\Repositories\TodoRepository;

class TodoService {
    protected $todoRepository;

    public function __construct(TodoRepository $todoRepository) {
        $this->todoRepository = $todoRepository;
    }

    public function getAll() {
        return $this->todoRepository->getAll();
    }

    public function find($id) {
        return $this->todoRepository->find($id);
    }

    public function destroy($id) {
        return $this->todoRepository->destroy($id);
    }
}
