<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    public function index()
    {
        $zones = Zone::latest()->get();
        return view('admin.zones.index', compact('zones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Zone::create($request->only('name'));

        return redirect()->back()->with('success', 'Zone added successfully');
    }

    public function update(Request $request, Zone $zone)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $zone->update($request->only('name'));

        return redirect()->back()->with('success', 'Zone updated successfully');
    }

    public function destroy(Zone $zone)
    {
        $zone->delete();
        return redirect()->back()->with('success', 'Zone deleted successfully');
    }
}
