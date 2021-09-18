@extends('adminlte::page')

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/datatables/css/dataTables.bootstrap4.min.css') }}">
@stop

@section('title')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Utilizadores</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item active"><a href="{{ route('users.index') }}">Utilizadores</a></li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
@stop

@section('content')
    <div class="row">
        <div class="container-fluid" style="padding: 20px;">

            <div class="text-center">
                @if (Session::has('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif
            </div>

            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div class="card pb-5">
                        <div class="card-header">
                            <h3 class="card-title">Novo Utilizador</h3>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['method' => 'POST', 'route' => ['users.store'], 'files'=>true]) !!}

                            {!! Form::label('photo', 'Foto:') !!}
                            <div class="form-group">
                                {!! Form::file('photo', null, ['class' => 'form-control ' . ($errors->has('photo') ? ' is-invalid' : '')]) !!}
                                @if ($errors->has('photo'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('photo') }}</strong>
                                    </div>
                                @endif
                            </div>

                            {!! Form::label('name', 'Nome:') !!}
                            <div class="form-group">
                                {!! Form::text('name', null, ['class' => 'form-control ' . ($errors->has('name') ? ' is-invalid' : '')]) !!}
                                @if ($errors->has('name'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </div>
                                @endif
                            </div>

                            {!! Form::label('email', 'E-mail:') !!}
                            <div class="form-group">
                                {!! Form::text('email', null, ['class' => 'form-control ' . ($errors->has('email') ? ' is-invalid' : '')]) !!}
                                @if ($errors->has('email'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </div>
                                @endif
                            </div>

                            {!! Form::label('password', 'Senha:') !!}
                            <div class="form-group">
                                {!! Form::password('password', ['class' => 'form-control ' . ($errors->has('password') ? ' is-invalid' : '')]) !!}
                                @if ($errors->has('password'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </div>
                                @endif
                            </div>

                            {!! Form::label('role_id', 'Funcão:') !!}
                            <div class="form-group">
                                {!! Form::select('role_id', $roles, 0, ['class' => 'form-control' . ($errors->has('role_id') ? ' is-invalid' : '')]) !!}
                                @if ($errors->has('role_id'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('role_id') }}</strong>
                                    </div>
                                @endif
                            </div>

                            {!! Form::label('is_suspended', 'Estado da conta:') !!}
                            <div class="form-group">
                                {!! Form::select('is_suspended', ['0' => 'Activa', '1' => 'Suspensa'], 0, ['class' => 'form-control' . ($errors->has('is_suspended') ? ' is-invalid' : '')]) !!}
                                @if ($errors->has('is_suspended'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('is_suspended') }}</strong>
                                    </div>
                                @endif
                            </div>

                            {!! Form::label('description', 'Descricao:') !!}
                            <div class="form-group">
                                {!! Form::textarea('description', null, ['class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''), 'rows'=>3]) !!}
                                @if ($errors->has('description'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                {!! Form::submit('Salvar', ['class' => 'btn btn-primary']) !!}
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
