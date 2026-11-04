<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::query()->first();

        return view('settings.index', compact('setting'));
    }

    public function create()
    {
        $setting = Setting::query()->first();

        return view('settings.create', compact('setting'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);
        $setting = Setting::query()->first() ?? new Setting();

        $this->persistSetting($request, $setting, $validated);

        return redirect()
            ->route('settings.index')
            ->with('success', 'Settings saved successfully.');
    }

    public function show(Setting $setting)
    {
        return redirect()->route('settings.edit', $setting);
    }

    public function edit(Setting $setting)
    {
        return view('settings.create', compact('setting'));
    }

    public function update(Request $request, Setting $setting)
    {
        $validated = $this->validateData($request);

        $this->persistSetting($request, $setting, $validated);

        return redirect()
            ->route('settings.index')
            ->with('success', 'Settings updated successfully.');
    }

    public function destroy(Setting $setting)
    {
        if ($setting->logo && Storage::disk('public')->exists($setting->logo)) {
            Storage::disk('public')->delete($setting->logo);
        }

        $setting->delete();

        return redirect()
            ->route('settings.index')
            ->with('success', 'Settings removed successfully.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'business_title' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile_number' => 'required|string|max:30',
            'address' => 'required|string|max:1000',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'invoice_type' => 'required|in:thermal,a5',
            'tax_included' => 'nullable|boolean',
            'remove_logo' => 'nullable|boolean',
        ]);
    }

    private function persistSetting(Request $request, Setting $setting, array $validated): void
    {
        if ($request->boolean('remove_logo') && $setting->logo && Storage::disk('public')->exists($setting->logo)) {
            Storage::disk('public')->delete($setting->logo);
            $validated['logo'] = null;
        }

        if ($request->hasFile('logo')) {
            if ($setting->logo && Storage::disk('public')->exists($setting->logo)) {
                Storage::disk('public')->delete($setting->logo);
            }

            $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9\.\-_]/', '', $request->file('logo')->getClientOriginalName());
            $validated['logo'] = $request->file('logo')->storeAs('settings', $fileName, 'public');
        } else {
            unset($validated['logo']);
        }

        unset($validated['remove_logo']);
        $validated['tax_included'] = $request->boolean('tax_included');

        $setting->fill($validated);
        $setting->save();
    }
}
