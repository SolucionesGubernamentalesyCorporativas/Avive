<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ContratoRequest;
use Illuminate\Support\Facades\Storage;
use App\Contrato;
use App\Membresia;
use App\Pago;

class ContratoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $membresias = Membresia::all();
        $pagos = Pago::all();
        return view('contratos.index')->with('membresias',$membresias)
                                      ->with('pagos',$pagos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContratoRequest $request)
    {
        //
        $pago = Pago::find($request->pagos);
        $membresia = Membresia::find($request->membresia);


        $contrato = new Contrato;
        $contrato->nombre_afiliado = $request->nombre;
        $contrato->curp_afiliado = $request->curp;
        $contrato->edad_afiliado = $request->edad;
        $contrato->domicilio_afiliado = $request->domicilio;
        $contrato->rfc_afiliado = $request->rfc;
        $contrato->correo_electronico_afiliado = $request->email;
        $contrato->membresia()->associate($membresia);
        $contrato->pago()->associate($pago); 


        $curp = $request->curp_file->store('public/img');
        $rfc = $request->rfc_file->store('public/img');
        $comprobante = $request->comprobante->store('public/img');
        $contrato->url_curp = Storage::url($curp);
        $contrato->url_rfc = Storage::url($rfc);
        $contrato->url_comprobante = Storage::url($comprobante);
        $contrato->save();
        return "listo";

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $contrato = Contrato::find($id);
        $visibility = $contrato->url_curp;
         //dd($visibility);
        return view('admin.contratosP')->with('contrato',$contrato);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
