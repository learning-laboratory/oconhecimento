
@extends('layouts.blog')

@section('css')

@endsection

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12 col-md-5">
            <div class="input-group mb-3">
                <input type="text" name="term" id="term" class="py-3 form-control" placeholder="Pesquisar..." aria-label="Pesquisar..." aria-describedby="basic-addon2">
                <div class="input-group-append">
                  <button id="search-btn" class="input-group-text" id="basic-addon2">
                      <i class="fas fa-search px-3"></i>
                  </button>
                  <button class="input-group-text" id="basic-addon2" data-toggle="modal" data-target="#micModal">
                      <i class="fas fa-microphone px-3"></i>
                  </button>
                </div>
            </div>

            <div class="modal fade" id="micModal" tabindex="-1" role="dialog" aria-labelledby="micModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="micModalLabel">Pesquisar por voz</h5>
                            <button  type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body py-5">
                            <span id="final_span" class="final"></span>
                            <span id="interim_span" class="interim"></span>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <p>Clique no ícone do microfone e comece a falar.</p>
                            <button type="button" class="btn btn-danger rounded-circle" onclick="startButton(event)">
                                <i class="fas fa-microphone"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">

        <div class="col-sm-12 py-2">
            <div>
                <h1 class="page-title">Artigos</h1>
                <p class="page-description">Veja todos os artigos disponiveis</p>
            </div>
        </div>
    </div>
    <div id="articles-list" class="row">
        @forelse ($articles as $article)
            <div class="col-sm-12 col-md-4 px-3">
                <img class="article-image rounded py-2" width="300" height="220" src="{{ $article->getFeturedImage() }}" alt="Capa do Artigo">
                <h2 class="article-title py-2">
                    <a href="{{ route('blog.article', $article->id) }}">
                        {{ $article->title }}
                    </a>
                </h2>
                <div class="article-categories">
                    @forelse ($article->categories as $category)
                        <span class="badge badge-dark">
                            {{ $category->name }}
                        </span>
                    @empty
                        Sem Categoria
                    @endforelse
                </div>
                <div class="article-published">
                    {{ $category->created_at->format('d-M-Y') }}
                </div>
                <div class="article-content py-3">
                    @if($article->summary)
                        {!! Str::limit($article->summary,160, ' ...'); !!}
                    @else
                        {!! Str::limit($article->content,160, ' ...'); !!}
                    @endif
                </div>
            </div>
        @empty
            Sem Artigos
        @endforelse

    </div>
</div>

@endsection

@section('js')
<script src="{{ asset('vendor/jquery/jquery.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.js') }}"></script>

<script >

    var final_transcript = '';
    var recognizing = false;
    var ignore_onend;
    var start_timestamp;

    if (('webkitSpeechRecognition' in window)) {

    var recognition = new webkitSpeechRecognition();
    recognition.interimResults = true;

    recognition.onstart = function() {
        recognizing = true;
        console.log('Speak now')
    };

    recognition.onerror = function(event) {

        if (event.error == 'no-speech') {
        console.log('No speech was detected. You may need to adjust your <a href="//support.google.com/chrome/bin/answer.py?hl=en&amp;answer=1407892"> microphone settings')
        ignore_onend = true;
        }
        if (event.error == 'audio-capture') {
        ignore_onend = true;
        }

        if (event.error == 'not-allowed') {
        if (event.timeStamp - start_timestamp < 100) {
        console.log('Permission to use microphone is blocked. To change, go to chrome://settings/contentExceptions#media-stream')
        } else {
        console.log('Permission to use microphone was denied!')
        }
        ignore_onend = true;
        }
    };

    recognition.onend = function() {

        recognizing = false;

        if (ignore_onend) {
        return;
        }

        if (!final_transcript) {
        return;
        }

        if(final_transcript){

            if($('#micModal').hasClass('show')){
                $('#micModal').fadeOut();
                $('.modal-backdrop').fadeOut();
                $('.modal-open').css({'overflow': 'visible'});
            }
            send_request(final_transcript);
        }

    };

    recognition.onresult = function(event) {
        var interim_transcript = '';
        for (var i = event.resultIndex; i < event.results.length; ++i) {
        if (event.results[i].isFinal) {
            final_transcript += event.results[i][0].transcript;
        } else {
            interim_transcript += event.results[i][0].transcript;
        }
        }
        final_transcript = capitalize(final_transcript);
        final_span.innerHTML = linebreak(final_transcript);
        interim_span.innerHTML = linebreak(interim_transcript);
    };
    }


    var two_line = /\n\n/g;
    var one_line = /\n/g;
    function linebreak(s) {
    return s.replace(two_line, '<p></p>').replace(one_line, '<br>');
    }

    var first_char = /\S/;
    function capitalize(s) {
    return s.replace(first_char, function(m) { return m.toUpperCase(); });
    }

    function startButton(event) {
        if (recognizing) {
            recognition.stop();
            return;
        }
        final_transcript = '';
        recognition.lang = 'pt-BR';
        recognition.start();
        ignore_onend = false;
        final_span.innerHTML = '';
        interim_span.innerHTML = '';
        start_timestamp = event.timeStamp;
    }

    $("#search-btn").on("click", function(){
        term = $("#term").val();
        send_request(term)
    });

    function send_request(term) {
        $.ajax({
                url: 'http://127.0.0.1:8000/search?term='+term,
                type: 'GET',
                success: function(data){
                    if(data.length > 0){
                        console.log(data)
                        $('#articles-list').html("")
                        $('#articles-list').html(data)
                    }else{
                        $('#articles-list').html('<div class="col-sm-12 col-md-4 px-3"><p><strong>Sem registos!</strong></p></div>')
                    }
                }
        });

        if(final_span.innerHTML !== '')
            $("#term").val(final_span.innerHTML)
        final_span.innerHTML = ''
        interim_span.innerHTML = ''
    }



    


</script>

@endsection
