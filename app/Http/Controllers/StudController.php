<?php

namespace Fakultet\Http\Controllers;

use Fakultet\Mjesto;
use Fakultet\Stud;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use View;

class StudController extends Controller {

    public function studloc($pbr) {
        $m = new Mjesto;
        $os = $m->find($pbr)->student_rod->first();

        echo "<h2>Prvi student iz mjesta "
        . $m->find($pbr)->nazMjesto
        . " sa matičnim br:"
        . $os->mbrStud
        . "</h2>";
        echo $os->imeStud . " " . $os->prezStud;

        $svios = $m->find($pbr)->student_rod->toArray();

        echo "<h2>Svi studenti iz " . $m->find($pbr)->nazMjesto . " ARRAY</h2>";
        echo "<ol>";
        foreach ($svios as $oosj) {
            echo "<li>" . $oosj['imeStud']
            . " "
            . $oosj['prezStud'] . "</li>";
        }
        echo "</ol>";

        echo "<h2>Svi studenti iz " . $m->find($pbr)->nazMjesto . " DIREKTNO</h2>";

        $svios = $m->find($pbr)->student_rod;
        echo "<ol>";
        foreach ($svios as $oosj) {
            printf("<li>%s %s </li>", $oosj->imeStud, $oosj->prezStud);
        }
        echo "</ol>";
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        return Stud::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        // Dohvati studenta sa primarnim ključem npr:
        // http://localhost:8000/studenti/1120
        $s = Stud::find($id);
        //return $s->mjesto_rod->nazMjesto;
        return View::make('fakultet.student.show')
                        ->with('student', $s);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $student = Stud::find($id);

        // Pokazi formu za editiranje studenata
        return View::make('fakultet.student.edit')
                        ->with('student', $student);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id) {
        // validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'mbrStud' => 'required|numeric',
            'imeStud' => 'required',
            'prezStud' => 'required',
            'pbrRod' => 'required|numeric',
            'pbrStan' => 'required|numeric',
            'datRodStud' => 'required|date|date_format:Y-n-j',
            'jmbgStud' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('studenti/' . $id . '/edit')
                            ->withErrors($validator)
                            ->withInput(Input::except('password'));
        } else {
            // store
            $student = Stud::find($id);
            $student->mbrStud = Input::get('mbrStud');
            $student->imeStud = Input::get('imeStud');
            $student->prezStud = Input::get('prezStud');
            $student->pbrRod = Input::get('pbrRod');
            $student->pbrStan = Input::get('pbrStan');
            $student->datRodStud = Input::get('datRodStud');
            $student->jmbgStud = Input::get('jmbgStud');
            $student->slikaStud = Input::get('slikaStud');



            $student->save();

            // redirect
            Session::flash('message', 'Uspjesno uređen student!');
            return Redirect::to('studenti');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        //
    }

}
