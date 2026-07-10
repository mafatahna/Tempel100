# TEMPEL 100

Pastebin sederhana tanpa database untuk menempel dan berbagi kode.

## Versi 1.1

- Pestebin tanpa database
- Tampilan compact dengan Bootstrap 5
- Font **Source Code Pro** untuk area kode
- Penomoran URL otomatis mulai dari 100 hingga 999
- Arsip otomatis saat melewati angka 999

## Daftar Perbaikan (v1.1)

### Bug fungsional

- **Penomoran folder** — Perbaikan logika `scandir()[0]+1` yang bisa menghasilkan nomor salah; sekarang scan folder numerik di `t/` (100–999) dan ambil nomor berikutnya
- **Path direktori** — Perbaikan pengecekan `is_dir($dir)` yang salah path; folder dibuat di `t/<nomor>/` dengan benar
- **Direktori `t/`** — Dibuat otomatis saat pertama kali dipakai (tidak perlu setup manual folder 100)
- **Redirect setelah tempel** — Ganti meta refresh rusak (`=0`) dan `include` file user dengan redirect HTTP 303 ke halaman hasil
- **Arsip & reset 999** — Saat nomor berikutnya melewati 999, seluruh isi `t/` dipindah ke `arsip/<timestamp>/` lalu nomor direset ke 100

### Keamanan

- **XSS** — Escape output `$judul` dan `$kode` dengan `htmlspecialchars()`
- **Eksekusi PHP** — Hapus `include` pada file hasil tempel user untuk mencegah eksekusi kode PHP

### Tampilan

- Layout card compact dengan Bootstrap 5 (`form-control-sm`, `btn-sm`)
- Font Source Code Pro untuk textarea kode dan blok `<pre><code>` hasil tempel
- Halaman hasil tempel (`t/<nomor>/index.html`) konsisten dengan halaman utama

## Cara Pakai

1. Deploy `index.php`, `gaya.css`, dan pastikan server mendukung PHP
2. Pastikan direktori root dapat ditulis (untuk membuat `t/`, `arsip/`, dan `acc-log.txt`)
3. Buka halaman utama, isi judul dan kode, lalu klik **Tempel**
4. Hasil tempel tersedia di `t/100/index.html` (nomor naik otomatis)

## Struktur Direktori

```
/
├── index.php       # Halaman utama & proses tempel
├── gaya.css        # Gaya compact & font kode
├── acc-log.txt     # Log IP akses (otomatis)
├── t/              # Folder tempel aktif (100–999)
└── arsip/          # Arsip saat reset setelah 999
```

## Catatan

- URL tempel mulai dari **100**, setelah **999** seluruh file diarsipkan dan link direset ke **100**
- Butuh koneksi internet untuk CDN Bootstrap dan Google Fonts (Source Code Pro)
