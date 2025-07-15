@extends('layouts.app')

@section('content')
    <h1>Metro App</h1>
    @foreach($info as $color => $status)
    <span class="lines"><a href="/info/{{$color}}/stations">{{ $color }}: </a></span>@if($status === ' Ok')<span class="d-inline-block rounded-circle bg-success" style="width: 24px; height: 24px;"></span>@endif<br>
    @endforeach
@endsection

<style scoped lang="scss">
.lines {
    font-size: 24px;
    text-transform: capitalize;
}
</style>