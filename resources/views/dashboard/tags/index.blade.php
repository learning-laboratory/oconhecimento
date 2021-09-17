@extends('adminlte::page')

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/datatables/css/dataTables.bootstrap4.min.css') }}">
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

            <div class="text-center">
                @if (Session::has('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif
            </div>

            <div class="row">

                <div class="col-sm-12 col-md-5">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Nova Tag</h3>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['method' => 'POST', 'route' => ['tags.store']]) !!}
                            {!! Form::label('name', 'Nome:') !!}
                            <div class="form-group">
                                {!! Form::text('name', null, ['class' => 'form-control ' . ($errors->has('name') ? ' is-invalid' : '')]) !!}
                                @if ($errors->has('name'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
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

                <div class="col-sm-12 col-md-7">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Todas Tags</h3>
                        </div>
                        <div class="card-body">
                            <table id="table" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Acções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($tags)
                                        @foreach ($tags as $tag)
                                            <tr>
                                                <td>{{ $tag->name }}</td>
                                                <td class="py-0 align-middle">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['tags.destroy', $tag->id]]) !!}
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('tags.edit', $tag->id) }}"
                                                            class="btn btn-primary"><i class="fa fa-edit"></i></a>
                                                        <button type="submit" class="btn btn-danger"><i
                                                                class="fas fa-trash"></i></button>
                                                    </div>
                                                    {!! Form::close() !!}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @stop

        @section('js')
            <script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
            <script src="{{ asset('vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
            <script>
                $(function() {
                    $('#table').DataTable({
                        "language": {
                            url: 'https://cdn.datatables.net/plug-ins/1.11.1/i18n/pt_br.json'
                        }
                    });
                });
            </script>
        @endsection
