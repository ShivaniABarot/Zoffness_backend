<?php

    namespace App\Http\Controllers;

    use App\Models\CollageEssaysPackage;
    use App\Models\Session;
    use Illuminate\Support\Facades\Validator;

    use Illuminate\Http\Request;

    class CollageEssaysPackageController extends Controller
    {
        // Show all timeslots
        public function index()
        {
            $CollageEssaysPackage = CollageEssaysPackage::all(); // Removed 'session' relationship
            return view('collage_essays_packages.index', compact('CollageEssaysPackage'));
        }
        

        // Show form to create a new timeslot
        public function create()
        {
            $CollageEssaysPackage = CollageEssaysPackage::all(); // Fetch available sessions
            return view('collage_essays_packages.create', compact('CollageEssaysPackage'));
        }

        // Store a new timeslot
        public function store(Request $request)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'description' => 'required|nullable|string', // Allow HTML content
            ]);
    
            CollageEssaysPackage::create([
                'name' => $request->name,
                'price' => $request->price,
                'number_of_sessions' => $request->number_of_sessions,
                'description' => $request->description, // Save HTML content
            ]);
    
            return redirect()->route('collage_essays_packages.index')->with('success', 'Collage Essays packages created successfully.');
        }

        // Show the form to edit an existing timeslot
        public function edit($id)
        {
            $CollageEssaysPackage = CollageEssaysPackage::findOrFail($id);
            return view('collage_essays_packages.edit', compact('CollageEssaysPackage'));
        }
        

        // Update the timeslot

        public function update(Request $request, $id)
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'description' => 'required|nullable|string', // Allow HTML content
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
            $package->save();
    
            return redirect()->route('collage_essays_packages.index')->with('success', 'Collage Essays Package updated successfully.');
        }
        public function show($id)
        {
            $CollageEssaysPackage = CollageEssaysPackage::findOrFail($id);
            // dd( $CollageEssaysPackage);
            return view('collage_essays_packages.view', compact('CollageEssaysPackage'));
        }
        

        // Delete a timeslot
        public function destroy($id)
        {
            $CollageEssaysPackage = CollageEssaysPackage::findOrFail($id);
            $CollageEssaysPackage->delete();
        
            return response()->json([
                'success' => true,
                'message' => 'Collage Essays package deleted successfully.',
            ]);
        }

        public function get_CollageEssaysPackage()
        {
            $CollageEssaysPackage = CollageEssaysPackage::all();
            return response()->json([
                'success' => true,
                'data' => $CollageEssaysPackage
            ], 200);
        }
    
    }
