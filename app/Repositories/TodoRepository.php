<?php

namespace App\Repositories;

use App\Models\Todo;
use App\Models\User;

use Illuminate\Support\Facades\Hash;


class TodoRepository {
    protected $model;

    public function __construct(Todo $model) {
        $this->model = $model;
    }

    public function getAll() {
        return $this->model->all();
    }

    public function find($id) {
        return $this->model->where('id',$id)->first();
    }

    public function destroy($id) {

        return $this->model->where('id',$id)->delete($id);
    }
}
