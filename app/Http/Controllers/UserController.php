<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->has('role') && $request->role !== '') {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $users = $query->paginate(10);

        return view('admin.Pengguna.index', compact('users'));
    }

    public function create()
    {
        return view('pengguna.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users|max:100',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'role' => 'required|in:admin,customer',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'address' => $request->address,
                'role' => $request->role,
                'status' => $request->status,
            ]);

            return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan pengguna: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('pengguna.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'role' => 'required|in:admin,customer',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'role' => $request->role,
                'status' => $request->status,
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui pengguna: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new UsersImport, $request->file('file'));
            return redirect()->route('pengguna.index')->with('success', 'Data pengguna berhasil diimport');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $request->validate([
            'format' => 'required|in:xlsx,pdf',
            'columns' => 'required|array'
        ]);

        try {
            $export = new UsersExport($request->columns);
            
            if ($request->format === 'xlsx') {
                return Excel::download($export, 'users.xlsx');
            } else {
                return Excel::download($export, 'users.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengeksport data: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|string'
        ]);

        try {
            $ids = explode(',', $request->ids);
            User::whereIn('id', $ids)->delete();
            return redirect()->route('pengguna.index')->with('success', 'Pengguna terpilih berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'ids' => 'required|string',
            'role' => 'nullable|in:admin,customer',
            'status' => 'nullable|in:active,inactive'
        ]);

        try {
            $ids = explode(',', $request->ids);
            $updateData = [];

            if ($request->filled('role')) {
                $updateData['role'] = $request->role;
            }

            if ($request->filled('status')) {
                $updateData['status'] = $request->status;
            }

            if (!empty($updateData)) {
                User::whereIn('id', $ids)->update($updateData);
                return redirect()->route('pengguna.index')->with('success', 'Pengguna terpilih berhasil diperbarui');
            }

            return redirect()->back()->with('error', 'Tidak ada data yang diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui pengguna: ' . $e->getMessage());
        }
    }

    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:8'
        ]);

        try {
            $user = User::findOrFail($id);
            $user->update([
                'password' => Hash::make($request->password)
            ]);
            return redirect()->route('pengguna.index')->with('success', 'Password berhasil direset');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mereset password: ' . $e->getMessage());
        }
    }

    public function profil()
    {
        $user = Auth::user();
        return view('pengguna.profil.index', compact('user'));
    }

    public function tambahPesanan($id)
    {
        // Logic untuk menambah produk ke pesanan/keranjang user
        // Contoh sederhana:
        // 1. Cek user login
        // 2. Tambah ke tabel pesanan/keranjang
        // 3. Redirect/flash message

        // return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke pesanan!');
    }
} 