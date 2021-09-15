@extends('layouts.blog')

@section('css')

@endsection

@section('content')

    <section class="p-5 m-5">

        <div id="loader" style="display: none">
            <img src="{{ asset('img/ajax-loader.gif') }}" alt="Imagem de carregamento">
        </div>

        <div class="container">
            <div class="row justify-content-center">
                <div class="modal fade" id="micModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Pesquisar por voz</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body py-5">
                                <span id="final_span" class="final"></span>
                                <span id="interim_span" class="interim"></span>
                            </div>
                            <div class="modal-footer  justify-content-between">
                                <p>Clique no botão iniciar e comece a falar.</p>
                                <button type="button" class="btn btn-danger text-white"
                                    onclick="startButton(event)">Iniciar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">

                <div id="articles-list" class="col-sm-12 col-md-8">
                    <h1 class="article-title">{{ $article->title }}</h1>

                    <img class="article-image rounded py-2 card-img" src="{{ $article->getFeaturedImage() }}" alt="Capa do Artigo">

                    <div class="article-categories py-2">
                        @forelse ($article->categories as $category)
                            <span class="badge bg-dark">
                                {{ $category->name }}
                            </span>
                        @empty
                            <span class="badge bg-secondary">Sem categoria</span>
                        @endforelse
                    </div>

                    <div class="article-author">De {{ $article->author->name }}</div>
                    <small class="article-published"> Publicado {{ $article->created_at->diffForHumans() }}</small>
                    <div class="article-content py-3">
                        {!! $article->content !!}
                    </div>
                    <div class="article-tags py-2">
                        @foreach ($article->tags as $tag)
                            <span class="badge bg-secondary">
                                #{{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <div class="col-sm-12 col-md-4 bg-white shadow-sm">
                    <div class="m-4">
                        <h2 class="sidebar-title pb-1">Pesquisa</h2>
                        <div class="input-group">
                            <div class="input-group">
                                <input name="term" id="term" type="text" class="form-control"
                                    aria-label="Dollar amount (with dot and two decimal places)">
                                <button id="search-btn" class="input-group-text">
                                    <i class="fas fa-search px-3"></i>
                                </button>
                                <button class="input-group-text" data-bs-toggle="modal" data-bs-target="#micModal">
                                    <i class="fas fa-microphone px-3"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="m-4">
                        @if ($categories)
                            <div>
                                <h2 class="sidebar-title">Categorias</h2>
                                <ul class="list-unstyled">
                                    @foreach ($categories as $category)
                                        <li>
                                            <a href="{{ route('blog.category', $category->id) }}">
                                                {{ $category->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <div class=" m-4">
                        @if ($archives)
                            <div>
                                <h2 class="sidebar-title">Arquivos</h2>
                                <ul class="list-unstyled">
                                    @foreach ($archives as $archive)
                                        <li class="d-flex justify-content-between align-items-center">
                                            <a href="{{ route('blog.archive', $archive->month) }}">
                                                {{ ucfirst($archive->getArchiveDate()->monthName) }}
                                                {{ $archive->getArchiveDate()->year }}
                                            </a>
                                            <span class="badge bg-primary badge-pill">{{ $archive->num_articles }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <div class="m-4">
                        @if ($articles)
                            <div>
                                <h2 class="sidebar-title">Artigos Recentes</h2>
                                <ul class="list-unstyled">
                                    @foreach ($articles as $article)
                                        <li class="py-1">
                                            <div class="d-flex">
                                                <img class="sidebar-article-img" width="100" height="65"
                                                    class="p-1 rounded-3" src="{{ $article->getFeaturedImage() }}"
                                                    alt="Capa de artigo">
                                                <div class="px-2">
                                                    <a class="sidebar-article-title"
                                                        href="{{ route('blog.article', $article->id) }}">
                                                        {{ $article->title }}
                                                    </a>
                                                    <p class="sidebar-author">
                                                        De {{ $article->author->name }}
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                </div>

            </div>
        </div>

    </section>

@endsection

@section('js')
    <script src="{{ asset('js/jquery-3.6.0.js') }}"></script>
    <script>
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
                    console.log(
                        'No speech was detected. You may need to adjust your <a href="//support.google.com/chrome/bin/answer.py?hl=en&amp;answer=1407892"> microphone settings'
                    )
                    ignore_onend = true;
                }
                if (event.error == 'audio-capture') {
                    ignore_onend = true;
                }

                if (event.error == 'not-allowed') {
                    if (event.timeStamp - start_timestamp < 100) {
                        console.log(
                            'Permission to use microphone is blocked. To change, go to chrome://settings/contentExceptions#media-stream'
                        )
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

                if (final_transcript) {

                    if ($('#micModal').hasClass('show')) {
                        $('#micModal').fadeOut();
                        $('.modal-backdrop').fadeOut();
                        $('.modal-open').css({
                            'overflow': 'visible'
                        });
                    }
                    send_request(final_transcript, true);
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
            return s.replace(first_char, function(m) {
                return m.toUpperCase();
            });
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

        $("#search-btn").on("click", function() {
            term = $("#term").val();
            send_request(term, false)
        });

        const synth = window.speechSynthesis;

        const textToSpeech = (string) => {
            let voice = new SpeechSynthesisUtterance(string);
            voice.text = string;
            voice.lang = "pt-br";
            voice.volume = 1;
            voice.rate = 1;
            voice.pitch = 1; // Can be 0, 1, or 2
            synth.cancel();
            synth.speak(voice);
        }

        function send_request(term, voice_response = false) {
            $.ajax({
                url: 'http://127.0.0.1:8000/search?term=' + term,
                type: 'GET',
                beforeSend: function() {
                    $('#loader').fadeIn();
                },
                success: function(data) {
                    if (data.length > 0) {
                        if (voice_response) {
                            if (data.length == 1) {
                                textToSpeech("Foi encontrado " + data.length + " resultado na busca por" +
                                    term);
                            } else {
                                textToSpeech("Foram encontrados " + data.length + " resultados na busca por" +
                                    term);
                            }
                        }
                        rows = '<div id ="articles" class="row row-cols-1 row-cols-md-2">'
                        data.forEach(col => {
                            rows += col
                        });
                        rows += '</div>'

                        $('#articles-list').html("")
                        $('#articles-list').html(rows)
                    } else {
                        if (voice_response) {
                            textToSpeech("Nenhum resultado foi encontrado na busca por " + term);
                        }
                        $('#articles-list').html(
                            '<div class="col-sm-12 col-md-4 px-3"><p><strong>Sem registos!</strong></p></div>'
                        )
                    }
                },
                complete: function() {
                    $('#loader').fadeOut();
                }
            });

            if (final_span.innerHTML !== '')
                $("#term").val(final_span.innerHTML)
            final_span.innerHTML = ''
            interim_span.innerHTML = ''
        }
    </script>

@endsection
