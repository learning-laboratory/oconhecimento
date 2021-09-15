
@extends('adminlte::page')

@section('css')
<link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/datatables/css/dataTables.bootstrap4.min.css') }}">
@stop
@section('plugins.Datatables', true)

@section('title')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Artigos</h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item active"><a href="{{ route('articles.index') }}">Artigos</a></li>
        </ol>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop

@section('content')
<div class="row">
				    <div class="container-fluid" style="padding: 20px;">

					<div class="text-center">
						@if(Session::has('message'))
							<div class="alert alert-success">
								{{session('message')}}
							</div>
						@endif
					</div>

					<div class="row">
						<div class="col-sm-12 col-md-12">
							<div class="card">
								<div class="card-header">
									<h3 class="card-title">Todos Artigos</h3>
								</div>
								<div class="card-body">
                                    <table id="table" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Titulo</th>
                                                <th>Categoria</th>
                                                <th>Author</th>
                                                <th>Data Publicacao</th>
                                                <th>Acções</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($articles)
                                                @foreach($articles as $article)
                                                    <tr>
                                                        <td>{{$article->title}}</td>
                                                        <td>
                                                            @forelse ($article->categories as $category)
                                                                {{ $category->name }}
                                                            @empty
                                                                Sem categoria
                                                            @endforelse
                                                        </td>
                                                        <td>{{$article->author->name}}</td>
                                                        <td>{{$article->created_at}}</td>
                                                        <td class="py-0 align-middle">
                                                            {!! Form::open(['method'=>'DELETE','route'=>['articles.destroy', $article->id]]) !!}
                                                                <div class="btn-group btn-group-sm">
                                                                    <a href="{{route('blog.article',$article->id)}}" class="btn btn-dark"><i class="fa fa-eye"></i></a>
                                                                    <a href="{{route('articles.edit',$article->id)}}" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                                                                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
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
<script src="{{ asset('vendor/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/js/dataTables.bootstrap4.min.js') }}"></script>
<script>
  $(function () {
    $('#table').DataTable({
        "language": {
            url: 'https://cdn.datatables.net/plug-ins/1.11.1/i18n/pt_br.json'
        }
    });
  });
</script>
@endsection
