@extends('adminlte::page')

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/datatables/css/dataTables.bootstrap4.min.css') }}">
@stop
@section('title')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Categorias</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item active"><a href="{{ route('categories.index') }}">Categorias</a></li>
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
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Todas Utilizadores</h3>
                        </div>
                        <div class="card-body">
                            <table id="table" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>E-mail</th>
                                        <th>Função</th>
                                        <th>Estado da conta</th>
                                        <th>Acções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($users)
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>
                                                    <img class="img-circle img-size-32 mr-2" src="{{ $user->getAvatar() }}" alt="Avatar do utilizador">
                                                    {{ $user->name }}
                                                </td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    @forelse ($user->roles as $role)
                                                        {{ $role->display_name }}
                                                    @empty
                                                        Sem função
                                                    @endforelse
                                                </td>
                                                <td>
                                                    @if ($user->is_suspended)
                                                        <span class="badge badge-danger">Suspensa</span>
                                                    @else
                                                        <span class="badge badge-success">Activa</span>
                                                    @endif
                                                </td>
                                                <td class="py-0 align-middle">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $user->id]]) !!}
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('users.edit', $user->id) }}"
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
