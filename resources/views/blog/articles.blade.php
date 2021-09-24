@extends('layouts.blog')

@section('content')

    <div id="loader" style="display: none">
        <img src="{{ asset('img/loader.gif') }}" alt="Imagem de carregamento">
    </div>

    <section class="hero-overlay hero d-md-block d-none position-relative bg-no-repeat bg-position-center">
        <div class="container">
            <div class="search-section">
                <h4 class="text-white">Encontre os melhores artigos acadêmicos para os seus trabalhos</h4>
                <div class="input-group">
                    <div class="input-group">
                        <input name="term" id="term" type="text" class="form-control py-3">
                        <button id="search-btn" class="input-group-text">
                            <i class="fas fa-search px-3"></i>
                        </button>
                        <button id="mic-btn" class="input-group-text" data-bs-toggle="modal" data-bs-target="#micModal">
                            <i class="fas fa-microphone px-3"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="micModal" tabindex="-1" aria-labelledby="modal-lable" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal-lable">Pesquisar por voz</h5>
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
    </section>
    <section class="p-5 my-2">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h2 class="page-title">{{ $title }}</h2>
                    <p>Veja todos os artigos disponiveis</p>
                </div>
            </div>
        </div>

        <div class="container py-3">
            <div id="articles" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                @forelse ($articles as $article)
                    <div class="col align-items-stretch">
                        <div class="card h-100">
                            <img src="{{ $article->getFeaturedImage() }}" class="card-img-top" alt="Capa da Imagem">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="{{ $article->getLink() }}">
                                        <h4 class="card-title">{{ $article->title }}</h4>
                                    </a>
                                </h5>
                                <p class="card-text"><small class="text-muted">
                                        {{ $article->getPublishedDate() }}</small></p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p><strong>Nenhum artigo foi publicado.</strong></p>
                @endforelse
            </div>
        </div>

        <div id="article-paginate" class="pt-4 d-flex justify-content-center">
            {{ $articles->links() }}
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
                        $('#articles').html("")
                        $('#articles').html(data)
                    } else {
                        if (voice_response) {
                            textToSpeech("Nenhum resultado foi encontrado na busca por " + term);
                        }
                        $('#articles').html(
                            '<div class="col-sm-12 col-md-4"><p><strong>Nenhum resultado foi encontrado!</strong></p></div>'
                        )
                    }
                    $("#article-paginate").html("");
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
