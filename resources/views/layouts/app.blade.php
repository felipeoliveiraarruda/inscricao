@extends('laravel-usp-theme::master')

@section('title') 
  @parent 
@endsection

@section('styles')
  @parent
  <style>
    /*seus estilos*/
  </style>
@endsection

@section('javascripts_bottom')
  @parent
  <script type="text/javascript" src="{{ asset('js/mascara.js') }}"></script>
@endsection