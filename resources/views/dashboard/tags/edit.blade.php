@extends('adminlte::page')

@section('css')
<link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.css') }}">
@stop

@section('title')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Tags</h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item active"><a href="{{ route('tags.index') }}">Tags</a></li>
        </ol>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop

@section('content')
<div class="row">
    <div class="container-fluid" style="padding: 20px;">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Editar Categoria</h3>
            </div>
            <div class="card-body">
                {!! Form::open(['method'=>'PATCH','route'=>['tags.update', $tag->id]]) !!}
                    {!! Form::label('name','Nome:') !!}
                    <div class="form-group">
                    {!! Form::text('name', $tag->name, [ 'class' => 'form-control ' . ( $errors->has('name') ? ' is-invalid' : '' )]) !!}
                        @if($errors->has('name'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('name') }}</strong>
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        {!! Form::submit('Salvar',['class'=>'btn btn-primary']) !!}
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

</div>
@stop
