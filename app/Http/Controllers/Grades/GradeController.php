<?php

namespace App\Http\Controllers\Grades;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGrade;
use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Models\Grade;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Grade::all();
        return view('pages.grades.index', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGrade $request)
    {


        if(Grade::where('Name->ar', $request->Name)->orWhere('Name->en', $request->Name_en)->exists())
        {
            return redirect()->back()->withErrors(trans('Grades_trans.exists'));
        }

        try
        {
            $validated = $request->validated();
            $grade = new Grade;

            $grade->Name = [ 'en' => $request->Name_en, 'ar' => $request->Name];
            $grade->Notes = $request->Notes;
            $grade->save();

            toastr()->success(trans('messages.success'));
            return redirect()->route('grades.index');
        }

        catch (\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreGrade $request, $id)
    {
        try
        {
            $validated = $request->validated();

            $grade =  Grade::findOrFail($request->id);

            $grade->update([

                $grade->Name    = [ 'en' => $request->Name_en, 'ar' => $request->Name],
                $grade->Notes   = $request->Notes

            ]);


            $grade->save();

            toastr()->success(trans('messages.Update'));
            return redirect()->route('grades.index');
        }

        catch (\Exception $e)
        {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $MyClass_id = Classroom::where('Grade_id',$request->id)->pluck('Grade_id');

        if($MyClass_id->count() == 0){

            $Grades = Grade::findOrFail($request->id)->delete();
            toastr()->error(trans('messages.Delete'));
            return redirect()->route('grades.index');
        }

        else{

            toastr()->error(trans('Grades_trans.delete_Grade_Error'));
            return redirect()->route('grades.index');

        }
    }
}
