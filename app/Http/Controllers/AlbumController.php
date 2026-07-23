<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlbumController extends Controller
{
    public function index()
    {
        $albums = Album::published()
            ->with(['photos' => fn ($q) => $q->take(1)])
            ->withCount('photos')
            ->latest('tanggal_kegiatan')
            ->paginate(12);

        return view('albums.index', compact('albums'));
    }

    public function create()
    {
        $this->authorize('create', Album::class);

        return view('albums.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Album::class);

        // Jika ukuran total request melebihi post_max_size PHP, PHP mengosongkan
        // seluruh $_POST/$_FILES sebelum sampai ke Laravel -- termasuk field
        // "judul" yang wajib diisi -- sehingga tampak seperti reload tanpa
        // error yang jelas. Deteksi kasus ini dan beri pesan yang spesifik.
        if ($request->missing('judul') && (int) $request->server('CONTENT_LENGTH') > 0) {
            $postMaxBytes = static::iniSizeToBytes(ini_get('post_max_size'));
            if ($postMaxBytes > 0 && (int) $request->server('CONTENT_LENGTH') > $postMaxBytes) {
                return back()->withInput()->withErrors([
                    'photos' => 'Total ukuran foto yang diupload terlalu besar untuk sekali kirim. Buat album dulu tanpa foto, lalu upload foto lewat halaman album.',
                ]);
            }
        }

        $data = $request->validate([
            'judul'            => 'required|string|max:200',
            'deskripsi'        => 'nullable|string|max:1000',
            'cover'            => 'nullable|image|max:5120',
            'tanggal_kegiatan' => 'nullable|date',
            'is_published'     => 'boolean',
            'photos'           => 'nullable|array|max:30',
            'photos.*'         => 'image|max:8192',
            'captions'         => 'nullable|array',
            'captions.*'       => 'nullable|string|max:255',
        ]);

        $album = Album::create([
            'user_id'          => auth()->id(),
            'judul'            => $data['judul'],
            'deskripsi'        => $data['deskripsi'] ?? null,
            'tanggal_kegiatan' => $data['tanggal_kegiatan'] ?? null,
            'is_published'     => $request->boolean('is_published', true),
        ]);

        if ($request->hasFile('cover')) {
            $album->update(['cover' => $request->file('cover')->store('albums/covers', config('filesystems.default'))]);
        }

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $i => $file) {
                Photo::create([
                    'album_id'   => $album->id,
                    'user_id'    => auth()->id(),
                    'file_path'  => $file->store('albums/photos', config('filesystems.default')),
                    'caption'    => $data['captions'][$i] ?? null,
                    'file_size'  => $file->getSize(),
                    'mime_type'  => $file->getMimeType(),
                    'sort_order' => $i,
                ]);
            }

            // Auto-set first photo as cover if none given
            if (!$request->hasFile('cover')) {
                $first = $album->photos()->first();
                if ($first) $album->update(['cover' => $first->file_path]);
            }
        }

        return redirect()->route('albums.show', $album)
            ->with('success', 'Album berhasil dibuat.');
    }

    public function show(Album $album)
    {
        $this->authorize('view', $album);

        $photos = $album->photos;

        return view('albums.show', compact('album', 'photos'));
    }

    public function edit(Album $album)
    {
        $this->authorize('update', $album);

        return view('albums.edit', compact('album'));
    }

    public function update(Request $request, Album $album)
    {
        $this->authorize('update', $album);

        $data = $request->validate([
            'judul'            => 'required|string|max:200',
            'deskripsi'        => 'nullable|string|max:1000',
            'cover'            => 'nullable|image|max:5120',
            'tanggal_kegiatan' => 'nullable|date',
            'is_published'     => 'boolean',
        ]);

        if ($request->hasFile('cover')) {
            if ($album->cover) Storage::disk(config('filesystems.default'))->delete($album->cover);
            $data['cover'] = $request->file('cover')->store('albums/covers', config('filesystems.default'));
        }

        $album->update(array_merge($data, ['is_published' => $request->boolean('is_published', true)]));

        return redirect()->route('albums.show', $album)
            ->with('success', 'Album berhasil diperbarui.');
    }

    public function destroy(Album $album)
    {
        $this->authorize('delete', $album);

        // Delete all photos from storage
        foreach ($album->photos as $photo) {
            Storage::disk(config('filesystems.default'))->delete($photo->file_path);
        }
        if ($album->cover) Storage::disk(config('filesystems.default'))->delete($album->cover);

        $album->delete();

        return redirect()->route('albums.index')
            ->with('success', 'Album berhasil dihapus.');
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
