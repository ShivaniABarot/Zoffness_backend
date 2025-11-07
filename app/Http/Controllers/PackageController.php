<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class PackageController extends Controller
{
  
    public function index()
    {
        return view('packages.index');
    }

    public function getPackages(Request $request)
    {
        if ($request->ajax()) {
            try {
                $packages = Package::select(['id', 'name', 'price', 'description', 'status']);

                return DataTables::of($packages)
                    ->addIndexColumn()
                    ->addColumn('formatted_price', function ($row) {
                        return '$' . number_format($row->price, 2);
                    })
                    ->addColumn('status', function ($row) {
                        $status = $row->status ?? 'in-active';
                        return '<label class="switch">
                                    <input type="checkbox" '.($status === 'active' ? 'checked' : '').'
                                        onchange="toggleStatus('.$row->id.', \''.$status.'\')">
                                    <span class="slider"></span>
                                </label>';
                    })
                    ->addColumn('actions', function ($row) {
                        $view = '<a href="' . route('packages.show', $row->id) . '" class="btn btn-sm"><i class="bx bx-show"></i></a>';
                        $edit = '<a href="' . route('packages.edit', $row->id) . '" class="btn btn-sm"><i class="bx bx-edit"></i></a>';
                        $delete = '<button class="btn btn-sm" onclick="deletePackage(' . $row->id . ')"><i class="bx bx-trash"></i></button>';
                        return $view . $edit . $delete;
                    })
                    ->rawColumns(['status', 'actions'])
                    ->make(true);
            } catch (\Exception $e) {
                return response()->json([
                    'data' => [],
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

   
    public function create()
    {
        return view('packages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status'      => 'required|in:active,in-active',
        ]);

        Package::create([
            'name'        => $request->name,
            'price'       => $request->price,
            'description' => $request->description,
            'status'      => $request->status,
        ]);

        return redirect()->route('packages.index')
            ->with('success', 'Package created successfully.');
    }

   
    public function edit($id)
    {
        $package = Package::findOrFail($id);
        return view('packages.edit', compact('package'));
    }

       public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status'      => 'required|in:active,in-active',
        ]);

        $package = Package::findOrFail($id);
        $package->name        = $request->name;
        $package->price       = $request->price;
        $package->description = $request->description;
        $package->status      = $request->status;
        $package->save();

        return response()->json([
            'success' => true,
            'message' => 'Package updated successfully.'
        ]);
    }

  
    public function show($id)
    {
        $package = Package::findOrFail($id);
        return view('packages.view', compact('package'));
    }

   
    public function toggleStatus($id)
    {
        $package = Package::findOrFail($id);
        $package->status = $package->status === 'active' ? 'in-active' : 'active';
        $package->save();

        return response()->json([
            'success' => true,
            'message' => 'Package status updated successfully.',
            'new_status' => $package->status
        ]);
    }

  
    public function destroy($id)
    {
        $package = Package::findOrFail($id);
        $package->delete();

        return response()->json([
            'success' => true,
            'message' => 'Package deleted successfully.'
        ]);
    }

    // 📱 API: Get All Packages
    public function get_packages()
    {
        $packages = Package::where('status', 'active')->get();
        return response()->json([
            'success' => true,
            'data'    => $packages
        ], 200);
    }

    // 📅 API: Get Dates (Next 3 Months Saturdays)
    public function get_dates(Request $request)
    {
        $startDate = Carbon::now();
        $endDate = Carbon::now()->addMonths(3);

        $saturdays = [];
        $current = $startDate->copy()->next(Carbon::SATURDAY)->setTime(9, 0, 0);

        while ($current->lte($endDate)) {
            $saturdays[] = $current->toDateTimeString();
            $current->addWeek();
        }

        return response()->json([
            'success'   => true,
            'saturdays' => $saturdays
        ]);
    }
}
