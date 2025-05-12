<?php

    namespace App\Http\Controllers;

    use App\Models\ExecutivePackage;
    use App\Models\Session;
    use Illuminate\Support\Facades\Validator;

    use Illuminate\Http\Request;

    class ExecutivePackageController extends Controller
    {
        // Show all timeslots
        public function index()
        {
            $ExecutivePackage = ExecutivePackage::all(); // Removed 'session' relationship
            return view('executive_function_packages.index', compact('ExecutivePackage'));
        }
        

        // Show form to create a new timeslot
        public function create()
        {
            $ExecutivePackage = ExecutivePackage::all(); // Fetch available sessions
            return view('executive_function_packages.create', compact('ExecutivePackage'));
        }

        // Store a new timeslot
        public function store(Request $request)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'description' => 'nullable|string', // Allow HTML content
            ]);
    
            ExecutivePackage::create([
                'name' => $request->name,
                'price' => $request->price,
                'number_of_sessions' => $request->number_of_sessions,
                'description' => $request->description, // Save HTML content
            ]);
    
            return redirect()->route('executive_function_packages.index')->with('success', 'Executive addmissions packages created successfully.');
        }

        // Show the form to edit an existing timeslot
        public function edit($id)
        {
            $ExecutivePackage = ExecutivePackage::findOrFail($id);
            return view('executive_function_packages.edit', compact('ExecutivePackage'));
        }
        

        // Update the timeslot

        public function update(Request $request, $id)
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'description' => 'nullable|string', // Allow HTML content
            ]);
    
            if ($validator->fails()) {
                return redirect()->route('executive_function_packages.edit', $id)
                                 ->withErrors($validator)
                                 ->withInput();
            }
    
            $package = ExecutivePackage::findOrFail($id);
            $package->name = $request->name;
            $package->price = $request->price;
            $package->description = $request->description;
            $package->save();
    
            return redirect()->route('executive_function_packages.index')->with('success', 'Executive admissions Package updated successfully.');
        }
        public function show($id)
        {
            // Fetch the timeslot with its associated session using Eloquent's relationship
            $ExecutivePackage = ExecutivePackage::findOrFail($id);
    
            return view('executive_function_packages.view', compact('ExecutivePackage'));
        }
        

        // Delete a timeslot
        public function destroy($id)
        {
            $ExecutivePackage = ExecutivePackage::findOrFail($id);
            $ExecutivePackage->delete();
        
            return response()->json([
                'success' => true,
                'message' => 'Executive addmissions package deleted successfully.',
            ]);
        }

        public function get_ExecutivePackage()
        {
            $ExecutivePackage = ExecutivePackage::all();
            return response()->json([
                'success' => true,
                'data' => $ExecutivePackage
            ], 200);
        }
    
    }
