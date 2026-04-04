@extends('layouts.admin')

@section('header', 'Laporan Pendapatan')

@section('content')
<div class="bg-white rounded-lg shadow overflow-hidden">
    <!-- Filter Bar -->
    <div class="p-6 border-b border-gray-200 bg-gray-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form action="{{ route('admin.incomes.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" class="shadow-sm border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border px-3 py-2">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" class="shadow-sm border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border px-3 py-2">
            </div>
            <button type="submit" class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Filter
            </button>
        </form>

        <a href="{{ route('admin.incomes.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank" class="bg-green-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center items-center text-sm font-medium text-white hover:bg-green-700 focus:outline-none">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Export CSV
        </a>
    </div>
    
    <!-- Data Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pesanan Selesai</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php $totalSemua = 0; @endphp
                @forelse($incomes as $income)
                @php $totalSemua += $income->total_revenue; @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                        {{ \Carbon\Carbon::parse($income->date)->translatedFormat('d F Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $income->total_orders }} Pesanan</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-bold text-green-600">
                        Rp {{ number_format($income->total_revenue, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500">
                        Tidak ada data pendapatan pada rentang tanggal ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($incomes->isNotEmpty())
            <tfoot class="bg-gray-50 font-bold border-t-2 border-gray-200">
                <tr>
                    <td colspan="2" class="px-6 py-4 text-right text-gray-700 uppercase">Total Pendapatan Keseluruhan</td>
                    <td class="px-6 py-4 text-right text-green-700 text-lg">Rp {{ number_format($totalSemua, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $incomes->appends(['start_date' => $startDate, 'end_date' => $endDate])->links() }}
    </div>
</div>
@endsection
