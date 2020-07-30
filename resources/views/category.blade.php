@extends('layouts.master')
{{--@section('title', $category->name)--}}
@section('content')
<div class="container">

        <h1>
            {{$category->name}} {{$category->products->count()}}
        </h1>
        <p>
            {{$category->description}}
        </p>
        <div class="row">
            @foreach($category->products()->with('category')->get() as $product)
                @include('layouts.card',compact('product'))
            @endforeach

        </div>

</div>
@endsection
