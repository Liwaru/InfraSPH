<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ajukan Permintaan | InfraSPH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/views/ajukan_permintaan.css') }}">
</head>
<body>
    <div class="app-shell" id="appShell">
        @include('header')

        <main class="request-page">
            <div class="page-shell">
                <section class="hero-card">
                    <div class="eyebrow">{{ $dashboard['role_name'] ?? 'Pengguna' }}</div>
                    <h1 class="hero-title">Ajukan Permintaan</h1>
                    <p class="hero-subtitle">
                        Buat pengajuan kebutuhan kelas dengan alur yang jelas. Pilih jenis permintaan, isi alasan secara singkat, lalu kirim untuk diproses melalui alur Ketua Kelas → Wali Kelas → Kepala Sekolah → Pengelola Sistem.
                    </p>
                </section>

                <div class="feedback-stack">
                    @if (session('success'))
                        <div class="success-banner">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="error-banner">{{ session('error') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="error-banner">
                            Ada beberapa bagian yang perlu diperiksa lagi. Pastikan semua data penting sudah terisi.
                        </div>
                    @endif
                </div>

                <section class="section-grid">
                    <article class="panel-card">
                        <h2 class="section-title">Pilih Jenis Permintaan</h2>
                        <div class="type-grid">
                            <label class="type-card">
                                <input type="radio" name="request_type_display" value="barang_baru" {{ old('request_type', 'barang_baru') === 'barang_baru' ? 'checked' : '' }}>
                                <span class="type-body">
                                    <span class="type-icon"><i class="bi bi-plus-square-fill"></i></span>
                                    <span>
                                        <span class="type-title">Ajukan Barang Baru</span>
                                        <span class="type-copy">Gunakan untuk mengajukan kebutuhan barang tambahan yang belum tersedia atau jumlahnya kurang di kelas.</span>
                                    </span>
                                </span>
                            </label>

                            <label class="type-card">
                                <input type="radio" name="request_type_display" value="perbaikan" {{ old('request_type') === 'perbaikan' ? 'checked' : '' }}>
                                <span class="type-body">
                                    <span class="type-icon"><i class="bi bi-tools"></i></span>
                                    <span>
                                        <span class="type-title">Ajukan Perbaikan</span>
                                        <span class="type-copy">Gunakan untuk melaporkan barang inventaris kelas yang rusak dan membutuhkan tindak lanjut perbaikan.</span>
                                    </span>
                                </span>
                            </label>
                        </div>
                    </article>

                    <div class="info-grid">
                        <article class="info-card">
                            <div class="info-label">Ruangan</div>
                            <div class="info-value">{{ $assignment->nama_ruangan }} ({{ $assignment->kode_ruangan }})</div>
                        </article>
                        <article class="info-card">
                            <div class="info-label">Pengaju</div>
                            <div class="info-value">{{ $user['nama'] }}</div>
                        </article>
                        <article class="info-card">
                            <div class="info-label">Tanggal</div>
                            <div class="info-value">{{ $todayLabel }}</div>
                        </article>
                    </div>

                    <form method="POST" action="{{ route('requests.store') }}" class="form-grid" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="request_type" id="requestTypeInput" value="{{ old('request_type', 'barang_baru') }}">

                        <article class="panel-card">
                            <h2 class="section-title">Form Permintaan</h2>

                            <div class="field-grid hidden-section {{ old('request_type', 'barang_baru') === 'barang_baru' ? 'active' : '' }}" id="barangBaruSection">
                                <div class="field">
                                    <label for="new_item_id">Nama Barang</label>
                                    <select name="new_item_id" id="new_item_id">
                                        <option value="" disabled @selected(old('new_item_id') === null || old('new_item_id') === '')>Pilih barang yang ingin diajukan</option>
                                        @foreach ($availableItems as $item)
                                            <option value="{{ $item->id_barang }}" data-satuan="{{ $item->satuan }}" {{ (string) old('new_item_id') === (string) $item->id_barang ? 'selected' : '' }}>
                                                {{ $item->nama_barang }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('new_item_id')
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                    @error('item_selection')
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="field-row">
                                    <div class="field">
                                        <label for="quantity">Jumlah</label>
                                        <input type="number" min="1" name="quantity" id="quantity" value="{{ old('quantity') }}" placeholder="Contoh: 1">
                                        @error('quantity')
                                            <div class="field-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="field">
                                        <label for="priority">Prioritas</label>
                                        <select name="priority" id="priority">
                                            <option value="" disabled @selected(old('priority') === null || old('priority') === '')>Pilih prioritas</option>
                                            <option value="biasa" {{ old('priority') === 'biasa' ? 'selected' : '' }}>Biasa</option>
                                            <option value="mendesak" {{ old('priority') === 'mendesak' ? 'selected' : '' }}>Mendesak</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="field">
                                    <label for="reason">Alasan Pengajuan</label>
                                    <textarea name="reason" id="reason" placeholder="Jelaskan kenapa barang ini dibutuhkan di kelasmu...">{{ old('reason') }}</textarea>
                                    <div class="field-help">Tuliskan alasan yang jelas agar pengajuan lebih mudah ditinjau oleh wali kelas.</div>
                                    @error('reason')
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="field-grid hidden-section {{ old('request_type') === 'perbaikan' ? 'active' : '' }}" id="perbaikanSection">
                                <div class="field">
                                    <label for="repair_item_id">Pilih Barang Inventaris</label>
                                    <select name="repair_item_id" id="repair_item_id">
                                        <option value="" disabled @selected(old('repair_item_id') === null || old('repair_item_id') === '')>Pilih barang yang ingin diperbaiki</option>
                                        @foreach ($roomInventory as $item)
                                            <option value="{{ $item->id_barang }}" data-satuan="{{ $item->satuan }}" {{ (string) old('repair_item_id') === (string) $item->id_barang ? 'selected' : '' }}>
                                                {{ $item->nama_barang }} (Baik: {{ $item->jumlah_baik }}, Rusak: {{ $item->jumlah_rusak }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('repair_item_id')
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                    @error('item_selection')
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="field-row">
                                    <div class="field">
                                        <label for="repair_quantity">Jumlah Rusak</label>
                                        <input type="number" min="1" name="quantity" id="repair_quantity" value="{{ old('quantity') }}" placeholder="Contoh: 1">
                                        @error('quantity')
                                            <div class="field-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="field">
                                        <label for="damage_level">Tingkat Kerusakan</label>
                                        <select name="damage_level" id="damage_level">
                                            <option value="" disabled @selected(old('damage_level') === null || old('damage_level') === '')>Pilih tingkat kerusakan</option>
                                            <option value="ringan" {{ old('damage_level') === 'ringan' ? 'selected' : '' }}>Ringan</option>
                                            <option value="sedang" {{ old('damage_level') === 'sedang' ? 'selected' : '' }}>Sedang</option>
                                            <option value="berat" {{ old('damage_level') === 'berat' ? 'selected' : '' }}>Berat</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="field">
                                    <label for="repair_reason">Deskripsi Kerusakan</label>
                                    <textarea name="reason" id="repair_reason" placeholder="Jelaskan kerusakan yang terjadi pada barang tersebut...">{{ old('reason') }}</textarea>
                                    <div class="field-help">Contoh: kaki meja patah, baut kursi lepas, atau permukaan papan tulis sudah tidak layak.</div>
                                    @error('reason')
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="field">
                                    <label for="damage_photo">Foto Kerusakan</label>
                                    <input type="file" name="damage_photo" id="damage_photo" accept="image/*,.heic,.heif,.tif,.tiff">
                                    <div class="field-help">Unggah foto barang rusak. Format gambar umum didukung, maksimal 5 MB.</div>
                                    @error('damage_photo')
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="button-row">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send-check-fill"></i>
                                    Kirim Pengajuan
                                </button>
                                <a href="{{ route('class.inventory') }}" class="btn btn-secondary btn-cancel-request">
                                    <i class="bi bi-x-circle"></i>
                                    Batal
                                </a>
                            </div>
                        </article>

                        <aside class="preview-card">
                            <h2 class="section-title">Ringkasan</h2>
                            <div class="preview-list">
                                <div class="preview-item">
                                    <div class="preview-label">Jenis Permintaan</div>
                                    <div class="preview-value" id="previewType">Barang Baru</div>
                                </div>
                                <div class="preview-item">
                                    <div class="preview-label">Barang</div>
                                    <div class="preview-value" id="previewItem">Belum dipilih</div>
                                </div>
                                <div class="preview-item">
                                    <div class="preview-label">Jumlah</div>
                                    <div class="preview-value" id="previewQuantity">{{ old('quantity') ?: '-' }}</div>
                                </div>
                                <div class="preview-item">
                                    <div class="preview-label">Keterangan</div>
                                    <div class="preview-value" id="previewReason">{{ old('reason') ?: 'Isi alasan atau deskripsi agar ringkasan tampil di sini.' }}</div>
                                </div>
                                <div class="preview-item" id="previewPhotoWrap" class="is-hidden">
                                    <div class="preview-label">Foto</div>
                                    <div class="preview-value" id="previewPhoto">Belum dipilih</div>
                                </div>
                            </div>

                        </aside>
                    </form>
                </section>
            </div>
        </main>
    </div>
    @include('chatbot')

    <script>
        (function () {
            const typeInputs = document.querySelectorAll('input[name="request_type_display"]');
            const requestTypeInput = document.getElementById('requestTypeInput');
            const barangBaruSection = document.getElementById('barangBaruSection');
            const perbaikanSection = document.getElementById('perbaikanSection');
            const newItemSelect = document.getElementById('new_item_id');
            const repairItemSelect = document.getElementById('repair_item_id');
            const quantityInput = document.getElementById('quantity');
            const repairQuantityInput = document.getElementById('repair_quantity');
            const reasonInput = document.getElementById('reason');
            const repairReasonInput = document.getElementById('repair_reason');
            const damagePhotoInput = document.getElementById('damage_photo');
            const previewType = document.getElementById('previewType');
            const previewItem = document.getElementById('previewItem');
            const previewQuantity = document.getElementById('previewQuantity');
            const previewReason = document.getElementById('previewReason');
            const previewPhotoWrap = document.getElementById('previewPhotoWrap');
            const previewPhoto = document.getElementById('previewPhoto');
            const cancelButton = document.querySelector('.btn-cancel-request');

            function activeType() {
                const checked = Array.from(typeInputs).find(function (input) {
                    return input.checked;
                });

                return checked ? checked.value : 'barang_baru';
            }

            function updateSections() {
                const type = activeType();
                requestTypeInput.value = type;
                barangBaruSection.classList.toggle('active', type === 'barang_baru');
                perbaikanSection.classList.toggle('active', type === 'perbaikan');
                barangBaruSection.querySelectorAll('input, select, textarea').forEach(function (element) {
                    element.disabled = type !== 'barang_baru';
                });
                perbaikanSection.querySelectorAll('input, select, textarea').forEach(function (element) {
                    element.disabled = type !== 'perbaikan';
                });
                previewType.textContent = type === 'barang_baru' ? 'Barang Baru' : 'Perbaikan';
                updatePreview();
            }

            function selectedOptionText(selectElement) {
                if (!selectElement) {
                    return 'Belum dipilih';
                }

                const option = selectElement.options[selectElement.selectedIndex];
                return option && option.value ? option.text : 'Belum dipilih';
            }

            function updatePreview() {
                const type = activeType();
                const quantityValue = type === 'barang_baru'
                    ? (quantityInput?.value || '-')
                    : (repairQuantityInput?.value || '-');
                const reasonValue = type === 'barang_baru'
                    ? (reasonInput?.value || 'Isi alasan atau deskripsi agar ringkasan tampil di sini.')
                    : (repairReasonInput?.value || 'Isi alasan atau deskripsi agar ringkasan tampil di sini.');

                previewItem.textContent = type === 'barang_baru'
                    ? selectedOptionText(newItemSelect)
                    : selectedOptionText(repairItemSelect);
                previewQuantity.textContent = quantityValue;
                previewReason.textContent = reasonValue;

                if (previewPhotoWrap && previewPhoto) {
                    previewPhotoWrap.style.display = type === 'perbaikan' ? '' : 'none';
                    previewPhoto.textContent = damagePhotoInput?.files?.[0]?.name || 'Belum dipilih';
                }
            }

            typeInputs.forEach(function (input) {
                input.addEventListener('change', updateSections);
            });

            [newItemSelect, repairItemSelect, quantityInput, repairQuantityInput, reasonInput, repairReasonInput, damagePhotoInput].forEach(function (element) {
                if (!element) {
                    return;
                }

                element.addEventListener('input', updatePreview);
                element.addEventListener('change', updatePreview);
            });

            if (cancelButton) {
                cancelButton.addEventListener('click', function (event) {
                    const confirmed = window.confirm('Batalkan pengajuan ini dan kembali ke halaman Kelas Saya?');

                    if (!confirmed) {
                        event.preventDefault();
                    }
                });
            }

            updateSections();
        })();
    </script>
</body>
</html>

