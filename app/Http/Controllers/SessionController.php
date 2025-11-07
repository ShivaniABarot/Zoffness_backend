<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;


class SessionController extends Controller
{
    // list 
    public function index()
    {
        return view('sessions.index');
    }

    // Fetch sessions for DataTable
   
    public function getSessions(Request $request)
{
    if ($request->ajax()) {
        try {
            $sessions = Session::select(['id','title','date','price_per_slot','status']);

            return DataTables::of($sessions)
                ->addIndexColumn() // DT_RowIndex
                ->addColumn('formatted_date', function($row) {
                    return $row->date ? date('m-d-Y', strtotime($row->date)) : '';
                })
                ->addColumn('formatted_price', function($row) {
                    return '$' . number_format($row->price_per_slot, 2);
                })
                ->addColumn('status', function($row) {
                    $status = $row->status ?? 'in-active';
                    return '<label class="switch">
                                <input type="checkbox" '.($status === 'active' ? 'checked' : '').'
                                    onchange="toggleStatus('.$row->id.', \''.$status.'\')">
                                <span class="slider"></span>
                            </label>';
                })
                ->addColumn('actions', function($row){
                    $view = '<a href="'.route('sessions.show', $row->id).'" class="btn btn-sm"><i class="bx bx-show"></i></a>';

                    $edit = '<a href="'.route('sessions.edit', $row->id).'" class="btn btn-sm"><i class="bx bx-edit"></i></a> ';

                    $delete = '<button class="btn btn-sm" onclick="deleteSession('.$row->id.')"><i class="bx bx-trash"></i></button>';
                    return $view . $edit . $delete;
                })
                ->rawColumns(['status', 'actions'])
                ->make(true); // must use make(true) for DataTables JSON
        } catch (\Exception $e) {
            // return proper JSON so DataTables doesn't break
            return response()->json([
                'data' => [],
                'error' => $e->getMessage(),
            ]);
        }
    }
}

    



    // create new session 
    public function create()
    {
        return view('sessions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'price_per_slot' => 'required|numeric',
            'date' => 'required|date',
        ]);
    
        Session::create([
            'title' => $request->title,
            'price_per_slot' => $request->price_per_slot,
            'date' => $request->date,
            'status' => $request->status ?? 'active',
        ]);
    
        return redirect()->route('sessions.index') // correct route
            ->with('success', 'Session created successfully.');
    }
    

    // edit and update 
    public function edit($id)
    {
        $session = Session::findOrFail($id);
        return view('sessions.edit', compact('session'));
    }

    // Update the specified user (update)
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'price_per_slot' => 'required|numeric',
            'date' => 'required|date',
            'status' => 'required|in:active,in-active',
        ]);
    
        $session = Session::findOrFail($id);
        $session->title = $request->title;
        $session->price_per_slot = $request->price_per_slot;
        $session->date = $request->date;
        $session->status = $request->status;
        $session->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Session updated successfully.'
        ]);
    }
    

    public function show($id)
{
    $session = Session::findOrFail($id);
    return view('sessions.view', compact('session'));
}

    
    //ACTIVE INACTIVE BUTTON FUNCTION
    public function toggleStatus($id)
    {
        $session = Session::findOrFail($id);
        $session->status = $session->status === 'active' ? 'in-active' : 'active';
        $session->save();

        return response()->json([
            'success' => true,
            'message' => 'Session status updated successfully.',
            'new_status' => $session->status
        ]);
    }


    // Remove the specified user (delete)
    public function destroy($id)
    {
        // Find the session by its ID
        $session = Session::findOrFail($id);

        // Delete the session
        $session->delete();

        // Return a JSON response for AJAX
        return response()->json([
            'success' => true,
            'message' => 'Session deleted successfully.',
        ]);
    }

    // GET SESSIONS API
    public function get_sessions()
    {
        $sessions = Session::where('status', 'active')->get();
        return response()->json([
            'success' => true,
            'data' => $sessions
        ], 200);
    }



}
