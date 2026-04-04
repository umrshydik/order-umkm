@extends('layouts.admin')

@section('header', 'Manajemen Permissions')

@section('content')
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-4 border-b flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Daftar Permissions</h2>
        <a href="{{ route('admin.permissions.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm font-medium">
            Tambah Permission
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="p-4 font-medium text-gray-600">ID</th>
                    <th class="p-4 font-medium text-gray-600">Nama Permission (cth: edit data pesanan)</th>
                    <th class="p-4 font-medium text-gray-600 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($permissions as $permission)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-4 text-gray-800">{{ $permission->id }}</td>
                    <td class="p-4 text-gray-800">{{ $permission->name }}</td>
                    <td class="p-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.permissions.edit', $permission) }}" class="text-indigo-600 hover:text-indigo-900 border border-indigo-200 px-3 py-1 rounded hover:bg-indigo-50 text-sm">Edit</a>
                            
                            <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus permission ini?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 border border-red-200 px-3 py-1 rounded hover:bg-red-50 text-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="p-4 text-center text-gray-500 py-8">Belum ada data permission.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-4 border-t">
        {{ $permissions->links() }}
    </div>
</div>
@endsection
