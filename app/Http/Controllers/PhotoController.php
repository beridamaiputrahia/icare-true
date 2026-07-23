<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function store(Request $request, Album $album)
    {
        $this->authorize('create', Photo::class);

        // Jika ukuran total request melebihi post_max_size PHP, PHP mengosongkan
        // seluruh $_POST/$_FILES sebelum sampai ke Laravel -- tanpa pesan ini,
        // user hanya melihat "gagal validasi: photos wajib diisi" yang membingungkan.
        if (empty($request->file('photos')) && (int) $request->server('CONTENT_LENGTH') > 0) {
            $postMaxBytes = static::iniSizeToBytes(ini_get('post_max_size'));
            if ($postMaxBytes > 0 && (int) $request->server('CONTENT_LENGTH') > $postMaxBytes) {
                return back()->withErrors([
                    'photos' => 'Total ukuran foto yang diupload terlalu besar untuk sekali kirim. Coba upload lebih sedikit foto sekaligus.',
                ]);
            }
        }

        $request->validate([
            'photos'     => 'required|array|min:1|max:30',
            'photos.*'   => 'image|max:8192',
            'captions'   => 'nullable|array',
            'captions.*' => 'nullable|string|max:255',
        ]);

        $lastOrder = $album->photos()->max('sort_order') ?? -1;

        foreach ($request->file('photos') as $i => $file) {
            Photo::create([
                'album_id'   => $album->id,
                'user_id'    => auth()->id(),
                'file_path'  => $file->store('albums/photos', config('filesystems.default')),
                'caption'    => $request->input("captions.{$i}"),
                'file_size'  => $file->getSize(),
                'mime_type'  => $file->getMimeType(),
                'sort_order' => $lastOrder + $i + 1,
            ]);
        }

        $uploaded = count($request->file('photos'));

        return back()->with('success', "{$uploaded} foto berhasil diupload.");
    }

    public function destroy(Photo $photo)
    {
        $this->authorize('delete', $photo);

        Storage::disk(config('filesystems.default'))->delete($photo->file_path);
        $photo->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
    }

    public function download(Photo $photo)
    {
        // Stream lewat disk agar tetap bekerja untuk disk remote (Cloudinary/S3)
        // yang tidak punya path filesystem lokal.
        $disk = Storage::disk(config('filesystems.default'));

        return $disk->response($photo->file_path, basename($photo->file_path));
    }

    public function updateCaption(Request $request, Photo $photo)
    {
        $this->authorize('delete', $photo);

        $request->validate(['caption' => 'nullable|string|max:255']);
        $photo->update(['caption' => $request->input('caption')]);

        return response()->json(['success' => true]);
    }

    private static function iniSizeToBytes(string $value): int
    {
        $value = trim($value);
        $unit  = strtolower(substr($value, -1));
        $num   = (int) $value;

        return match ($unit) {
            'g' => $num * 1024 * 1024 * 1024,
            'm' => $num * 1024 * 1024,
            'k' => $num * 1024,
            default => $num,
        };
    }
}
