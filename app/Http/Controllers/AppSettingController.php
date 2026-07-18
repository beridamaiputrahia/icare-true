<?php

namespace App\Http\Controllers;

use App\Managers\SettingsManager;
use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AppSettingController extends Controller
{
    public function __construct(private SettingsManager $settings) {}

    public function index()
    {
        $groups = AppSetting::orderBy('group')->orderBy('sort_order')->get()->groupBy('group');
        return view('settings.index', compact('groups'));
    }

    public function update(Request $request)
    {
        // Ambil setting milik tenant yang sedang login (BelongsToTenant scope aktif)
        $settings = AppSetting::orderBy('sort_order')->get();

        foreach ($settings as $setting) {
            $key = $setting->key;

            if ($setting->type === 'image') {
                if ($request->hasFile($key)) {
                    if ($setting->value) {
                        Storage::disk('public')->delete($setting->value);
                    }
                    $path = $request->file($key)->store('settings', 'public');
                    // Update langsung di row yang sudah diketahui (hindari updateOrCreate ambiguity)
                    $setting->update(['value' => $path]);
                    $this->settings->set($key, $path);
                }
                continue;
            }

            if ($setting->type === 'boolean') {
                $value = $request->boolean($key) ? '1' : '0';
                $setting->update(['value' => $value]);
                $this->settings->set($key, $value);
                continue;
            }

            if ($request->has($key)) {
                $value = $request->input($key);
                $setting->update(['value' => $value]);
                $this->settings->set($key, $value);
            }
        }

        $this->settings->flush();

        return redirect()->route('settings.index')
            ->with('success', 'Pengaturan berhasil disimpan.');
    }

    public function maintenanceToggle(Request $request)
    {
        $current = $this->settings->isMaintenanceMode();
        $this->settings->set('maintenance_mode', $current ? '0' : '1');
        $this->settings->flush();

        $status = $current ? 'dinonaktifkan' : 'diaktifkan';
        return back()->with('success', "Mode maintenance berhasil {$status}.");
    }
}
