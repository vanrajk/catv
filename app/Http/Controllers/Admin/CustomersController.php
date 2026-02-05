<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use App\Models\Customers;
    use App\Models\Zone;
    use App\Models\Area;
    use App\Models\Site;
    use Illuminate\Http\Request;

    class CustomersController extends Controller
    {
     public function index(Request $request)
{
    $query = Customers::with(['zone','area','site']);

    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('name', 'like', "%{$request->search}%")
              ->orWhere('account_number', 'like', "%{$request->search}%")
              ->orWhere('mobile', 'like', "%{$request->search}%")
              ->orWhere('customer_number', 'like', "%{$request->search}%");
        });
    }

    if ($request->zone_id) {
        $query->where('zone_id', $request->zone_id);
    }

    if ($request->area_id) {
        $query->where('area_id', $request->area_id);
    }

    $zones = Zone::all();
    $sites = Site::all();

    // ✅ SERVER-SIDE PAGINATION
    $perPage = $request->per_page ?? 10;
    
    // ✅ Check if it's an AJAX request for pagination
    if ($request->ajax()) {
        $customers = $query->paginate($perPage);
        
        return response()->json([
            'html' => view('admin.customers.partials.table-rows', compact('customers'))->render(),
            'pagination' => view('admin.customers.partials.pagination', compact('customers'))->render(),
            'info' => "Showing {$customers->firstItem()} to {$customers->lastItem()} of {$customers->total()} entries"
        ]);
    }

    $customers = $query->paginate($perPage);
    
    return view('admin.customers.index', compact('customers', 'zones', 'sites'));
}
       public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'account_number' => 'required|unique:customers,account_number',
        'mobile' => 'required|digits_between:10,15',
        'customer_number' =>
            'required',
        
        'zone_id' => 'required|exists:zones,id',
        'area_id' => 'required|exists:areas,id',
        'site_id' => 'required|exists:sites,id',
    ], [
        'customer_number.unique' => 'This customer number already exists in the selected Zone and Area.'
    ]);

    $customer = Customers::create($validated);

    // ✅ Return JSON for AJAX requests
    if ($request->expectsJson()) {
        return response()->json([
            'status' => true,
            'success' => true,
            'message' => 'Customer added successfully',
            'customer' => $customer->load(['zone', 'area', 'site'])
        ]);
    }

    // ✅ Traditional redirect for non-AJAX
    return redirect()->back()->with('success', 'Customer added successfully');
}
     public function update(Request $request, Customers $customer)  // Changed from $Customers to $customer
{
    $request->validate([
        'customer_number' => 'required',
        'name' => 'required|string|max:255',
        'account_number' => 'required', 
        'mobile' => 'required|digits_between:10,15',
        'zone_id' => 'required|exists:zones,id',
        'area_id' => 'required|exists:areas,id',
        'site_id' => 'required|exists:sites,id',
    ]);

    $customer->update($request->only([  // Changed from $Customers to $customer
        'customer_number',
        'name',
        'account_number',
        'mobile',
        'zone_id',
        'area_id',
        'site_id',
    ]));

    // ✅ AJAX REQUEST
    if ($request->expectsJson()) {
        return response()->json([
            'status' => true,
            'customer' => $customer->load(['zone', 'area', 'site']),  // Changed from $Customers
        ]);
    }

    // ✅ NORMAL FORM REQUEST
    return redirect()->back()->with('success', 'Customer updated successfully');
}
        public function destroy(Customers $Customers)
        {
            $Customers->delete();

            return redirect()->back()->with('success', 'Customers deleted successfully');
        }

        public function editData(Customers $customer)
{
    return response()->json($customer->load(['zone', 'area']));
}
public function getNextCustomerNumber(Request $request)
{
    $zoneId = $request->zone_id;
    $areaId = $request->area_id;

    if (!$zoneId || !$areaId) {
        return response()->json(['customer_number' => '']);
    }

    // ✅ ORDER BY CAST for proper numerical sorting
    $lastCustomer = Customers::where('zone_id', $zoneId)
                             ->where('area_id', $areaId)
                             ->orderByRaw('CAST(customer_number AS UNSIGNED) DESC')
                             ->first();

    if ($lastCustomer) {
        // Remove any leading zeros and increment
        $customerNumber = (int)$lastCustomer->customer_number + 1;
        
        // Pad with zeros to maintain format (e.g., 001, 002, 010, 025)
    } else {
        $customerNumber = '1';
    }

    return response()->json(['customer_number' => $customerNumber]);
}
    }
