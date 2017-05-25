<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ContratoRequest;
use Illuminate\Support\Facades\Storage;
use App\Contrato;
use App\Membresia;
use App\Pago;
use PDF; 

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
        if($request->nacionalidad==1){
            $contrato->nacionalidad_afiliado = "Mexicana";
        }else{
            $contrato->nacionalidad_afiliado = $request->nac_otra;
        }

        $contrato->domicilio_afiliado = $request->domicilio;
        $contrato->rfc_afiliado = $request->rfc;
        $contrato->correo_electronico_afiliado = $request->email;
        $contrato->membresia()->associate($membresia);
        $contrato->pago()->associate($pago); 

        if($request->membresia ==1){
            $contrato->periodo_promo =  " un mes ";
        }else if($request->membresia == 2){
            $contrato->periodo_promo =  " dos meses ";
        }else{
            $contrato->periodo_promo =  " tres meses ";
        }

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


    public function muestraPDF($id){
        $contrato = Contrato::find($id);
        $this->generarPDF($contrato);
    }
    public function generarPDF($contrato){

        
        /*
            REFERENCIAS 
            AddPage('P', 'A4'); es para el tamanio de las hojas 
            MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
            Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')
            Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
        */

        //Configuraciones generales
        //MARGEN ES IZQ, ARRIBA, DER
        PDF::SetMargins(18, 30, 28);
        //configuraciones de fuente
        PDF::SetFont('helvetica', '', 12);
        //texto al margen del contrato
        $texto_margen = 'POR “AVIVE” '.$contrato->nombre_representante.'         '.'"EL AFILIADO" '.$contrato->nombre_afiliado;
        //encabezados
        PDF::setHeaderCallback(function($pdf) use ($contrato){
                $image_file = '/dumy.png';
                $pdf->Image($image_file, 8, 8, 40, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
                $pdf->SetFont('helvetica', 'B', 7);

                // Texto
                $header="Hoja  ".$pdf->getPageNumGroupAlias()." / ".$pdf->getPageGroupAlias().'  del Contrato de Servicios que celebran “AVIVE” y '.$contrato->nombre_afiliado.' de fecha 23 de Mayo de 2017';
                $pdf->MultiCell(60, 10,$header, 0, 'J', 0, 0, 135 ,8, true);
                /*
                $pdf->Cell(75,10, $header, 1, 2, 'J', 0, '', 0, false, 'T', 'M');
                $header="";
                $pdf->Cell(75,10, $header, 1, 2, 'J', 0, '', 0, false, 'T', 'M');*/

            });

        


        

        //inicio del grupo para contar hojas
        PDF::startPageGroup();

        //--------- INICIO DE HOJA 1-------
        PDF::AddPage('P','LETTER');
        
        //inicio del texto del costado
        PDF::StartTransform();
        PDF::SetFont('helvetica', '', 9);
        PDF::Rotate(-90);
        PDF::MultiCell(170, 3,$texto_margen, 0, 'J', 0, 2, 18 ,-155, true);
        PDF::StopTransform();
        //fin texto del costado

        PDF::SetFont('helvetica', 'B', 12);
        $txt = "CONTRATO DE SERVICIOS AVIVE \nPARA MEMBRESÍA ".mb_strtoupper($contrato->membresia->nombre,'UTF-8');
        PDF::MultiCell(170, 3,$txt, 0, 'C', 0, 2, 18 ,35, true);
  
        PDF::Ln();
        $txt ="CONTRATO DE SERVICIOS CORRESPONDIENTES A LA MEMBRESÍA ".mb_strtoupper($contrato->membresia->nombre,'UTF-8');
        $txt.= "QUE CELEBRAN POR UNA PARTE CONSULTORÍA EN DESARROLLO HUMANO Y ORGANIZACIONAL AVIVE S.C., POR CONDUCTO DE SU REPRESENTANTE LEGAL ".mb_strtoupper($contrato->nombre_representante,'UTF-8');
        $txt.= "A QUIEN EN ADELANTE SE LE DENOMINARÁ INDISTINTAMENTE “AVIVE” O “EL PRESTADOR DE SERVICIOS” Y POR OTRA PARTE ".mb_strtoupper($contrato->nombre_afiliado,'UTF-8');
        $txt.= "QUIEN INTERVIENE PERSONALMENTE Y POR DERECHO PROPIO A QUIEN EN ADELANTE SE LE DENOMINARÁ “EL AFILIADO”, DE ACUERDO CON LAS SIGUIENTES:";
        PDF::writeHTML( $txt, true, 0, true, false, 'J');

        PDF::Ln();
        PDF::writeHTML( "DECLARACIONES", true, 0, true, false, 'C');


        PDF::SetFont('helvetica', '', 12);
        PDF::Ln();
        $txt="<b>I.</b> Declara “AVIVE” a través de su representante legal:";
        PDF::writeHTML( $txt, true, 0, true, false, 'J');

        PDF::Ln();
        $txt = '<b>I.1</b> Que es una Sociedad Civil debidamente constituida conforme a las leyes de los Estados Unidos Mexicanos, según consta en la Escritura Pública No. 34,797 (Treinta y Cuatro mil Setecientos Noventa y Siete), de fecha veintisiete de febrero del año dos mil catorce, otorgada ante la fe del licenciado Benjamin Cervantes Cardiel  Titular de la Notaría No. 177 (ciento setenta y siete) de la Ciudad de México, cuyo primer testimonio quedó inscrito en el Registro Público de la Propiedad y de Comercio, bajo el número de Folio de Personas Morales 110304 (ciento diez mil trescientos cuatro).';
        PDF::writeHTML( $txt, true, 0, true, false, 'J');

        PDF::Ln();
        $txt='<b>I.2</b> Que su representante legal cuenta con las facultades necesarias para obligar a su representada conforme a lo estipulado en el presente contrato  tal y como se desprende del';
        if($contrato->declaracion_representante == 2){
            $txt.=' Instrumento Público No. '.$contrato->numero_escritura.' de fecha '.$contrato->fecha_escritura.' otorgado ante la fe del Fedatario Público '.$contrato->nombre_notario.' Notario Público Número '.$contrato->numero_notaria.' ';
            if($contrato->notario_titular){
                $txt.='TITULAR';
            }else{
                $txt.='ADSCRITO';
            }
            $txt.=' en '.$contrato->estado_municipio;
            $txt.=' manifestando que dichas facultades, conforme a las cuales actúa, no le han sido revocadas, modificadas ni limitadas de manera alguna.';
        }else{
            $txt .= ' contenido de los Estatutos de la Escritura Pública descrita en el apartado anterior manifestando que dichas facultades, conforme a las cuales actúa, no le han sido revocadas, modificadas ni limitadas de manera alguna. ';

        }
        PDF::writeHTML( $txt, true, 0, true, false, 'J');



        PDF::Ln();
        $txt='<b>I.3</b> Que está inscrita en el Registro Federal de Contribuyentes con la Clave CDH1402272L1, y Cédula de Identificación Fiscal 14040721676 expedida por el Servicio de Administración Tributaria.';
        PDF::writeHTML( $txt, true, 0, true, false, 'J');


        PDF::Ln();
        $txt="<b>I.4</b> Que señala como domicilio para oír y recibir notificaciones derivadas o relacionadas con el presente instrumento el ubicado en ";
        
        $txt.=$contrato->domicilio_avive.' y correo electrónico '.$contrato->correo_electronico_avive;
        PDF::writeHTML( $txt, true, 0, true, false, 'J');





        //-------- INICIO DE HOJA 2 ------
        PDF::AddPage('P','LETTER');

        //inicio del texto del costado
        PDF::StartTransform();
        PDF::SetFont('helvetica', '', 9);
        PDF::Rotate(-90);
        PDF::MultiCell(170, 3,$texto_margen, 0, 'J', 0, 2, 18 ,-155, true);
        PDF::StopTransform();
        //fin texto del costado

        PDF::Ln();
        PDF::SetFont('helvetica', '', 12);
        $txt='<b>I.5</b> Que como parte de su modelo de servicios ha desarrollado estrategias enfocadas a promover la incursión de expertos en diversos temas de interés para la sociedad, en múltiples sectores y organizaciones bajo una participación y coordinación colectiva que redunda favorablemente en la reputación y prestigio de cada uno de los participantes.';
        PDF::MultiCell(170, 3,$txt, 0, 'J', 0, 2, 17 ,35, true,0,true);

        PDF::Ln();
        $txt='<b>I.6</b> Que los alcances de los derechos que asumen los afiliados para cada proyecto y las obligaciones de “AVIVE” con respecto a cada uno de los participantes se han identificado a través de la figura de membresías las cuales delimitan los aspectos cualitativos y cuantitativos que aplicarán a cada categoría.';
        PDF::MultiCell(170, 3,$txt, 0, 'J', 0, 2, 17 ,63, true,0,true);
        
        PDF::SetFont('helvetica', 'B', 12);
        PDF::Ln();
        $txt='II. Declara "EL AFILIADO" personalmente y por su propio derecho:';
        PDF::MultiCell(170, 3,$txt, 0, 'J', 0, 2, 17 ,90, true,0,true);


        PDF::SetFont('helvetica', '', 12);

        PDF::Ln();
        $txt='II.1  Llamarse como ha quedado indicado al rubro del presente documento, ser de nacionalidad ';
        if($contrato->nacionalidad_afiliado === "Mexicana"){
            $txt.='mexicana, con Clave Única de Registro de Población '.$contrato->curp_afiliado;
        }else{
            $txt.=$contrato->nacionalidad_afiliado;
        }
        $txt.=' ser de '.$contrato->edad_afiliado.' años de edad, con domicilio para oír y recibir notificaciones derivadas o relacionadas con el presente instrumento en ';
        $txt.=$contrato->domicilio_afiliado.' y correo electrónico '.$contrato->correo_electronico_afiliado;
        $txt.=' los cuales reconoce subsistirán para los efectos del presente contrato hasta en tanto no proporcione otros diversos por escrito a “AVIVE” y cuente con el debido acuse de recibido.';
        PDF::writeHTML( $txt, true, 0, true, false, 'J');

        PDF::Ln();
        $txt='<b>II.2</b> Que está inscrita en el Registro Federal de Contribuyentes con la Clave ';
        $txt.=$contrato->rfc_afiliado;
        $txt.=' otorgada por el Servicio de Administración Tributaria de la cual anexa copia de la Cédula de Identificación Fiscal respectiva para efectos de facturación por parte de “AVIVE”.';
        PDF::writeHTML( $txt, true, 0, true, false, 'J');

        PDF::Ln();
        $txt='<b>II.3</b> Haber conocido los beneficios que ofrecen las diversas membresías que ofrece “AVIVE” a través de sus mecanismos de difusión y orientación de su personal con respecto a los proyectos colectivos especializados para expertos en temáticas específicas que coordina;';
        PDF::writeHTML( $txt, true, 0, true, false, 'J');

        PDF::Ln();
        $txt='<b>II.4</b> Que tras haber efectuado su análisis está interesado en recibir los servicios que “AVIVE” ofrece con la adquisición de la Membresía ';
        $txt.=$contrato->membresia->nombre;
        $txt.=' cuyas características y beneficios se precisan en el presente Contrato, en virtud de poseer los conocimientos y experiencia necesarios para participar en la temática que constituye el objeto del proyecto que más adelante se señala.';
        PDF::writeHTML( $txt, true, 0, true, false, 'J');

        PDF::Ln();
        $txt='<b>II.5</b> Que adjunta su curriculum vitae donde se destacan los principales datos  académicos, profesionales y laborales que detallan su experiencia en el manejo temático objeto del presente instrumento.';
        PDF::writeHTML( $txt, true, 0, true, false, 'J');






        //-------- INICIO DE HOJA 3 ------
        PDF::AddPage('P','LETTER');

        //inicio del texto del costado
        PDF::StartTransform();
        PDF::SetFont('helvetica', '', 9);
        PDF::Rotate(-90);
        PDF::MultiCell(170, 3,$texto_margen, 0, 'J', 0, 2, 18 ,-155, true);
        PDF::StopTransform();
        //fin texto del costado

        PDF::Ln();
        PDF::SetFont('helvetica', 'B', 12);
        $txt='III. DECLARAN AMBAS PARTES:';
        PDF::MultiCell(170, 3,$txt, 0, 'J', 0, 2, 17 ,35, true,0,true);

        PDF::SetFont('helvetica', '', 12);

        PDF::Ln();
        $txt='<b>III.1.</b> Que la suscripción de este Contrato no se encuentra afectada por ningún vicio que menoscabe su consentimiento, por lo que está libre de error, violencia, dolo, lesión o mala fe.';
        PDF::writeHTML( $txt, true, 0, true, false, 'J');

        PDF::Ln();
        $txt='<b>III.2.</b> Que se reconocen mutuamente la personalidad con que se ostentan en este acto.';
        PDF::writeHTML( $txt, true, 0, true, false, 'J');

        PDF::Ln();
        $txt='<b>III.3.</b> Que han negociado libremente los términos del presente contrato, por lo que conocen el alcance de los derechos y de las obligaciones que en él asumen, en atención a lo expuesto “AVIVE”  y "EL AFILIADO" se someten a las siguientes:';
        PDF::writeHTML( $txt, true, 0, true, false, 'J');


        PDF::SetFont('helvetica', 'B', 12);
        PDF::Ln();
        PDF::writeHTML( "CLÁUSULAS", true, 0, true, false, 'C');
        PDF::Ln();
        PDF::writeHTML( "OBJETO DEL CONTRATO", true, 0, true, false, 'j');

        PDF::SetFont('helvetica', '', 12);
        PDF::Ln();
        $txt='<b>PRIMERA.-</b> El objeto del presente contrato es establecer las condiciones a que se sujetarán los servicios que prestará “AVIVE” a "EL AFILIADO" con motivo de la adquisición por parte de este último de la Membresía ';
        $txt.='<b>'.mb_strtoupper($contrato->membresia->nombre,'UTF-8').'</b>';
        $txt.=' que contempla los servicios que se describen en la Cláusula Segunda de este Instrumento y que tendrá por alcance temático exclusivamente el concepto ';
        $txt.=$contrato->nombre_proyecto;
        $txt.=' en su versión ';
        $txt.=$contrato->version;
        $txt.=' en lo sucesivo “EL PROYECTO” para un colectivo de hasta ';
        $txt.=$contrato->participantes;
        $txt.=' participantes los cuales son determinados por “AVIVE” y se detallan de forma preliminar en el Anexo 1 del presente Contrato.';
        PDF::writeHTML( $txt, true, 0, true, false, 'J');

        PDF::Ln();
        $txt='“AVIVE”,  se compromete a brindar los servicios materia de este contrato solicitados por “EL AFILIADO, en óptimas condiciones aplicando la metodología más apropiada, eficaz, ética, bajo secrecía, con la mejor calidad y la mayor diligencia en su desarrollo, en los términos del presente instrumento.';
        PDF::writeHTML( $txt, true, 0, true, false, 'J');

        PDF::SetFont('helvetica', 'B', 12);
        PDF::Ln();
        PDF::writeHTML( "DE LA MEMBRESÍA", true, 0, true, false, 'j');


        PDF::SetFont('helvetica', '', 12);
        PDF::Ln();
        $txt='<b>SEGUNDA.-</b> La Membresía ';
        $txt.='<b>'.mb_strtoupper($contrato->membresia->nombre,'UTF-8').'</b>';
        $txt.=' que “EL AFILIADO” adquiere por virtud del presente Contrato implicará por parte de “AVIVE” la prestación de los servicios siguientes:';
        PDF::writeHTML( $txt, true, 0, true, false, 'J');





        //-------- INICIO DE HOJA 3 ------
        PDF::AddPage('P','LETTER');

        //inicio del texto del costado
        PDF::StartTransform();
        PDF::SetFont('helvetica', '', 9);
        PDF::Rotate(-90);
        PDF::MultiCell(170, 3,$texto_margen, 0, 'J', 0, 2, 18 ,-155, true);
        PDF::StopTransform();
        //fin texto del costado


        PDF::SetFont('helvetica', 'B', 12);
        PDF::Ln();
        PDF::MultiCell(170, 3,"2.1 SERVICIOS WEB:", 0, 'J', 0, 2, 17 ,35, true,0,true);

        PDF::SetFont('helvetica', 'B', 12);
        PDF::Ln();
        PDF::writeHTML( "2.1.1.- Directorio:", true, 0, true, false, 'j');


        PDF::SetFont('helvetica', '', 12);
        PDF::Ln();
        $txt='Incorporación en el Directorio que “AVIVE” generará para la identificación de los participantes expertos en “EL PROYECTO”, mismo que contendrá al menos los datos de “EL AFILIADO” siguientes: nombre, fotografía, enlace a su página en Internet, de existir ésta, e información de contacto. ';
        PDF::writeHTML( $txt, true, 0, true, false, 'J');

        //linea de render del PDF
        PDF::Output('prueba -.pdf');

        



    }



}
