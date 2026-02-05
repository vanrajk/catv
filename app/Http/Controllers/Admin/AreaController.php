<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Zone;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::with('zone')->latest()->get();
        $zones = Zone::orderBy('name')->get();

        return view('admin.areas.index', compact('areas', 'zones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'name'    => 'required|string|max:255',
        ]);

        Area::create($request->only('zone_id', 'name'));

        return back()->with('success', 'Area added successfully');
    }

    public function update(Request $request, Area $area)
    {
        $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'name'    => 'required|string|max:255',
        ]);

        $area->update($request->only('zone_id', 'name'));

        return back()->with('success', 'Area updated successfully');
    }

    public function destroy(Area $area)
    {
        $area->delete();
        return back()->with('success', 'Area deleted successfully');
    }

    public function byZone($zoneId)
{
    return Area::where('zone_id', $zoneId)
        ->orderBy('name')
        ->get(['id', 'name']);
}
}
