@extends('restrict.layout')

@section('content')
@if(count($erros)>0)
<ul class="validator">
    @foreach($erros->all() as $error)
    <li>{{$error}}</li>
    @endforeach
</ul>
@endif
<form method='POST' action="{{route('mensagem', $mensagem->id)}}">
    @csrf
    @method('PUT')
    <div>
        <label for="titulo">Título</label>
        <input type="text" name = "titulo" id = "titulo" value = "{{$mensagem ->titulo}}" required/>
    </div>
    <div>
        <label for="msg">Mensagem</label>
        <textarea name = "mensagem" id = "msg" required> "{{$mensagem ->mensagem}}" </textarea>
    </div>
    <div>
        <label>
            Tópicos 
            <a href="{{url('topico/create')}}" class="button">Add Tópico</a>
        </label>
        <div class="sub">
            @foreach($topicos as $topico)
            <input type="checkbox" id="top{{$topico -> id}}" value="{{$topico -> id}}" name="topico[]" @foreach($mensagem->topicos a $msgTopico)
            @if($topico->id == $msgTopico->id) checked @endif
            @endforeach
            />
            <label for = "top{{$topico ->id}}">{{$topico ->id}}</label>
            @endforeach
        </div>
    </div>
    <button type="submit" class= "button">Salvar </button>
</form>