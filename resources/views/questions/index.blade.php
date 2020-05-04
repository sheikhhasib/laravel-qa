@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h2>All Questions</h2>
                        <div class="ml-auto">
                            <a href="{{ route('questions.create') }}" class="btn btn-outline-secondary">Ask Questions</a>
                        </div>
                    </div>

                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-info">
                            {{ session('success') }}
                        </div>
                    @endif
                    @foreach ($questions as $question)
                    <div class="media">
                        <div class="d-flex flex-column counters">
                            <div class="vote">
                                <strong>{{ $question->votes}}</strong>{{str_plural('vote',$question->votes) }}
                            </div>
                            <div class="status {{ $question->status }}">
                                <strong>{{ $question->answers }}</strong>{{ str_plural('answer',$question->answers) }}
                            </div>
                            <div class="view">
                                {{ $question->views ." ". str_plural('view',$question->views) }}
                            </div>
                        </div>
                        <div class="media-body">
                            <div class="d-flex align-items-center">
                                <h3 class="mt-0"><a href="{{ $question->url }}">{{ $question->title }}</a></h3>
                                <div class="ml-auto">
                                    <div class="row">

                                        <div class="pr-1">
                                            @if (Auth::user()->can('update-question',$question))
                                                <a href="{{ route('questions.edit',$question->id) }}" class="btn btn-sm btn-outline-info">Edit</a>
                                            @endif
                                        </div>
                                        <div>
                                            @if (Auth::user()->can('delete-question',$question))
                                                <form class="form-delete" action="{{ route('questions.destroy',$question->id) }}" method="post">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure to delete ?')">Delete</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="lead">
                                Asked by
                                <a href="{{ $question->user->url }}">{{ $question->user->name }}</a>
                                <small class="text-muted">{{ $question->created_date }}</small>
                            </p>
                            {{ str_limit($question->body,250) }}
                        </div>
                    </div><hr>
                    @endforeach
                    <div class="mx-auto">
                        {{ $questions->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
