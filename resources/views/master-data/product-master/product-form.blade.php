<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ isset($product) ? __('Edit Product') : __('Add Product') }}
        </h2>
    </x-slot>

    <div class="container mx-auto p-4">
        <div class="overflow-x-auto rounded-lg bg-white p-6 shadow-md">

            <!-- Back button -->
            <a href="{{ route('product-index') }}" class="text-blue-500 hover:underline">← Back</a>

            <form 
                action="{{ isset($product) ? route('product-update', $product->id) : route('product-store') }}" 
                method="POST" 
                class="mt-4"
                onsubmit="return handleSubmit(event)"
            >
                @csrf
                @if(isset($product))
                    @method('PUT')
                @endif

                <div class="mb-4">
                    <label class="block text-gray-700">Product Name</label>
                    <input type="text" name="product_name" class="mt-1 w-full rounded border p-2" 
                        value="{{ $product->product_name ?? old('product_name') }}" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Unit</label>
                    <input type="text" name="unit" class="mt-1 w-full rounded border p-2" 
                        value="{{ $product->unit ?? old('unit') }}" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Type</label>
                    <input type="text" name="type" class="mt-1 w-full rounded border p-2" 
                        value="{{ $product->type ?? old('type') }}" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Information</label>
                    <textarea name="information" class="mt-1 w-full rounded border p-2" required>{{ $product->information ?? old('information') }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Quantity</label>
                    <input type="number" name="qty" class="mt-1 w-full rounded border p-2" 
                        value="{{ $product->qty ?? old('qty') }}" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Producer</label>
                    <input type="text" name="producer" class="mt-1 w-full rounded border p-2" 
                        value="{{ $product->producer ?? old('producer') }}" required>
                </div>

                <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                    {{ isset($product) ? 'Update Product' : 'Add Product' }}
                </button>
            </form>
        </div>
    </div>

    <script>
        // Fungsi alert pop-up saat submit berhasil/gagal
        function handleSubmit(event) {
            event.preventDefault();

            // Ambil form
            const form = event.target;

            fetch(form.action, {
                method: form.method,
                body: new FormData(form),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(async response => {
                if (response.ok) {
                    alert("✅ Data berhasil disimpan!");
                    window.location.href = "{{ route('product-index') }}";
                } else {
                    const text = await response.text();
                    console.error(text);
                    alert("❌ Terjadi kesalahan saat menyimpan data.");
                }
            })
            .catch(error => {
                console.error(error);
                alert("❌ Gagal terhubung ke server.");
            });

            return false;
        }
    </script>
</x-app-layout>
