# Setup Pusher untuk Chat & Game Online Real-Time

## Kenapa perlu ini
Render free tier tidak bisa menjalankan proses WebSocket terpisah (Reverb),
jadi sebelumnya `BROADCAST_CONNECTION=log` — artinya event chat/game "dikirim"
tapi tidak pernah benar-benar sampai ke browser lawan bicara/lawan main
secara real-time. Pusher Channels adalah layanan WebSocket cloud pihak
ketiga dengan **free tier gratis selamanya** (bukan trial):
- 200.000 pesan/hari
- 100 koneksi bersamaan

Cukup untuk skala komunitas kecil-menengah tanpa perlu server sendiri.

## Langkah setup

### 1. Daftar akun Pusher
1. Buka https://pusher.com, daftar gratis (Sign Up).
2. Setelah login, klik **Channels** > **Create app**.
3. Isi nama app (misal `icare-true`), pilih cluster terdekat (misal
   **Asia Pacific (Singapore) - ap1** untuk Indonesia), pilih "Laravel"
   sebagai tech stack (opsional, cuma referensi kode).
4. Setelah app dibuat, buka tab **App Keys** — catat 4 nilai berikut:
   - `app_id`
   - `key`
   - `secret`
   - `cluster`

### 2. Set environment variable di Render
Buka dashboard Render > service `icare-true` > Environment Variables, isi:

```
BROADCAST_CONNECTION=pusher
PUSHER_APP_ID=<app_id dari Pusher>
PUSHER_APP_KEY=<key dari Pusher>
PUSHER_APP_SECRET=<secret dari Pusher>
PUSHER_APP_CLUSTER=<cluster dari Pusher, misal ap1>
```

Simpan — Render akan otomatis redeploy.

### 3. Test chat real-time
1. Login dengan 2 akun berbeda (misal 2 browser berbeda, atau mode
   Incognito untuk akun kedua).
2. Buka halaman **Chat** di kedua akun, mulai percakapan yang sama.
3. Kirim pesan dari akun A — pesan harus **langsung muncul** di akun B
   tanpa refresh halaman.
4. Coba juga indikator "sedang mengetik" — harus muncul di akun lawan
   saat salah satu mengetik.

### 4. Test game online
1. Dari akun A, buka **Game** > pilih game > mode **Online** > pilih
   lawan (akun B).
2. Di akun B (device/browser lain), notifikasi tantangan harus muncul
   otomatis tanpa refresh.
3. Terima tantangan, mainkan — skor & giliran harus sinkron real-time
   di kedua sisi.

## Kalau masih tidak real-time setelah setup ini
Buka DevTools (F12) > Console di kedua browser, cari pesan error terkait
Pusher (misal "Pusher not available" atau error otentikasi channel). Kirim
pesan errornya untuk didiagnosis lebih lanjut — kemungkinan cluster salah,
atau kredensial belum tersimpan dengan benar (masalah serupa dengan
kasus `CLOUDINARY_URL` sebelumnya: pastikan tidak ada spasi/newline
tersembunyi saat mengisi value di Render).
