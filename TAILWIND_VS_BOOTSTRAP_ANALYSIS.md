# Analisis Penggunaan Tailwind vs Bootstrap

## Status Saat Ini

### Tailwind CSS
**Status:** ✅ Terpasang tapi TIDAK DIGUNAKAN di Views

- **package.json:** `tailwindcss@^4.0.0` dan `@tailwindcss/vite@^4.0.0` sudah terpasang
- **resources/css/app.css:** Sudah import Tailwind dengan `@import 'tailwindcss'`
- **Vite config:** Sudah disetup dengan Laravel Vite plugin
- **app.css @source directives:** Sudah pointing ke blade.php files

**TAPI:** Tidak ada satupun Tailwind classes di dalam views (riwayatTransaksi, barang, dll)

### Bootstrap & Custom CSS
**Status:** ❌ TIDAK terpasang di package.json tapi DIGUNAKAN di Views

- **Tidak ada di dependencies** - Bootstrap bukan NPM dependency
- **Yang digunakan:** Custom CSS classes yang didefinisikan langsung di `<style>` block dalam view files
- **Contoh classes:** `card`, `filter-section`, `filter-group`, `btn-detail`, `btn-edit`, `action-buttons`, `empty-state`, dll.
- **Styling approach:** Inline `<style>` tags di setiap blade.php file

## Temuan

### Kenapa Tailwind di TSD tapi tidak digunakan?

1. **Setup tapi tidak digunakan** - Tailwind sudah dikonfigurasi tapi views menggunakan custom CSS classes
2. **Build proses** - Vite build mungkin belum menghasilkan compiled CSS
3. **Development stage** - Mungkin plan awalnya pakai Tailwind, tapi implementasi menggunakan custom CSS

### Struktur Styling Saat Ini

```
Application Structure:
├── resources/css/app.css (Tailwind imported tapi tidak dipakai)
├── resources/views/
│   ├── admin/
│   │   ├── riwayatTransaksi/
│   │   │   └── index.blade.php (CSS dalam <style> tag)
│   │   └── barang/
│   │       └── index.blade.php (CSS dalam <style> tag)
│   └── owner/
│       └── ... (semua menggunakan <style> tag)
└── Tidak ada public/css/ folder dengan compiled CSS
```

## Rekomendasi untuk TSD Update

### Pilihan 1: Migrate ke Tailwind (Lebih modern)
- ✅ Lebih scalable & maintainable
- ✅ Sudah terpasang, tinggal digunakan
- ✅ Lebih ringan untuk production
- ⏱️ Effort: Sedang (refactor semua views)

### Pilihan 2: Keep current custom CSS (Cepat)
- ✅ Sudah berjalan, tidak perlu perubahan
- ✅ Custom classes lebih mudah dipahami
- ❌ Tidak optimal untuk maintenance
- ⏱️ Effort: Minimal

## Kesimpulan untuk TSD

**Update TSD:** 

Di bagian "Technical Stack" → CSS Framework:
- **Status saat ini:** "Custom CSS dengan inline styles di views"
- **Tersedia:** Tailwind CSS v4 (belum digunakan)
- **Rekomendasi:** 
  - Dalam jangka pendek: Keep current custom CSS
  - Dalam jangka panjang: Migrate ke Tailwind CSS (sudah terinstall)

Jadi statement "Tailwind CSS dipake" di TSD tidak sepenuhnya akurat - lebih tepat adalah "Custom CSS dengan beberapa utility approaches".
