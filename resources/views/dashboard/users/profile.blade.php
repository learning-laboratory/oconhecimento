@extends('adminlte::page')

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/datatables/css/dataTables.bootstrap4.min.css') }}">
@stop

@section('title')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Perfil</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item active"><a href="{{ route('categories.index') }}">Perfil</a></li>
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
                <div class="col-sm-12 col-md-4">
                    <div>
                        <h4>Informacões do Perfil</h4>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsum, aperiam? Ea rem sit minus fugiat
                            soluta</p>
                    </div>
                </div>
                <div class="col-sm-12 col-md-8">
                    <div class="card pb-5">
                        <div class="card-body">
                            {!! Form::open(['method' => 'PUT', 'route' => ['user-profile-information.update', $user], 'files' => true]) !!}
                            {!! Form::label('photo', 'Foto:') !!}
                            <div class="form-group">
                                {!! Form::file('photo', null, ['class' => 'form-control ' . ($errors->updateProfileInformation->has('photo') ? ' is-invalid' : '')]) !!}
                                @if ($errors->updateProfileInformation->has('photo'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->updateProfileInformation->first('photo') }}</strong>
                                    </div>
                                @endif
                            </div>

                            {!! Form::label('name', 'Nome:') !!}
                            <div class="form-group">
                                {!! Form::text('name', $user->name, ['class' => 'form-control ' . ($errors->updateProfileInformation->has('name') ? ' is-invalid' : '')]) !!}
                                @if ($errors->updateProfileInformation->has('name'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->updateProfileInformation->first('name') }}</strong>
                                    </div>
                                @endif
                            </div>
                            {!! Form::label('email', 'E-mail:') !!}
                            <div class="form-group">
                                {!! Form::text('email', $user->email, ['class' => 'form-control ' . ($errors->updateProfileInformation->has('email') ? ' is-invalid' : '')]) !!}
                                @if ($errors->updateProfileInformation->has('email'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->updateProfileInformation->first('email') }}</strong>
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
                <div class="col-sm-12 col-md-4">
                    <div>
                        <h4>Alterar a Senha</h4>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsum, aperiam? Ea rem sit minus fugiat
                            soluta</p>
                    </div>
                </div>
                <div class="col-sm-12 col-md-8">
                    <div class="card">
                        <div class="card-body">
                            {!! Form::open(['method' => 'PUT', 'route' => ['user-password.update']]) !!}
                            {!! Form::label('current_password', 'Senha Actual:') !!}
                            <div class="form-group">
                                {!! Form::password('current_password', ['class' => 'form-control ' . ($errors->updatePassword->has('current_password') ? ' is-invalid' : '')]) !!}
                                @if ($errors->updatePassword->has('current_password'))
                                    <div class="invalid-feedback">
                                        <strong>O campo senha actual é obrigatório.</strong>
                                    </div>
                                @endif
                            </div>
                            {!! Form::label('password', 'Nova Senha:') !!}
                            <div class="form-group">
                                {!! Form::password('password', ['class' => 'form-control ' . ($errors->updatePassword->has('password') ? ' is-invalid' : '')]) !!}
                                @if ($errors->updatePassword->has('password'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->updatePassword->first('password') }}</strong>
                                    </div>
                                @endif
                            </div>

                            {!! Form::label('password_confirmation', 'Confirmar Senha:') !!}
                            <div class="form-group">
                                {!! Form::password('password_confirmation', ['class' => 'form-control ' . ($errors->updatePassword->has('password_confirmation') ? ' is-invalid' : '')]) !!}
                                @if ($errors->updatePassword->has('password_confirmation'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->updatePassword->first('password_confirmation') }}</strong>
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
