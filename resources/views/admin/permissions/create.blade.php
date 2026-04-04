@extends('layouts.admin')

@section('header', 'Tambah Permission')

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-2xl mx-auto">
    <form action="{{ route('admin.permissions.store') }}" method="POST">
        @csrf
        <div class="mb-6">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nama Permission</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Contoh: edit data pesanan" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            <p class="text-xs text-gray-500 mt-2">Gunakan format "aksi namemenu" agar dapat terkelompokkan dengan baik pada menu Manajemen Hak Akses. (Contoh: "view data pesanan", "create data pesanan", "edit data pesanan", "delete data pesanan")</p>
            @error('name') <span class="text-red-500 text-xs text-italic">{{ $message }}</span> @enderror
        </div>
        
        <div class="flex items-center justify-between mt-8 pt-4 border-t">
            <a href="{{ route('admin.permissions.index') }}" class="text-gray-600 hover:text-gray-800">Batal</a>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
