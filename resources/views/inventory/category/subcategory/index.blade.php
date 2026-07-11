@extends('layouts.admin.layout')
@section('content')
    <showsubcategory url="{{ url('/') }}" categoryid="{{$category->id}}" categoryname="{{ucfirst($category->name)}}"  ></showsubcategory>
 
 @endsection