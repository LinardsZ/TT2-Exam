<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Education;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class EducationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        $education = new Education;
        $education->institution = $request->institution;
        $education->program = $request->program;
        $education->startyear = $request->startyear;
        if($request->filled('endyear')) {
            $education->endyear = $request->endyear;
        }

         $id = Auth::id();
        $education->userid = $id;
        $education->save();

        $user = Auth::user();
        $id = Auth::id();
        $data = DB::table('users')->select('userid', 'firstname', 'surname', 'email', 'has_company')->where('userid', '=', $id)->first();

        $education = DB::table('education')->join('users', 'education.userid', '=', 'users.userid')
        ->select('eduid', 'institution', 'startyear', 'endyear', 'program')->where('education.userid', '=', $id)->get();

        $experience = DB::table('experience')->join('users', 'users.userid', '=', 'experience.userid')
        ->select('expid', 'workplace', 'startyear', 'endyear', 'position')->where('experience.userid', '=', $id)->get();

        $temp = User::find(Auth::id());
        if($temp->userrole == 2) {
            $userrole = $temp->userrole;
            return view('user_profile', compact('data', 'education', 'experience', 'userrole'));
        }
        return view('user_profile', compact('data', 'education', 'experience'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
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
        $edu = DB::table('education')->select('userid', 'eduid', 'institution', 'program', 'startyear', 'endyear')->where('eduid', '=', $id)->first();
        
        if(Auth::id() != $edu->userid && !Gate::allows('isAdmin', auth()->user())) {
            abort(403);
        }

        return view('edit_education', compact('edu'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {   
        $rules = [
            'institution' => ['nullable', 'max:50'],
            'program' => ['nullable', 'max:50'],
            'startyear' => ['nullable', 'integer', 'between:1980,2022'],
            'endyear' => ['nullable', 'integer', 'between:1980,2022']
        ];

        $request->validate($rules);
        $edu = Education::find($request->eduid);
        $userid = $edu->userid;
        if(Auth::id() != $edu->userid && !Gate::allows('isAdmin', auth()->user())) {
            abort(403);
        }

        
        if(filled($request->institution)) $edu->institution = $request->institution;
        if(filled($request->program)) $edu->program = $request->program;
        if(filled($request->startyear)) $edu->startyear = $request->startyear;
        if(filled($request->endyear)) $edu->endyear = $request->endyear;
        $edu->save();

        $data = DB::table('users')->select('userid', 'firstname', 'surname', 'email')->where('userid', '=', $userid)->first();

        $education = DB::table('education')->join('users', 'education.userid', '=', 'users.userid')
        ->select('eduid', 'institution', 'startyear', 'endyear', 'program')->where('education.userid', '=', $userid)->get();

        $experience = DB::table('experience')->join('users', 'users.userid', '=', 'experience.userid')
        ->select('expid', 'workplace', 'startyear', 'endyear', 'position')->where('experience.userid', '=', $userid)->get();

        $temp = User::find(Auth::id());
        if($temp->userrole == 2 && Auth::id() != $userid) {
            return redirect()->route('admin.showuser', $userid);
        }

        return view('user_profile', compact('data', 'education', 'experience'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        $userid = DB::table('education')->select('userid')->where('eduid', '=', $id)->first();
        Education::destroy($id);

        $data = DB::table('users')->select('userid', 'firstname', 'surname', 'email')->where('userid', '=', $userid->userid)->first();

        $education = DB::table('education')->join('users', 'education.userid', '=', 'users.userid')
        ->select('eduid', 'institution', 'startyear', 'endyear', 'program')->where('education.userid', '=', $userid->userid)->get();

        $experience = DB::table('experience')->join('users', 'users.userid', '=', 'experience.userid')
        ->select('expid', 'workplace', 'startyear', 'endyear', 'position')->where('experience.userid', '=', $userid->userid)->get();

        $temp = User::find(Auth::id());
        if($temp->userrole == 2 && Auth::id() != $userid->userid) {
            return redirect()->route('admin.showuser', $userid->userid);
        }
        return view('user_profile', compact('data', 'education', 'experience'));
    }
}
