@extends('layouts.blog')

@section('content')

    <section class="p-5">
        <div id="loader" style="display: none">
            <img src="{{ asset('img/ajax-loader.gif') }}" alt="Imagem de carregamento">
        </div>

        <div class="container py-2">
            <div class="row justify-content-center">
                <div class="col-4">
                    <div class="input-group m-4">
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
        </div>

        <section class="py-2 text-center container">
            <div class="row">
                <div class="col-lg-6 col-md-8 mx-auto">
                    <h1 class="fw-light page-title">{{ $title }}</h1>
                    <p class="lead text-muted page-description">Veja todos os artigos disponiveis</p>
                </div>
            </div>
        </section>

        <div class="py-5">
            <div class="container">
                <div id="articles-list" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                    @forelse ($articles as $article)
                        <div class="col">
                            <div class="card shadow-sm">
                                <img src="{{ $article->getFeaturedImage() }}" class="card-img-top" width="100%"
                                    height="235" alt="Capa do Artigo">
                                <div class="card-body">
                                    <h2 class="article-title">
                                        <a href="{{ $article->getLink() }}">{{ $article->title }}</a>
                                    </h2>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="article-author">
                                            De {{ $article->author->name }}
                                        </div>
                                        <small class="text-muted article-published">Publicado
                                            {{ $article->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p><strong>Sem artigos</strong></p>
                    @endforelse

                    <div class="article-paginate d-flex justify-content-center py-4">
                        {{ $articles->links() }}
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
                        $('#articles-list').html("")
                        $('#articles-list').html(data)
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
