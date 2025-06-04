@extends('layouts.master')

@section('title', 'Dashboard GoKas Admin')

@section('css')

@endsection

@section('pageContent')
    <div class="container">

    </div>

@endsection

@section('scripts')
    <script src="{{ URL::asset('build/js/vendor.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/dashboards/dashboard.js') }}"></script>
@endsection
