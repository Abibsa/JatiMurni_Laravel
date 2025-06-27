<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromQuery, WithHeadings, WithMapping
{
    protected $columns;

    public function __construct($columns)
    {
        $this->columns = $columns;
    }

    public function query()
    {
        return User::query();
    }

    public function headings(): array
    {
        $headings = [];
        foreach ($this->columns as $column) {
            $headings[] = $this->getColumnHeading($column);
        }
        return $headings;
    }

    public function map($user): array
    {
        $row = [];
        foreach ($this->columns as $column) {
            $row[] = $this->getColumnValue($user, $column);
        }
        return $row;
    }

    protected function getColumnHeading($column)
    {
        return match($column) {
            'id' => 'ID',
            'name' => 'Nama',
            'email' => 'Email',
            'phone' => 'No. Telepon',
            'address' => 'Alamat',
            'role' => 'Role',
            'status' => 'Status',
            default => ucfirst($column)
        };
    }

    protected function getColumnValue($user, $column)
    {
        return match($column) {
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'address' => $user->address,
            'role' => ucfirst($user->role),
            'status' => ucfirst($user->status),
            default => $user->{$column}
        };
    }
} 