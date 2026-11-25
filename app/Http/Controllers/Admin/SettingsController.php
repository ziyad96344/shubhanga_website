<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public const GROUPS = ['store' => 'Store and shipping', 'contact' => 'Contact details', 'social' => 'Social links'];

    public function index()
    {
        return view('admin.settings', [
            'settings' => Setting::orderBy('sort_order')->get()->groupBy('group'),
            'groups' => self::GROUPS,
        ]);
    }

    public function update(Request $request)
    {
        $rules = [];
        foreach (Setting::DEFINITIONS as $fields) {
            foreach ($fields as $key => [$label, $type]) {
                $rules[$key] = match ($type) {
                    'number' => ['nullable', 'integer', 'min:0', 'max:100000'],
                    'email' => ['nullable', 'email', 'max:160'],
                    'url' => ['nullable', 'url', 'max:255'],
                    default => ['nullable', 'string', 'max:1000'],
                };
            }
        }

        foreach ($request->validate($rules) as $key => $value) {
            Setting::where('key', $key)->update(['value' => (string) $value]);
        }
        Setting::flush();

        return back()->with('success', 'Settings saved. The website uses them straight away.');
    }
}
