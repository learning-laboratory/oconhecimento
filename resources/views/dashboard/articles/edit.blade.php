
@extends('adminlte::page')

@section('css')
<link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/summernote/summernote-bs4.min.css') }}">
@stop
@section('plugins.Select2', true)

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
                    {!! Form::open(['method'=>'PATCH','route'=>['articles.update', $article->id], 'files'=>true]) !!}
					<div class="row">

						<div class="col-sm-12 col-md-8">
							<div class="card">
								<div class="card-header">
									<h3 class="card-title">Editar Artigo</h3>
								</div>
								<div class="card-body">

										{!! Form::label('title','Título:') !!}
										<div class="form-group">
										{!! Form::text('title', $article->title, [ 'class' => 'form-control ' . ( $errors->has('title') ? ' is-invalid' : '' )]) !!}
											@if($errors->has('title'))
												<div class="invalid-feedback">
													<strong>{{ $errors->first('title') }}</strong>
												</div>
											@endif
										</div>

                                        {!! Form::label('content','Conteúdo:') !!}
										<div class="form-group">
										{!! Form::textarea('content', $article->content, [ 'class' => 'text-area form-control ' . ( $errors->has('content') ? ' is-invalid' : '' )]) !!}
											@if($errors->has('content'))
												<div class="invalid-feedback">
													<strong>{{ $errors->first('content') }}</strong>
												</div>
											@endif
										</div>

                                        {!! Form::label('summary','Sumário:') !!}
										<div class="form-group">
										{!! Form::textarea('summary', $article->summary, ['rows'=>'3', 'class' => 'text-area form-control ' . ( $errors->has('summary') ? ' is-invalid' : '' )]) !!}
											@if($errors->has('summary'))
												<div class="invalid-feedback">
													<strong>{{ $errors->first('summary') }}</strong>
												</div>
											@endif
										</div>



								</div>
							</div>
						</div>

						<div class="col-sm-12 col-md-4">
							<div class="card">
								<div class="card-header">
									<h3 class="card-title">Publicação</h3>
								</div>
								<div class="card-body">

                                    <div class="form-group border-bottom pb-2">
										{!! Form::label('category_id','Categorias:') !!}
										{!! Form::select('category_id[]', $categories, $categories_selected, ['class'=>'form-control select2','multiple'=>'multiple']) !!}
									</div>

									<div class="form-group border-bottom pb-2">
										{!! Form::label('tag_id','Tags:') !!}
										{!! Form::select('tag_id[]', $tags, $tags_selected, ['class'=>'form-control select2','multiple'=>'multiple']) !!}
									</div>

									<div class="form-group border-bottom pb-2">
							            {!! Form::label('photo','Capa:') !!}
									    {!! Form::file('photo',null,['class'=>'form-control']) !!}
									</div>

									<div class="form-group">
										{!! Form::submit('Publicar',['class'=>'btn btn-primary']) !!}
									</div>
							    </div>
						    </div>
					    </div>
                {!! Form::close() !!}
			</div>
@stop

@section('js')
<script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('vendor/summernote/summernote-bs4.min.js') }}"></script>
<script>
  $(function () {
    $('.select2').select2({
        theme: "classic"
    });
    $('.text-area').summernote({
      lang: 'pt-PT'
    });
  });
</script>
@endsection
