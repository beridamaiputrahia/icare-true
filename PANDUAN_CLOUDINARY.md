# Setup Cloudinary untuk Foto Profil & Upload File (Render)

## Kenapa perlu ini
Render free tier tidak punya persistent disk — folder `storage/app/public`
di dalam container di-reset setiap kali ada redeploy. Semua foto yang
di-upload user (foto profil, foto member, gambar pengumuman/renungan, cover
album) akan hilang dan menampilkan broken image seperti yang terjadi
sebelumnya. Cloudinary menyimpan file di cloud eksternal, jadi aman
walau container di-redeploy kapan pun — dan gratis untuk penggunaan skala
kecil-menengah (25 GB storage + 25 GB bandwidth/bulan di free tier).

## Langkah setup

### 1. Daftar akun Cloudinary
1. Buka https://cloudinary.com/users/register/free, daftar gratis.
2. Setelah login, buka **Dashboard** (halaman utama setelah login).
3. Cari bagian **API Environment variable** — akan terlihat seperti:
   ```
   CLOUDINARY_URL=cloudinary://123456789012345:AbCdEfGhIjKlMnOpQrStUvWxYz@dxxxxxxxx
   ```
   Salin seluruh baris ini (atau catat 3 bagian: API Key, API Secret, Cloud Name).

### 2. Set environment variable di Render
1. Buka dashboard Render > service `icare-true` > Environment Variables.
2. Tambahkan/ubah:
   - `FILESYSTEM_DISK` = `cloudinary` (kalau belum ada, tambahkan baru)
   - `CLOUDINARY_URL` = tempel URL lengkap dari langkah 1 (termasuk prefix `cloudinary://`)
3. Simpan — Render akan otomatis redeploy.

### 3. Test upload
Setelah redeploy selesai:
1. Login ke aplikasi, buka halaman **Profil**.
2. Upload foto profil baru, simpan.
3. Refresh halaman — foto harus tampil (bukan broken image lagi).
4. Buka Cloudinary Dashboard > **Media Library** — file yang baru di-upload
   akan terlihat di sana, menandakan sudah tersimpan di cloud, bukan lagi
   di disk lokal container.

## Catatan tentang foto yang sudah ada sebelumnya
Foto-foto yang di-upload SEBELUM perubahan ini (misalnya waktu masih pakai
disk lokal) sudah hilang permanen dari container — tidak bisa dipulihkan
otomatis. User perlu upload ulang foto profil/foto lain yang sempat hilang.
Setelah ini, semua upload baru akan permanen tersimpan di Cloudinary dan
tidak akan hilang lagi walau ada redeploy berikutnya.

## Free tier limits Cloudinary
- 25 GB storage, 25 GB bandwidth/bulan — cukup untuk ratusan hingga ribuan
  foto ukuran wajar. Kalau nanti mendekati limit, Cloudinary akan
  memberi notifikasi email sebelum ada masalah.
