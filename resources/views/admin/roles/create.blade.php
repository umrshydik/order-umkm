@extends('layouts.admin')

@section('header', 'Tambah Role')

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-3xl mx-auto">
    <form action="{{ route('admin.roles.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nama Role</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            @error('name') <span class="text-red-500 text-xs text-italic">{{ $message }}</span> @enderror
        </div>
        
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-4">Pilih Permissions</label>
            <div class="space-y-4">
                @foreach($permissions as $group => $groupPermissions)
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="font-bold text-gray-800 capitalize mb-3">{{ $group }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach($groupPermissions as $permission)
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="form-checkbox h-5 w-5 text-blue-600 rounded">
                            <span class="ml-2 text-gray-700 text-sm capitalize">{{ explode(' ', $permission->name)[0] }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            @error('permissions') <span class="text-red-500 text-xs text-italic">{{ $message }}</span> @enderror
        </div>

        <div class="flex items-center justify-between mt-8 pt-4 border-t">
            <a href="{{ route('admin.roles.index') }}" class="text-gray-600 hover:text-gray-800">Batal</a>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
