@extends('layouts.blog')

@section('content')
    <section class="p-5 m-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="bg-white p-5">
                        <div class="text-center">
                            <h2 class="contact-title">Deixe-nos uma mensagem</h2>
                            <p class="contact-description">Normalmente respondemos dentro de 2 dias úteis</p>
                        </div>
                        <form action="#" method="post" class="row">
                            <div class="col-md-6 py-2">
                                <label for="name">Nome:</label>
                                <input type="text" name="name" id="name" class="form-control">
                            </div>
                            <div class="col-md-6 py-2">
                                <label for="email">E-mail:</label>
                                <input type="email" name="email" id="email" class="form-control">
                            </div>
                            <div class="col-md-12 py-2">
                                <label for="subject">Assunto:</label>
                                <input type="text" name="subject" id="subject" class="form-control">
                            </div>
                            <div class="col-md-12 py-2">
                                <label for="message">Mensagem:</label>
                                <textarea name="message" id="message" cols="30" rows="6" class="form-control"></textarea>
                            </div>
                            <div class="col-md-12 py-2">
                                <button class="btn btn-dark">Enviar Mensagem</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
