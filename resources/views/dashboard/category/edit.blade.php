@extends('dashboard.master')
@section('content')

@include('dashboard.partials.validation-error')
<form action="{{ route('post.update',$post->id) }}" method="POST">
    @method('PUT')
    @include('dashboard.post._form')
</form>
@endsection
