<?php

namespace App\Http\Controllers;

use App\Models\Mensagem;
use Illuminate\Http\Request;
use App\Models\Topico;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MensagemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mensagens = Mensagem::all();
        return view("restrict/mensagem", compact('mensagens'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $topicos = Topico::all();
        return view("restrict/mensagem/create", compact('topicos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|max:255',
            'mensagem' => 'required|max:255',
            'topico' => 'array|exists:App\Models\Topico,id',
            'imagem' => 'image'
        ]);
        if ($validated){
            $mensagem = new Mensagem();
            $mensagem->user_id = Auth::User()->id;
            $mensagem->titulo = $request->get('titulo');
            $mensagem->mensagem = $request->get('mensagem');
            $name = $request->file('imagem')->getClientOriginalName();
            $path = $request->file('imagem')->storeAs("public/img", $name);
            $mensagem->imagem = $path;
            $mensagem->save();
            $mensagem->topicos()->attach($request->get('topico'));
            //return redirect('mensagem');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Mensagem  $mensagem
     * @return \Illuminate\Http\Response
     */
    public function show(Mensagem $mensagem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Mensagem  $mensagem
     * @return \Illuminate\Http\Response
     */
    public function edit(Mensagem $mensagem)
    {
        $topicos = Topico::all();
        return view("restrict/mensagem/edit", compact('topicos', 'mensagem'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Mensagem  $mensagem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mensagem $mensagem)
    {
        $validated = $request->validate([
            'titulo' => 'required|max:255',
            'mensagem' => 'required|max:255',
            'topico' => 'array|exists:App\Models\Topico,id',
            'imagem' => 'image'
        ]);
        if ($validated){
            $mensagem->titulo = $request->get('titulo');
            $mensagem->mensagem = $request->get('mensagem');
            $name = $request->file('imagem')->getClientOriginalName();
            $path = $request->file('imagem')->storeAs("public/img", $name);
            $mensagem->imagem = $path;
            $mensagem->save();
            $mensagem->topicos()->sync($request->get('topicos'));
            return redirect('mensagem');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Mensagem  $mensagem
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mensagem $mensagem)
    {
        $mensagem->delete();
        return redirect("mensagem");
    }
}