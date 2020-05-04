<?php

namespace App\Http\Controllers;

use App\Question;
use App\Http\Requests\AskQuestionRequest;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth',['except' => ['index','show']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questions = Question::with('user')->Latest()->paginate(5);

        return view('questions.index',compact('questions'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $question = new Question();
        return view('questions.create',compact('question'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AskQuestionRequest $request)
    {
        $request->user()->questions()->create($request->only('title','body'));
        return redirect()->route('questions.index')->with('success',"Success! your data inserted");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        $question ->increment('views');
        return view('questions.show',compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        $this->authorize("update",$question);
        // if(\Gate::allows('update-question',$question)){
        //     return view("questions.edit",compact('question'));
        // }
        // abort(403,"Access Denied");
        return view("questions.edit",compact('question'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(AskQuestionRequest $request, Question $question)
    {
        $this->authorize("update",$question);
        $question->update($request->only('title','body'));
        return redirect('/questions')->with('success',"your questions has been updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        $this->authorize("delete",$question);
            // if(\Gate::allows('delete-question',$question)){

            //     abort(403,"Access Denied");
            // }
            $question->delete();

        // $question->delete();
        return redirect('/questions')->with('success','Your question has been deleted..!');
    }
}
