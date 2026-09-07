<?php

namespace App\Core\Repository\Employee;

use App\Models\Users\Employee;
use App\Models\Users\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmployeeRepository
{
    public function save(Employee $employee): void
    {
        if (!$employee->save()) {
            throw new \RuntimeException(__('client.Employee save error'));
        }
    }

    public function getEmployeeProfile(int $user_id): Model|Builder
    {
        return Employee::query()
            ->from('employees as e')
            ->select(DB::raw("e.*,CONCAT(f.path,'/',f.file_name) as image,u.*"))
            ->join('users as u', 'u.employee_id', '=', 'e.id')
            ->leftJoin('files as f','f.id','=','e.file_id')
            ->where('u.id', $user_id)
            ->firstOrFail();
    }
    public function get(int $id): Model|Builder
    {
        return Employee::query()->where('id', $id)->firstOrFail();
    }

    public function getEmployeeUser(int $employee_id): Model|Builder
    {
        return User::query()
            ->where('employee_id', $employee_id)
            ->firstOrFail();
    }
}
