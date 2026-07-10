@extends('layouts.student.layout')

@section('content')
   	<quiztest-list url="{{ url('/') }}" scope="{{ $standardLink_id }}"></quiztest-list>
@endsection