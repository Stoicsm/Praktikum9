<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="container p-4 mx-auto">
        {{-- TAMPILAN PESAN SESSION --}}
        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-50 p-4 text-green-700">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="mb-4 rounded-lg bg-red-50 p-4 text-red-700">
                {{ session('error') }}
            </div>
        @endif
        {{-- END SESSION --}}

        {{-- FORM CARI --}}
        <form method="GET" action="{{ route('product-index') }}" class="mb-4 flex items-center">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari produk..."
                class="w-1/4 rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            <button type="submit"
                class="ml-2 rounded-lg bg-green-500 px-4 py-2 text-white shadow-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                Cari
            </button>
        </form>

        <div class="overflow-x-auto shadow-lg sm:rounded-lg">
            {{-- Tombol Tambah Produk dan Export Excel --}}
            <div class="mb-4 flex gap-2">
                <a href="{{ route('product-create') }}">
                    <button
                        class="px-6 py-4 text-white bg-green-500 border border-green-500 rounded-lg shadow-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 transition duration-150 ease-in-out">
                        Add product data
                    </button>
                </a>

                {{-- Tombol Export Excel, PDF, JPG --}}
                <div class="mb-4 flex gap-2">
    <a href="{{ route('product-export-excel') }}">
        <button class="px-6 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600">Export ke Excel</button>
    </a>

    <a href="{{ route('product-export-pdf') }}">
        <button class="px-6 py-2 text-white bg-red-500 rounded-lg hover:bg-red-600">Export ke PDF</button>
    </a>

    <a href="{{ route('product-export-jpg') }}">
        <button class="px-6 py-2 text-white bg-green-500 rounded-lg hover:bg-green-600">Export ke JPG</button>
    </a>
</div>


            {{-- Tabel Data Produk --}}
            <table class="min-w-full border border-collapse border-gray-200">
                <thead>
                    <tr class="bg-gray-100">
                        @php
                            $sortBy = request('sort_by', 'id');
                            $sortOrder = request('sort_order', 'asc');
                            $nextOrder = $sortOrder === 'asc' ? 'desc' : 'asc';
                        @endphp

                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider border border-gray-200">
                            <a href="{{ route('product-index', ['sort_by' => 'id', 'sort_order' => $nextOrder, 'search' => request('search')]) }}"
                                class="hover:underline">
                                ID {!! $sortBy === 'id' ? ($sortOrder === 'asc' ? '▲' : '▼') : '' !!}
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider border border-gray-200">
                            <a href="{{ route('product-index', ['sort_by' => 'product_name', 'sort_order' => $nextOrder, 'search' => request('search')]) }}"
                                class="hover:underline">
                                Product Name {!! $sortBy === 'product_name' ? ($sortOrder === 'asc' ? '▲' : '▼') : '' !!}
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider border border-gray-200">
                            <a href="{{ route('product-index', ['sort_by' => 'unit', 'sort_order' => $nextOrder, 'search' => request('search')]) }}"
                                class="hover:underline">
                                Unit {!! $sortBy === 'unit' ? ($sortOrder === 'asc' ? '▲' : '▼') : '' !!}
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider border border-gray-200">
                            <a href="{{ route('product-index', ['sort_by' => 'type', 'sort_order' => $nextOrder, 'search' => request('search')]) }}"
                                class="hover:underline">
                                Type {!! $sortBy === 'type' ? ($sortOrder === 'asc' ? '▲' : '▼') : '' !!}
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider border border-gray-200">
                            <a href="{{ route('product-index', ['sort_by' => 'information', 'sort_order' => $nextOrder, 'search' => request('search')]) }}"
                                class="hover:underline">
                                Information {!! $sortBy === 'information' ? ($sortOrder === 'asc' ? '▲' : '▼') : '' !!}
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider border border-gray-200">
                            <a href="{{ route('product-index', ['sort_by' => 'qty', 'sort_order' => $nextOrder, 'search' => request('search')]) }}"
                                class="hover:underline">
                                Qty {!! $sortBy === 'qty' ? ($sortOrder === 'asc' ? '▲' : '▼') : '' !!}
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider border border-gray-200">
                            <a href="{{ route('product-index', ['sort_by' => 'producer', 'sort_order' => $nextOrder, 'search' => request('search')]) }}"
                                class="hover:underline">
                                Producer {!! $sortBy === 'producer' ? ($sortOrder === 'asc' ? '▲' : '▼') : '' !!}
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider border border-gray-200">
                            <a href="{{ route('product-index', ['sort_by' => 'supplier', 'sort_order' => $nextOrder, 'search' => request('search')]) }}"
                                class="hover:underline">
                                Supplier {!! $sortBy === 'supplier' ? ($sortOrder === 'asc' ? '▲' : '▼') : '' !!}
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider border border-gray-200">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @foreach ($data as $item)
                        <tr class="bg-white hover:bg-gray-50">
                            <td class="px-4 py-2 border border-gray-200 text-sm text-gray-900">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 border border-gray-200 text-sm text-gray-900">
                                <a href="{{ route('product-detail', $item->id) }}" class="text-blue-600 hover:underline">
                                    {{ $item->product_name }}
                                </a>
                            </td>
                            <td class="px-4 py-2 border border-gray-200 text-sm text-gray-900">{{ $item->unit }}</td>
                            <td class="px-4 py-2 border border-gray-200 text-sm text-gray-900">{{ $item->type }}</td>
                            <td class="px-4 py-2 border border-gray-200 text-sm text-gray-900">{{ $item->information }}</td>
                            <td class="px-4 py-2 border border-gray-200 text-sm text-gray-900">{{ $item->qty }}</td>
                            <td class="px-4 py-2 border border-gray-200 text-sm text-gray-900">{{ $item->producer }}</td>
                            <td class="px-4 py-2 border border-gray-200 text-sm text-gray-900">{{ optional($item->supplier)->name }}</td>
                            <td class="px-4 py-2 border border-gray-200 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('product-edit', $item->id) }}"
                                    class="text-blue-600 hover:text-blue-800 transition duration-150 ease-in-out mr-2">
                                    Edit
                                </a>
                                <button class="text-red-600 hover:text-red-800 transition duration-150 ease-in-out"
                                    onclick="confirmDelete('{{ route('product-deleted', $item->id) }}')">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $data->appends(['search' => request('search'), 'sort_by' => request('sort_by'), 'sort_order' => request('sort_order')])->links() }}
        </div>
    </div>

    {{-- Script Konfirmasi Hapus --}}
    <script>
        function confirmDelete(deleteUrl) {
            if (confirm('apakah anda yakin?')) {
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteUrl;

                let csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);

                let methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>

    {{-- 🚀 SweetAlert untuk Notifikasi Pop-up --}}
    @if(session('swal'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: '{{ session('swal.icon') }}',
                title: '{{ session('swal.title') }}',
                text: '{{ session('swal.text') }}',
                confirmButtonColor: '#3085d6',
            });
        </script>
    @endif
</x-app-layout>
