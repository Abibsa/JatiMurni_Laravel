<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new User([
            'name' => $row['nama'],
            'email' => $row['email'],
            'password' => Hash::make($row['password'] ?? 'password'),
            'phone' => $row['telepon'],
            'address' => $row['alamat'],
            'role' => $row['role'] ?? 'customer',
            'status' => $row['status'] ?? 'active',
        ]);
    }
} 