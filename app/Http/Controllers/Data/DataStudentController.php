<?php

namespace App\Http\Controllers\Data;

use Exception;
use App\Models\StudentData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class DataStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = StudentData::query()->get();

        return view('student-data.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('student-data.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:student_data',
            'number' => 'required|max:8',
            'start_year' => 'required|min:2|max:2',
            'major' => 'required'
        ]);

        StudentData::create([
            'name' => $request->name,
            'email' => $request->email,
            'number' => $request->number,
            'start_year' => $request->start_year,
            'major' => $request->major
        ]);

        flash()->success('Student added successfully!');

        return redirect()->route('student.data');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $data = StudentData::findOrFail($id);
            return view('student-data.edit', compact('data'));
        } catch (Exception $e) {
            flash()->error($e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Use validate and exclude the current record's email from the unique check
        $validated = $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:student_data,email,' . $id,
            'number' => 'required|max:8',
            'start_date' => 'required|min:2|max:2',
            'major' => 'required'
        ]);

        StudentData::find($id)->update($validated);

        flash()->success('Student updated successfully!');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Use findOrFail to handle non-existent records more gracefully
            $student = StudentData::findOrFail($id);

            $student->delete();

            flash()->success('Data deleted successfully!');

            return redirect()->back();
        } catch (Exception $e) {
            // Handle the exception and provide an error message
            flash()->error($e->getMessage());
            // Optionally, log the exception for debugging
            Log::error('Error deleting student data: ' . $e->getMessage());

            return redirect()->back();
        }
    }
}
