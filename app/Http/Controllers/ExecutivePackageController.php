<?php

namespace App\Http\Controllers;

use App\Models\ExecutivePackage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;

use Yajra\DataTables\Facades\DataTables; // Make sure you have yajra/laravel-datatables installed

class ExecutivePackageController extends Controller
{
    // Show all packages
    public function index()
    {
        $ExecutivePackage = ExecutivePackage::all();
        return view('executive_function_packages.index', compact('ExecutivePackage'));
    }
    

    public function getData(Request $request)
    {
        $packages = ExecutivePackage::query();
    
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
                    <a href="'.route('executive_function_packages.show', $package->id).'" class="btn btn-sm me-1" title="View">
                        <i class="bx bx-show"></i>
                    </a>
                    <a href="'.route('executive_function_packages.edit', $package->id).'" class="btn btn-sm me-1" title="Edit">
                        <i class="bx bx-edit"></i>
                    </a>
                    <button type="button" class="btn btn-sm" onclick="deletePackage('.$package->id.')" title="Delete">
                        <i class="bx bx-trash"></i>
                    </button>
                ';
            })
            ->rawColumns(['status','actions'])
            ->make(true);
    }
    

    // Show form to create a new package
    public function create()
    {
        return view('executive_function_packages.create');
    }

    // Store a new package
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        ExecutivePackage::create([
            'name' => $request->name,
            'price' => $request->price,
            'number_of_sessions' => $request->number_of_sessions ?? 0,
            'status' => $request->status,
        ]);

        return redirect()->route('executive_function_packages.index')
                         ->with('success', 'Executive admissions package created successfully.');
    }

    // Show the form to edit an existing package
    public function edit($id)
    {
        $ExecutivePackage = ExecutivePackage::findOrFail($id);
        return view('executive_function_packages.edit', compact('ExecutivePackage'));
    }

    // Update the package
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->route('executive_function_packages.edit', $id)
                             ->withErrors($validator)
                             ->withInput();
        }

        $package = ExecutivePackage::findOrFail($id);
        $package->name = $request->name;
        $package->price = $request->price;
        $package->status = $request->status;
        $package->save();

        return redirect()->route('executive_function_packages.index')
                         ->with('success', 'Executive admissions package updated successfully.');
    }

    // Show package details
    public function show($id)
    {
        $ExecutivePackage = ExecutivePackage::findOrFail($id);
        return view('executive_function_packages.view', compact('ExecutivePackage'));
    }

    // Delete a package
    public function destroy($id)
    {
        $ExecutivePackage = ExecutivePackage::findOrFail($id);
        $ExecutivePackage->delete();

        return response()->json([
            'success' => true,
            'message' => 'Executive admissions package deleted successfully.',
        ]);
    }

    // API: Get all packages
    public function get_ExecutivePackage()
    {
        $ExecutivePackage = ExecutivePackage::where('status', 'active')->get();
        return response()->json([
            'success' => true,
            'data'    => $ExecutivePackage
        ], 200);

        // $ExecutivePackage = ExecutivePackage::all();
        // return response()->json([
        //     'success' => true,
        //     'data' => $ExecutivePackage
        // ], 200);
    }

    // Optional: Toggle status via API
    public function toggleStatus($id)
    {
        $package = ExecutivePackage::findOrFail($id);
        $package->status = $package->status === 'active' ? 'inactive' : 'active';
        $package->save();

        return response()->json([
            'success' => true,
            'message' => 'Package status updated successfully.',
            'status' => $package->status
        ]);
    }
}
