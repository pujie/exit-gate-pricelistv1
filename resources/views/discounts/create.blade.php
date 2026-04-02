<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Promo Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-8 border border-gray-100">
                
                <form action="{{ route('discounts.store') }}" method="POST" x-data="discountForm()">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <x-input-label for="name" :value="__('Nama Kampanye Diskon')" />
                            <x-text-input id="name" name="name" class="block mt-1 w-full" type="text" required />
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="start_date" :value="__('Mulai')" />
                                <x-text-input id="start_date" name="start_date" class="block mt-1 w-full" type="datetime-local" required />
                            </div>
                            <div>
                                <x-input-label for="end_date" :value="__('Berakhir')" />
                                <x-text-input id="end_date" name="end_date" class="block mt-1 w-full" type="datetime-local" required />
                            </div>
                        </div>
                    </div>

                    <hr class="my-8 border-gray-100">

                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-800 italic">Daftar Produk Terasosiasi</h3>
                            <button type="button" @click="addRow()" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                + Tambah Produk
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50 text-gray-700 uppercase text-xs font-bold">
                                    <tr>
                                        <th class="px-4 py-3">Pilih Produk</th>
                                        <th class="px-4 py-3">Tipe Diskon</th>
                                        <th class="px-4 py-3">Nilai (Angka)</th>
                                        <th class="px-4 py-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(row, index) in rows" :key="index">
                                        <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                                            <td class="px-2 py-3">
                                                <select :name="`products[${index}][id]`" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                                    <option value="">-- Pilih Produk --</option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-2 py-3">
                                                <select :name="`products[${index}][type]`" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                                    <option value="percentage">Persentase (%)</option>
                                                    <option value="fixed_amount">Rupiah (Rp)</option>
                                                </select>
                                            </td>
                                            <td class="px-2 py-3">
                                                <input type="number" :name="`products[${index}][value]`" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="0" required>
                                            </td>
                                            <td class="px-2 py-3 text-center">
                                                <button type="button" @click="removeRow(index)" class="text-red-500 hover:text-red-700 font-bold">
                                                    Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="flex justify-end mt-10">
                        <x-primary-button class="px-8 py-3">
                            {{ __('Simpan Kampanye Diskon') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        function discountForm() {
            return {
                rows: [{ id: '', type: 'percentage', value: '' }], // Baris pertama default
                addRow() {
                    this.rows.push({ id: '', type: 'percentage', value: '' });
                },
                removeRow(index) {
                    if (this.rows.length > 1) {
                        this.rows.splice(index, 1);
                    }
                }
            }
        }
    </script>
</x-app-layout>