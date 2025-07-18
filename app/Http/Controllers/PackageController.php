<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class PackageController extends Controller
{
    // List function
    public function index()
    {
        $packages = Package::all();
        return view('packages.index', compact('packages'));
    }

    // create new package 
    public function create()
    {
        return view('packages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            // 'number_of_sessions' => 'required|integer|min:1',
            'description' => 'nullable|string', // Allow HTML content
        ]);

        Package::create([
            'name' => $request->name,
            'price' => $request->price,
            // 'number_of_sessions' => $request->number_of_sessions,
            'description' => $request->description, // Save HTML content
        ]);

        return redirect()->route('packages.index')->with('success', 'Package created successfully.');
    }

    public function edit($id)
    {
        $package = Package::findOrFail($id);
        return view('packages.edit', compact('package'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            // 'number_of_sessions' => 'required|integer|min:1',
            'description' => 'nullable|string', 
        ]);

        if ($validator->fails()) {
            return redirect()->route('packages.edit', $id)
                             ->withErrors($validator)
                             ->withInput();
        }

        $package = Package::findOrFail($id);
        $package->name = $request->name;
        $package->price = $request->price;
        // $package->number_of_sessions = $request->number_of_sessions;
        $package->description = $request->description;
        $package->save();

        return redirect()->route('packages.index')->with('success', 'Package updated successfully.');
    }

    public function show($id)
    {
        $package = Package::findOrFail($id); 
        // dd($package);
        return view('packages.view', compact('package'));
    }

    public function destroy($id)
    {
        // Find the session by its ID
        $package = Package::findOrFail($id);
    
        // Delete the session
        $package->delete();
    
        // Return a JSON response for AJAX
        return response()->json([
            'success' => true,
            'message' => 'Package deleted successfully.',
        ]);
    }

    // GET PACKAGES API
    public function get_packages()
{
    $packages = Package::all();
    return response()->json([
        'success' => true,
        'data' => $packages
    ], 200);
}

// GET DATES API ADDED BY SHIVANI 18/7
public function get_dates(Request $request)
{
    $startDate = Carbon::now();
    $endDate = Carbon::now()->addMonths(3);

    $saturdays = [];

    // Set to the next Saturday from today
    $current = $startDate->copy()->next(Carbon::SATURDAY)->setTime(9, 0, 0);

    while ($current->lte($endDate)) {
        $saturdays[] = $current->toDateTimeString(); // Format: YYYY-MM-DD HH:MM:SS
        $current->addWeek(); // Move to next Saturday 9AM
    }

    return response()->json([
        'success' => true,
        'saturdays' => $saturdays
    ]);
}

}
