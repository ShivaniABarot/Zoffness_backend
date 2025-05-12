<?php

    namespace App\Http\Controllers;

    use App\Models\SAT_ACT_Packages;
    use App\Models\Session;
    use Illuminate\Support\Facades\Validator;

    use Illuminate\Http\Request;

    class Satact_packagesController extends Controller
    {
        // Show all timeslots
        public function index()
        {
            $SAT_ACT_Packages = SAT_ACT_Packages::with('session')->get();
            return view('satact_course.index', compact('SAT_ACT_Packages'));
        }

        // Show form to create a new timeslot
        public function create()
        {
            $SAT_ACT_Packages = SAT_ACT_Packages::all(); // Fetch available sessions
            return view('satact_course.create', compact('SAT_ACT_Packages'));
        }

        // Store a new timeslot
        public function store(Request $request)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                // 'number_of_sessions' => 'required|integer|min:1',
                'description' => 'nullable|string', // Allow HTML content
            ]);
    
            SAT_ACT_Packages::create([
                'name' => $request->name,
                'price' => $request->price,
                'number_of_sessions' => $request->number_of_sessions,
                'description' => $request->description, // Save HTML content
            ]);
    
            return redirect()->route('satact_course.index')->with('success', 'SAT-ACT Package created successfully.');
        }

        // Show the form to edit an existing timeslot
        public function edit($id)
        {
            $package = SAT_ACT_Packages::findOrFail($id);
            return view('satact_course.edit', compact('package'));
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
                return redirect()->route('satact_course.edit', $id)
                                 ->withErrors($validator)
                                 ->withInput();
            }
    
            $package = SAT_ACT_Packages::findOrFail($id);
            $package->name = $request->name;
            $package->price = $request->price;
            $package->description = $request->description;
            $package->save();
    
            return redirect()->route('satact_course.index')->with('success', 'SAT - ACT Package updated successfully.');
        }
        public function show($id)
        {
            // Fetch the timeslot with its associated session using Eloquent's relationship
            $SAT_ACT_Packages = SAT_ACT_Packages::findOrFail($id);
    
            return view('satact_course.view', compact('SAT_ACT_Packages'));
        }
        

        // Delete a timeslot
        public function destroy($id)
        {
            $SAT_ACT_Packages = SAT_ACT_Packages::findOrFail($id);
            $SAT_ACT_Packages->delete();
        
            return response()->json([
                'success' => true,
                'message' => 'SAT ACT Package deleted successfully.',
            ]);
        }
    
    }
