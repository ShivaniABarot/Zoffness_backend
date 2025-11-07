<?php

namespace App\Http\Controllers;

use App\Models\CollageEssaysPackage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class CollageEssaysPackageController extends Controller
{
    // Show all packages
    public function index()
    {
        $CollageEssaysPackage = CollageEssaysPackage::all();
        return view('collage_essays_packages.index', compact('CollageEssaysPackage'));
    }

    // Get Data for DataTables
    public function getData(Request $request)
    {
        $packages = CollageEssaysPackage::query();

        return DataTables::of($packages)
            ->addIndexColumn()
            ->addColumn('created_at', function($package) {
                return $package->created_at ? Carbon::parse($package->created_at)->format('m-d-Y') : '';
            })
            ->addColumn('status', function($package) {
                $checked = $package->status === 'active' ? 'checked' : '';
                return '
                    <label class="switch">
                        <input type="checkbox" '.$checked.' onchange="toggleStatus('.$package->id.', \''.$package->status.'\')">
                        <span class="slider"></span>
                    </label>
                ';
            })
            ->addColumn('actions', function($package) {
                return '
                    <a href="'.route('collage_essays_packages.show', $package->id).'" class="btn btn-sm me-1" title="View">
                        <i class="bx bx-show"></i>
                    </a>
                    <a href="'.route('collage_essays_packages.edit', $package->id).'" class="btn btn-sm me-1" title="Edit">
                        <i class="bx bx-edit"></i>
                    </a>
                    <button type="button" class="btn btn-sm" onclick="deletePackage('.$package->id.')" title="Delete">
                        <i class="bx bx-trash"></i>
                    </button>
                ';
            })
            ->rawColumns(['status', 'actions'])
            ->make(true);
    }

    // Show form to create a new package
    public function create()
    {
        return view('collage_essays_packages.create');
    }

    // Store a new package
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        CollageEssaysPackage::create([
            'name' => $request->name,
            'price' => $request->price,
            'number_of_sessions' => $request->number_of_sessions ?? 0,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('collage_essays_packages.index')
                         ->with('success', 'Collage Essays package created successfully.');
    }

    // Show the form to edit an existing package
    public function edit($id)
    {
        $CollageEssaysPackage = CollageEssaysPackage::findOrFail($id);
        return view('collage_essays_packages.edit', compact('CollageEssaysPackage'));
    }

    // Update the package
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->route('collage_essays_packages.edit', $id)
                             ->withErrors($validator)
                             ->withInput();
        }

        $package = CollageEssaysPackage::findOrFail($id);
        $package->name = $request->name;
        $package->price = $request->price;
        $package->description = $request->description;
        $package->status = $request->status;
        $package->save();

        return redirect()->route('collage_essays_packages.index')
                         ->with('success', 'Collage Essays package updated successfully.');
    }

    // Show package details
    public function show($id)
    {
        $CollageEssaysPackage = CollageEssaysPackage::findOrFail($id);
        return view('collage_essays_packages.view', compact('CollageEssaysPackage'));
    }

    // Delete a package
    public function destroy($id)
    {
        $CollageEssaysPackage = CollageEssaysPackage::findOrFail($id);
        $CollageEssaysPackage->delete();

        return response()->json([
            'success' => true,
            'message' => 'Collage Essays package deleted successfully.',
        ]);
    }

    // API: Get all active packages
    public function get_CollageEssaysPackage()
    {
        $CollageEssaysPackage = CollageEssaysPackage::where('status', 'active')->get();
        return response()->json([
            'success' => true,
            'data' => $CollageEssaysPackage
        ], 200);
    }

    // Optional: Toggle status via API
    public function toggleStatus($id)
    {
        $package = CollageEssaysPackage::findOrFail($id);
        $package->status = $package->status === 'active' ? 'inactive' : 'active';
        $package->save();

        return response()->json([
            'success' => true,
            'message' => 'Package status updated successfully.',
            'status' => $package->status
        ]);
    }
}
