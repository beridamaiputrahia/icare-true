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
                'file_path'  => $file->store('albums/photos', 'public'),
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

        Storage::disk('public')->delete($photo->file_path);
        $photo->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
    }

    public function download(Photo $photo)
    {
        // Use response()->download() to avoid calling on the Filesystem contract
        $path = Storage::disk('public')->path($photo->file_path);

        return response()->download($path, basename($photo->file_path));
    }

    public function updateCaption(Request $request, Photo $photo)
    {
        $this->authorize('delete', $photo);

        $request->validate(['caption' => 'nullable|string|max:255']);
        $photo->update(['caption' => $request->input('caption')]);

        return response()->json(['success' => true]);
    }
}
