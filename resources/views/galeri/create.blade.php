@extends('layouts.dashboard')

@section('title', 'Tambah Foto - Galeri Edu')

@section('content')
<div class="w-full">
    <div class="bg-white shadow-lg rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-800">Tambah Foto Baru</h2>
                <a href="{{ route('galeri.index') }}" class="text-slate-600 hover:text-slate-900">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        <div class="p-6">
            <!-- Error Messages -->
            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <div>
                            <p class="font-bold">Terdapat kesalahan:</p>
                            <ul class="list-disc list-inside mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('galeri.store') }}" enctype="multipart/form-data" class="space-y-6" id="galeriForm">
                @csrf
                
                <div>
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
                        Judul Foto <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="judul" 
                        id="judul" 
                        required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-600 focus:border-teal-600 @error('judul') border-red-500 @enderror"
                        placeholder="Masukkan judul foto"
                        value="{{ old('judul') }}"
                    >
                    @error('judul')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi
                    </label>
                    <textarea 
                        name="deskripsi" 
                        id="deskripsi" 
                        rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-600 focus:border-teal-600 @error('deskripsi') border-red-500 @enderror"
                        placeholder="Masukkan deskripsi foto (opsional)"
                    >{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="kategori_id" 
                        id="kategori_id" 
                        required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-600 focus:border-teal-600 @error('kategori_id') border-red-500 @enderror"
                    >
                        <option value="">Pilih kategori</option>
                        @foreach($kategori as $kat)
                            <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
                                {{ $kat->judul }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="fotos" class="block text-sm font-medium text-gray-700 mb-2">
                        Foto-foto <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="file" 
                        name="fotos[]" 
                        id="fotos" 
                        required 
                        accept="image/*"
                        multiple
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-600 focus:border-teal-600 @error('fotos') border-red-500 @enderror @error('fotos.*') border-red-500 @enderror"
                        onchange="previewImages(event)"
                    >
                    <p class="mt-1 text-sm text-gray-500">Pilih beberapa foto sekaligus. Format: JPG, PNG, GIF, WEBP. Maksimal 10MB per file.</p>
                    @error('fotos')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('fotos.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    
                    <!-- Preview Images Container -->
                    <div id="preview-container" class="mt-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" style="display: none;">
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('galeri.index') }}" class="bg-slate-500 hover:bg-slate-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function previewImages(event) {
    const files = event.target.files;
    const previewContainer = document.getElementById('preview-container');
    
    // Clear previous previews
    previewContainer.innerHTML = '';
    
    if (files.length > 0) {
        previewContainer.style.display = 'grid';
        
        Array.from(files).forEach((file, index) => {
            // Only show image files
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'relative group';
                    previewDiv.innerHTML = `
                        <img 
                            src="${e.target.result}" 
                            alt="Preview ${index + 1}"
                            class="w-full h-32 object-cover rounded-lg border border-gray-300 shadow-sm"
                        >
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 rounded-lg flex items-center justify-center">
                            <span class="text-white text-sm opacity-0 group-hover:opacity-100 transition-opacity">
                                Foto ${index + 1}
                            </span>
                        </div>
                    `;
                    previewContainer.appendChild(previewDiv);
                };
                
                reader.readAsDataURL(file);
            }
        });
    } else {
        previewContainer.style.display = 'none';
    }
}
</script>
@endsection
