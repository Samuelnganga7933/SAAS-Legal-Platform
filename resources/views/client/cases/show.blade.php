@extends('layouts.client')

@section('title', $matter->title . ' - Matter Details')

@section('content')
<livewire:matters.matter-detail :matter="$matter" />
@endsection
