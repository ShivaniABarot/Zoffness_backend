<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SAT_ACT_Packages;
use App\Models\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class Satact_packagesController extends Controller
{
    // List view
    public function index()
    {
        return view('satact_course.index');
    }

    // Fetch packages for DataTable
    public function getPackages(Request $request)
    {
        if ($request->ajax()) {
            try {
                $packages = SAT_ACT_Packages::select(['id','name','price','description','status']);

                return DataTables::of($packages)
                    ->addIndexColumn()
                    ->addColumn('formatted_price', function($row){
                        return '$' . number_format($row->price, 2);
                    })
                    ->addColumn('status', function($row){
                        $status = $row->status ?? 'in-active';
                        return '<label class="switch">
                                    <input type="checkbox" '.($status === 'active' ? 'checked' : '').'
                                        onchange="toggleStatus('.$row->id.', \''.$status.'\')">
                                    <span class="slider"></span>
                                </label>';
                    })
                    ->addColumn('actions', function($row){
                        $view = '<a href="'.route('satact_course.show', $row->id).'" class="btn btn-sm"><i class="bx bx-show"></i></a>';
                        $edit = '<a href="'.route('satact_course.edit', $row->id).'" class="btn btn-sm"><i class="bx bx-edit"></i></a> ';
                        $delete = '<button class="btn btn-sm" onclick="deletePackage('.$row->id.')"><i class="bx bx-trash"></i></button>';
                        return $view . $edit . $delete;
                    })
                    ->rawColumns(['status','actions'])
                    ->make(true);

            } catch (\Exception $e) {
                return response()->json([
                    'data' => [],
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    // Show create form
    public function create()
    {
        return view('satact_course.create');
    }

    // Store package
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:active,in-active',
        ]);

        SAT_ACT_Packages::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('satact_course.index')
            ->with('success', 'SAT-ACT Package created successfully.');
    }

    // Show edit form
    public function edit($id)
    {
        $package = SAT_ACT_Packages::findOrFail($id);
        return view('satact_course.edit', compact('package'));
    }

    // Update package
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:active,in-active',
        ]);

        $package = SAT_ACT_Packages::findOrFail($id);
        $package->name = $request->name;
        $package->price = $request->price;
        $package->description = $request->description;
        $package->status = $request->status;
        $package->save();

        return response()->json([
            'success' => true,
            'message' => 'SAT-ACT Package updated successfully.'
        ]);
    }

    // Show package
    public function show($id)
{
    $package = SAT_ACT_Packages::findOrFail($id); // use $package, not $SAT_ACT_Packages
    return view('satact_course.view', compact('package'));
}

    

    // Toggle active/in-active status
    public function toggleStatus($id)
    {
        $package = SAT_ACT_Packages::findOrFail($id);
        $package->status = $package->status === 'active' ? 'in-active' : 'active';
        $package->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Package status updated successfully.',
            'new_status' => $package->status
        ]);
    }
    

    // Delete package
    public function destroy($id)
    {
        $package = SAT_ACT_Packages::findOrFail($id);
        $package->delete();

        return response()->json([
            'success' => true,
            'message' => 'SAT ACT Package deleted successfully.'
        ]);
    }

    // API: Get all packages
    public function get_sat_act_packages()
    {

        $packages = SAT_ACT_Packages::where('status', 'active')->get();
        return response()->json([
            'success' => true,
            'data' => $packages
        ], 200);

        // $packages = SAT_ACT_Packages::all();
        // return response()->json([
        //     'success' => true,
        //     'data' => $packages
        // ], 200);
    }
}
