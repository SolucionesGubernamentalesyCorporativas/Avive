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
            $contrato->periodo_promo =  "un mes";
            $contrato->num_apariciones = "seis";
            $contrato->exten_max ="diez";
            $contrato->caracteres ="13,430";
            $contrato->ejemplares_entrega = "cuarenta";
            $contrato->paginas = "finales";
            $contrato->incluira_en = "diez";
            $contrato->invitaciones = "cinco";
        }else if($request->membresia == 2){
            $contrato->periodo_promo =  "dos meses";
            $contrato->num_apariciones = "doce";
            $contrato->exten_max ="quince";
            $contrato->caracteres ="20,145";
            $contrato->ejemplares_entrega = "ochenta";
            $contrato->paginas = "intermedias";
            $contrato->incluira_en = "quince";
            $contrato->invitaciones = "diez";
            $contrato->tiempo_podio="cinco";
        }else{
            $contrato->periodo_promo =  "tres meses";
            $contrato->num_apariciones = "veinte";
            $contrato->exten_max ="veinte";
            $contrato->caracteres ="26,860";
            $contrato->ejemplares_entrega = "ciento veinte";
            $contrato->paginas = "iniciales";
            $contrato->incluira_en = "veinte";
            $contrato->invitaciones = "veinte";
            $contrato->tiempo_podio="diez";
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
        PDF::SetFont('helvetica', '', 12);
        //fin texto del costado

        PDF::Ln();
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
        PDF::SetFont('helvetica', '', 12);
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





        //-------- INICIO DE HOJA 4 ------
        PDF::AddPage('P','LETTER');

        //inicio del texto del costado
        PDF::StartTransform();
        PDF::SetFont('helvetica', '', 9);
        PDF::Rotate(-90);
        PDF::MultiCell(170, 3,$texto_margen, 0, 'J', 0, 2, 18 ,-155, true);
        PDF::StopTransform();
        PDF::SetFont('helvetica', '', 12);
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

        PDF::SetFont('helvetica', 'B', 12);
        PDF::Ln();
        PDF::writeHTML( "2.1.2 Promoción en Sitio WEB:", true, 0, true, false, 'j');

        PDF::Ln();
        PDF::SetFont('helvetica', '', 12);
        $txt='Dentro del sitio especializado que “AVIVE” establezca en Internet específicamente para difusión de “EL PROYECTO” se contendrá un formato publicitario (banner) a través del cual “AVIVE” difundirá los servicios que ofrezca “EL AFILIADO”, que no sean inconsistentes con “EL PROYECTO”, al menos por un periodo de ';
        $txt.=$contrato->periodo_promo;
        $txt.=' calendario durante la vigencia de su Membresía.';
        PDF::writeHTML( $txt, true, 0, true, false, 'J');

        PDF::SetFont('helvetica', 'B', 12);
        PDF::Ln();
        PDF::writeHTML( "2.1.3 Publicación en Redes Sociales:", true, 0, true, false, 'j');

        PDF::Ln();
        PDF::SetFont('helvetica', '', 12);
        $txt='<b>2.1.3.1</b> “AVIVE” difundirá, al menos a través de las plataformas de Facebook, Twitter y Linkedin que serán utilizadas para dar a conocer “EL PROYECTO”, información sobre “EL AFILIADO” y su participación en la temática del mismo, así como los servicios que ofrezca o aquellos anuncios que vinculados a su ejercicio profesional o actividad empresarial sean presentados a “AVIVE” por “EL AFILIADO” siempre que no sean inconsistentes con “EL PROYECTO”.';
        PDF::writeHTML( $txt, true, 0, true, false, 'J');

        PDF::Ln();
        $txt= '<b>2.1.3.2</b> “AVIVE” comunicará a “EL AFILIADO” las razones por las cuales no sea factible difundir un anuncio que este solicite a través de los perfiles que “AVIVE” genere y administre en las redes sociales indicadas en el punto anterior';
        PDF::writeHTML( $txt, true, 0, true, false, 'J');

        PDF::Ln();
        $txt = '<b>2.1.3.3 </b>“AVIVE” difundirá simultáneamente en las plataformas indicadas, los anuncios que le solicite “EL AFILIADO” que cumplan con lo señalado en los dos puntos anteriores hasta ';
        $txt.= $contrato->num_apariciones;
        $txt.=' ocasiones en total y siempre que se encuentre vigente su Membresía.';
        PDF::writeHTML( $txt, true, 0, true, false, 'J');


        if($contrato->membresia->id ==1){
            PDF::SetFont('helvetica', 'B', 12);
            PDF::Ln();
            PDF::writeHTML( "2.1.4 No contiene servicios de Video Prólogo", true, 0, true, false, 'j');
        }else{
            PDF::SetFont('helvetica', 'B', 12);
            PDF::Ln();
            PDF::writeHTML( "2.1.4 Video Prólogo:", true, 0, true, false, 'j');

            PDF::SetFont('helvetica', '', 12);
            PDF::Ln();
            $txt='<b>2.1.4.1</b> “AVIVE” producirá un video con una duración mínima de tres minutos, que tendrá como personaje central a “EL AFILIADO”, con un propósito introductorio o complementario a la participación que presente “EL AFILIADO” para efecto de “EL PROYECTO” y siempre acorde con su temática.';
            PDF::writeHTML( $txt, true, 0, true, false, 'J');

        }



        //-------- INICIO DE HOJA 5 ------
        PDF::AddPage('P','LETTER');

        //inicio del texto del costado
        PDF::StartTransform();
        PDF::SetFont('helvetica', '', 9);
        PDF::Rotate(-90);
        PDF::MultiCell(170, 3,$texto_margen, 0, 'J', 0, 2, 18 ,-155, true);
        PDF::StopTransform();
        PDF::SetFont('helvetica', '', 12);
        //fin texto del costado

        if($contrato->membresia->id !=1){
            PDF::Ln();
            $txt='<b>2.1.4.2</b> “AVIVE” se asegurará de que el Video Prólogo que produzca para “EL AFILIADO” sea accesible tanto en la plataforma que el propio “AVIVE” establezca en Internet específicamente para difusión de “EL PROYECTO”, como mediante la versión impresa del  trabajo que ponga a su disposición “EL AFILIADO” para su integración en la obra literaria integral, para lo cual “AVIVE” proporcionará a “EL AFILIADO” un servicio de realidad aumentada.';
            PDF::MultiCell(170, 3,$txt, 0, 'J', 0, 2, 17 ,35, true,0,true);
            PDF::SetFont('helvetica', 'B', 12);
            PDF::Ln();
            PDF::MultiCell(170, 3,"2.2 MATERIAL IMPRESO", 0, 'J', 0, 2, 17 ,70, true,0,true);
            
        }else{
            PDF::SetFont('helvetica', 'B', 12);
            PDF::Ln();
            PDF::MultiCell(170, 3,"2.2 MATERIAL IMPRESO", 0, 'J', 0, 2, 17 ,35, true,0,true);
        }

        PDF::SetFont('helvetica', '', 12);
        PDF::Ln();
        $txt='<b>2.2.1</b> “EL AFILIADO” proporcionará a “AVIVE” un texto de su autoría respecto de cuya obra “EL AFILIADO” goce de las prerrogativas y privilegios que la ley en materia de Derecho de Autor identifica como derechos moral y patrimonial cuyo objetivo será que “AVIVE” integre como colaboración conjunta con los otros trabajos que de manera similar aporten otros afiliados que participen como expertos en “EL PROYECTO”.';
        PDF::writeHTML( $txt, true, 0, true, false, 'J');

        PDF::Ln();
        $txt='<b>2.2.2</b>  El texto de la obra proporcionada por “EL AFILIADO” para los efectos de difusión y promoción que asume “AVIVE” como parte de la prestación de sus servicios, podrá contar con una extensión máxima de ';
        $txt.=$contrato->exten_max;
        $txt.=' cuartillas o ';
        $txt.=$contrato->caracteres;
        $txt.=' caracteres, escritos en formato Word, con letra Arial número 12, a doble espacio y con un margen superior, inferior, derecho e izquierdo de 2.5 centímetros.';
        PDF::writeHTML( $txt, true, 0, true, false, 'J');

        PDF::Ln();
        $txt='<b>2.2.3</b> “AVIVE” integrará en un sólo ejemplar impreso tanto la obra que le proporcione “EL AFILIADO” como la que aporten otros afiliados que participen como expertos en “EL PROYECTO”, por lo que en ningún caso “AVIVE” efectuará impresiones aisladas de la obra de “EL AFILIADO” pues es el conjunto de dichas obras las que de manera integral se promocionarán como material especializado en la temática que aborda “EL PROYECTO”.';
        PDF::writeHTML( $txt, true, 0, true, false, 'J');

        PDF::Ln();
        $txt='<b>2.2.4</b>  “EL AFILIADO” con la entrega de su obra a “AVIVE”  para los efectos a que se refiere el presente Contrato, le autoriza su divulgación de forma conjunta con los trabajos de los otros expertos afiliados que participarán en “EL PROYECTO”, así como su utilización en el contexto de la publicidad o acción  asociada a cualesquiera de los servicios de difusión que en particular “AVIVE” ofrezca a “EL AFILIADO” por virtud del presente Contrato.';
        PDF::writeHTML( $txt, true, 0, true, false, 'J');

        PDF::Ln();
        $txt='<b>2.2.5</b>  “AVIVE” podrá determinar la participación de expertos nacionales o internacionales que por su trayectoria den realce a “EL PROYECTO” en todas sus vertientes a pesar de no tener la característica de afiliados en beneficio del impacto de la obra literaria integral resultante y su difusión.';
        PDF::writeHTML( $txt, true, 0, true, false, 'J');


        //-------- INICIO DE HOJA 6 ------
        PDF::AddPage('P','LETTER');

        //inicio del texto del costado
        PDF::StartTransform();
        PDF::SetFont('helvetica', '', 9);
        PDF::Rotate(-90);
        PDF::MultiCell(170, 3,$texto_margen, 0, 'J', 0, 2, 18 ,-155, true);
        PDF::StopTransform();
        PDF::SetFont('helvetica', '', 12);
        //fin texto del costado


        PDF::Ln();
        $txt='<b>2.2.6</b> “AVIVE” se asegurará en cualquier caso, que el material impreso o electrónico que contenga la obra que pondrá a su disposición “EL AFILIADO”, siempre conlleve el reconocimiento de su calidad de autor respecto de la obra por él creada, la cual “AVIVE” no podrá bajo ninguna circunstancia deformar, mutilar o modificar.';
        PDF::MultiCell(170, 3,$txt, 0, 'J', 0, 2, 17 ,35, true,0,true);

        PDF::Ln();
        $txt='<b>2.2.7</b> “EL AFILIADO” conservará para sí los derechos morales y patrimoniales sobre su obra, “AVIVE” sólo está autorizado para incluir la obra de “EL AFILIADO” conjuntamente con los trabajos de los otros expertos afiliados que participarán en “EL PROYECTO” en el material impreso y electrónico que se genere del mismo, teniendo “EL AFILIADO” en todo momento la posibilidad de disponer la publicación de su obra en cualquier otro foro o de autorizar a otros su explotación, en cualquier forma.';
        PDF::MultiCell(170, 3,$txt, 0, 'J', 0, 2, 17 ,60, true,0,true);

        PDF::Ln();
        $txt='<b>2.2.8</b> “EL AFILIADO” está de acuerdo y otorga su autorización para que “AVIVE” lleve a cabo la impresión de su obra para los efectos de “EL PROYECTO” conforme a lo establecido en el presente Contrato, en una sola pieza literaria en un número de ';
        $txt.=$contrato->ejemplares;
        $txt.=', ejemplares impresos, en ';
        $txt.='más un sólo archivo electrónico que contendrá la pieza literaria integral.';
        PDF::MultiCell(170, 3,$txt, 0, 'J', 0, 2, 17 ,95, true,0,true);

        PDF::Ln();
        $txt='<b>2.2.9</b> “AVIVE” entregará a “EL AFILIADO” ';
        $txt.=$contrato->ejemplares_entrega;
        $txt.=' ejemplares impresos de la pieza literaria integral que se conforme con la conjunción de la obra de “EL AFILIADO” y la de los otros expertos afiliados que participarán en “EL PROYECTO”, así como el prólogo y los demás elementos que defina “AVIVE” en favor de la difusión y la generación de interés en “EL PROYECTO”. ';
        PDF::MultiCell(170, 3,$txt, 0, 'J', 0, 2, 17 ,125, true,0,true);

        PDF::Ln();
        $txt='<b>2.2.10</b> Las partes reconocen su acuerdo en que los ejemplares de la pieza literaria integral que “AVIVE” entregará a “EL AFILIADO”  constituirán la remuneración que “AVIVE” proporcionará a “EL AFILIADO” como autor y titular de los derechos patrimoniales de la obra que aportó en favor de “EL PROYECTO”.  “EL AFILIADO” podrá comercializar libremente dichos ejemplares.  El equivalente en monetario de dicha remuneración será el resultante de multiplicar el número de ejemplares proporcionados a “EL AFILIADO” por el costo de producción que conforme al numeral siguiente notifique oportunamente “AVIVE” a “EL AFILIADO”.';
        PDF::MultiCell(170, 3,$txt, 0, 'J', 0, 2, 17 ,155, true,0,true);

        PDF::Ln();
        $txt='<b>2.2.11</b> “AVIVE” con independencia de los ejemplares que entregará a “EL AFILIADO” en términos y para los efectos a que se refieren los apartados anteriores, pondrá a disposición de “EL AFILIADO” el número de ejemplares que este desee adquirir de la pieza literaria integral correspondiente a ”EL PROYECTO” al costo de su producción sin mediar ganancia alguna para “AVIVE”, sujeto al número de ejemplares de que conste el tiraje correspondiente y a las solicitudes similares que reciba “AVIVE”  de otros afiliados participantes en “EL PROYECTO”.  Para tal efecto “AVIVE” notificará a “EL AFILIADO” dicho costo una vez que se cuente con el material de la pieza literaria integral impreso y previo al evento de lanzamiento a que se refiere el presente contrato.';
        PDF::MultiCell(170, 3,$txt, 0, 'J', 0, 2, 17 ,205, true,0,true);


        //-------- INICIO DE HOJA 7 ------
        PDF::AddPage('P','LETTER');
        //inicio del texto del costado
        PDF::StartTransform();
        PDF::SetFont('helvetica', '', 9);
        PDF::Rotate(-90);
        PDF::MultiCell(170, 3,$texto_margen, 0, 'J', 0, 2, 18 ,-155, true);
        PDF::StopTransform();
        PDF::SetFont('helvetica', '', 12);
        //fin texto del costado

        PDF::Ln();
        $txt='<b>2.2.12</b> “AVIVE” en atención a la membresía ';
        $txt.='<b>'.mb_strtoupper($contrato->membresia->nombre,'UTF-8').'</b>';
        $txt.=' que adquiere “EL AFILIADO” estará sujeta a lo siguiente:';
        PDF::MultiCell(170, 2,$txt, 0, 'J', 0, 2, 17 ,35, true,0,true);

        PDF::Ln();
        $txt='<b>2.2.12.1</b> La obra de “EL AFILIADO” formará parte de las páginas ';
        $txt.=$contrato->paginas;
        $txt.=' de la pieza literaria integral que se conforme con la conjunción de la obra de “EL AFILIADO” y la de los otros expertos afiliados que participarán en “EL PROYECTO” en los términos del presente Contrato';
        PDF::writeHTML( $txt, true, 0, true, false, 'J');

        PDF::Ln();
        $txt='<b>2.2.12.2</b> “AVIVE” tomará el criterio primero en tiempo primero en orden de integración para determinar entre afiliados que adquieran el mismo tipo de membresía para efectos de “EL PROYECTO”, la secuencia en que se presentarán sus respectivas obras en la pieza literaria integral resultante.';
        PDF::writeHTML( $txt, true, 0, true, false, 'J');

        PDF::Ln();
        $txt='Conforme a este parámetro la fecha y hora  de liquidación de la membresía a “AVIVE” que aparezca en su estado de cuenta determinará la secuencia correspondiente.';
        PDF::writeHTML( $txt, true, 0, true, false, 'J');

        PDF::Ln();
        $txt='<b>2.2.12.3</b> La obra de “EL AFILIADO” se incluirá en al menos ';
        $txt.=$contrato->incluira_en;
        $txt.=' páginas de la pieza literaria integral resultante.';
        PDF::writeHTML( $txt, true, 0, true, false, 'J');

        PDF::Ln();
        $txt='<b>2.2.12.4</b> El nombre de “EL AFILIADO”';
        if($contrato->membresia->id !=3 ){
            $txt.=' no aparecerá en la portada.';
        }else{
            $txt.=' deberá aparecer en la portada.';
        }
        PDF::writeHTML( $txt, true, 0, true, false, 'J');

        PDF::Ln();
        $txt='<b>2.2.12.5</b> La fotografía de “EL AFILIADO”';
        if($contrato->membresia->id !=3 ){
            $txt.=' no aparecerá en la segunda de forros.';
        }else{
            $txt.=' deberá aparecer en la segunda de forros.';
        }
        PDF::writeHTML( $txt, true, 0, true, false, 'J');


        PDF::SetFont('helvetica', 'B', 12);
        PDF::Ln();
        PDF::writeHTML( "2.3 SERVICIO DE REALIDAD AUMENTADA", true, 0, true, false, 'j');
        if($contrato->membresia->id ==1){
            PDF::SetFont('helvetica', 'B', 12);
            PDF::Ln();
            PDF::writeHTML( "2.3.1 No incorpora servicios de realidad aumentada", true, 0, true, false, 'j');
                        
            

        }else{
            PDF::SetFont('helvetica', '', 12);
            PDF::Ln();
            $txt='<b>2.3.1</b> “AVIVE” brindará a “EL AFILIADO” un servicio de realidad aumentada durante la vigencia de su membresía  que permitirá a quienes tengan acceso a la pieza literaria integral que se conforme, el acceder a través de la aplicación indicada en el propio texto impreso y mediante el uso de la imagen determinada en cada caso, al Video Prólogo producido para ”EL AFILIADO” en los términos establecidos en el presente Contrato.';
            PDF::writeHTML( $txt, true, 0, true, false, 'J');

            PDF::Ln();
            $txt='<b>2.3.2</b>  Será responsabilidad de los usuarios contar con la aplicación y el equipo para su instalación a efecto de poder hacer uso del servicio de realidad aumentada.  En todo caso la aplicación correspondiente será de uso gratuito y deberá estar disponible para servicios IOS y ANDROID';
            PDF::writeHTML( $txt, true, 0, true, false, 'J');

           

        }

        PDF::SetFont('helvetica', 'B', 12);
        PDF::Ln();
        PDF::writeHTML( "2.4 VERSIÓN ELECTRÓNICA DE LA PIEZA LITERARIA ", true, 0, true, false, 'j');

        PDF::SetFont('helvetica', '', 12);
        PDF::Ln();
        $txt='“AVIVE” proporcionará un archivo en formato electrónico a “EL AFILIADO” que contenga una versión de la compilación de material aportado por él y por los expertos participantes en “EL PROYECTO”';
        PDF::writeHTML( $txt, true, 0, true, false, 'J');

        //-------- INICIO DE HOJA 8 ------
        PDF::AddPage('P','LETTER');
        //inicio del texto del costado
        PDF::StartTransform();
        PDF::SetFont('helvetica', '', 9);
        PDF::Rotate(-90);
        PDF::MultiCell(170, 3,$texto_margen, 0, 'J', 0, 2, 18 ,-155, true);
        PDF::StopTransform();
        PDF::SetFont('helvetica', '', 12);
        //fin texto del costado

        PDF::SetFont('helvetica', 'B', 12);
        PDF::Ln();
        PDF::MultiCell(170, 2,"2.5 LANZAMIENTO ", 0, 'J', 0, 2, 17 ,35, true,0,true);
        PDF::Ln();
        PDF::writeHTML( "2.5.1 Evento de Lanzamiento", true, 0, true, false, 'j');

        PDF::SetFont('helvetica', '', 12);
        PDF::Ln();
        $txt='<b>2.5.1.1</b> “AVIVE” coordinará un evento a desarrollarse en la fecha que determine y en las instalaciones acondicionadas para recibir a los expertos participantes en “EL PROYECTO”, a sus invitados en atención al número que de los mismos corresponda a su membresía, y a personas de los medios de comunicación, todo ello a efecto de dar a conocer los aspectos destacados de “EL PROYECTO” y presentar la pieza literaria integral que se conforme con las obras aportadas por todos los expertos afiliados en términos de lo señalado en el presente contrato.';
        PDF::writeHTML( $txt, true, 0, true, false, 'J');

        PDF::Ln();
        $txt='<b>2.5.1.2</b> “EL AFILIADO” recibirá de “AVIVE” ';
        $txt.=$contrato->invitaciones;
        $txt.=' invitaciones digitales que podrá distribuir entre igual número de personas para que asistan como sus invitados al evento referido en el punto anterior.  “AVIVE” reservará bajo relación de “EL AFILIADO” los nombres de sus asistentes, asegurando  su acceso al foro donde se desarrolle el evento indicado.';
        PDF::writeHTML( $txt, true, 0, true, false, 'J');

        PDF::Ln();
        $txt='<b>2.5.1.3</b> La logística del Evento que aplicará “AVIVE” dará prioridad en cercanía a la ubicación del podium a los invitados de afiliados con membresía Integral, seguidos de aquellos que correspondan a las membresías óptima y básica, en ese orden.';
        PDF::writeHTML( $txt, true, 0, true, false, 'J');

        PDF::Ln();
        $txt='<b>2.5.1.4</b> “AVIVE” tomará el criterio primero en tiempo primero en ubicación para determinar entre afiliados que adquieran el mismo tipo de membresía, el orden en que serán colocados sus invitados.  Conforme a este parámetro la fecha y hora  de liquidación de la membresía a “AVIVE” que aparezca en su estado de cuenta determinará el orden correspondiente.';
        PDF::writeHTML( $txt, true, 0, true, false, 'J');

        PDF::Ln();
        $txt='<b>2.5.1.5</b> “AVIVE” podrá invitar a participar en el evento de lanzamiento  a expertos nacionales o internacionales que por su trayectoria den realce a “EL PROYECTO” a pesar de no tener la característica de afiliados.';
        PDF::writeHTML( $txt, true, 0, true, false, 'J');

        PDF::SetFont('helvetica', 'B', 12);
        PDF::Ln();
        PDF::writeHTML( "2.5.2 Video de Lanzamiento", true, 0, true, false, 'J');
        if($contrato->membresia->id==1){
            PDF::Ln();
            PDF::writeHTML( "2.5.2.1 No se contempla para Membresía Básica ", true, 0, true, false, 'J');
            PDF::Ln();
            PDF::writeHTML( "2.5.3 Tiempo en Podium", true, 0, true, false, 'J');
            PDF::Ln();
            PDF::writeHTML( "2.5.3.1 No se contempla para Membresía Básica", true, 0, true, false, 'J');
            
        }else{
            PDF::SetFont('helvetica', '', 12);
            PDF::Ln();
            $txt='<b>2.5.2.1 </b>“AVIVE” producirá un video de lanzamiento con una duración mínima de dos minutos, que abordará la temática de “EL PROYECTO” y en el cual se efectuará la mención de los nombres de los afiliados con Membresías Óptima e Integral.  Dicho video estará disponible con posterioridad a la realización del evento de Lanzamiento, en el sitio especializado que “AVIVE” establezca en Internet específicamente para difusión de “EL PROYECTO” y a través del enlace que se integre por conducto de las plataformas de redes sociales que utilizará “AVIVE” en los términos que se precisan en el presente Contrato.';
            PDF::writeHTML( $txt, true, 0, true, false, 'J');

            //-------- INICIO DE HOJA 9 ------
            PDF::AddPage('P','LETTER');
            //inicio del texto del costado
            PDF::StartTransform();
            PDF::SetFont('helvetica', '', 9);
            PDF::Rotate(-90);
            PDF::MultiCell(170, 3,$texto_margen, 0, 'J', 0, 2, 18 ,-155, true);
            PDF::StopTransform();
            PDF::SetFont('helvetica', '', 12);
            //fin texto del costado

            PDF::Ln();
            $txt='<b>2.5.2.2</b>  “AVIVE” tomará el criterio primero en tiempo primero en orden de mención para determinar entre afiliados que adquieran el mismo tipo de membresía, el orden de mención su nombre en el Video de lanzamiento.  Conforme a este parámetro la fecha y hora  de liquidación de la membresía a “AVIVE” que aparezca en su estado de cuenta determinará la secuencia correspondiente.';
            PDF::MultiCell(170, 2,$txt, 0, 'J', 0, 2, 17 ,35, true,0,true);
            
            
        }
        


        if($contrato->membresia->id == 1){
                //-------- INICIO DE HOJA 9 ------
                PDF::AddPage('P','LETTER');
                //inicio del texto del costado
                PDF::StartTransform();
                PDF::SetFont('helvetica', '', 9);
                PDF::Rotate(-90);
                PDF::MultiCell(170, 3,$texto_margen, 0, 'J', 0, 2, 18 ,-155, true);
                PDF::StopTransform();
                PDF::SetFont('helvetica', '', 12);
                //fin texto del costado

                PDF::SetFont('helvetica', 'B', 12);
                PDF::Ln();
                PDF::MultiCell(170, 2,"2.5.4 Presencia en Material Visual el día del Evento ", 0, 'J', 0, 2, 17 ,35, true,0,true);
                PDF::Ln();
                PDF::writeHTML( "2.5.4.1 No se contempla para Membresía Básica", true, 0, true, false, 'J');

                PDF::Ln();
                PDF::writeHTML( "2.6 COWORKING", true, 0, true, false, 'J');

                PDF::SetFont('helvetica', '', 12);
                PDF::Ln();
                $txt='<b>2.6.1</b> “AVIVE” organizará al menos dos sesiones de trabajo durante la vigencia de la membresía de “EL AFILIADO” a la que le convocará a participar con el fin de compartir sus experiencias con los otros expertos afiliados participantes en “EL PROYECTO”.  Esta experiencia de trabajo será presencial y se llevará a cabo en las instalaciones que determine “AVIVE” y ponga a disposición de los participantes en la fecha y en el horario que les comunique al menos con 20 días de anticipación.';
                PDF::writeHTML( $txt, true, 0, true, false, 'J');

                PDF::Ln();
                $txt='<b>2.6.2</b> “AVIVE” pondrá a disposición de “EL AFILIADO” el listado de personas y datos de contacto de las mismas que entren en contacto con “AVIVE” por estar interesadas en “EL PROYECTO” o que ingresen al sitio especializado que “AVIVE” establezca en Internet específicamente para obtener información en general del mismo o que mencionen su interés en la obra o los servicios que ofrezca “EL AFILIADO”.  Tal información deberá ser manejada acorde con el aviso de privacidad que en su momento establezca “AVIVE” para efectos de “EL PROYECTO”.';
                PDF::writeHTML( $txt, true, 0, true, false, 'J');
                
                PDF::SetFont('helvetica', 'B', 12);
                PDF::Ln();
                PDF::writeHTML( "COMPROMISOS A CARGO DE “EL AFILIADO", true, 0, true, false, 'J');

                PDF::SetFont('helvetica', '', 12);
                PDF::Ln();
                $txt='<b>TERCERA.-</b> "EL AFILIADO" mediante la suscripción del presente Contrato reconoce que la definición de las estrategias que coordinará “AVIVE” implican asegurar la participación de cada uno de los expertos que intervienen en “EL PROYECTO” en este sentido acepta que su integración al mismo a través de la membresía que adquiere, conlleva asegurar para “AVIVE” y para el resto de los afiliados el asumir una conducta ética responsable, por lo cual se compromete:';
                PDF::writeHTML( $txt, true, 0, true, false, 'J');


        }else{
            PDF::SetFont('helvetica', 'B', 12);
            PDF::writeHTML( "2.5.3 Tiempo en Podium ", true, 0, true, false, 'J');

            PDF::SetFont('helvetica', '', 12);
            PDF::Ln();
            $txt='<b>2.5.3.1</b> “AVIVE” coordinará la logística del evento de lanzamiento considerando la intervención presencial que en el mismo deberán tener los afiliados que participen en “EL PROYECTO” y cuenten con Membresías Óptima e Integral. Salvo caso fortuito o de fuerza mayor que impida a “EL AFILIADO” participar en la fecha y hora programada para ello, “AVIVE” aplicará los medios a su alcance para preservar su intervención en términos óptimos.';
            PDF::writeHTML( $txt, true, 0, true, false, 'J');

            PDF::Ln();
            $txt='<b>2.5.3.2</b> “EL AFILIADO” contará con un tiempo de ';
            $txt.=$contrato->tiempo_podio;
            $txt.=' minutos para hacer su intervención presencial el día del evento de Lanzamiento desde el Podium.  El orden de la intervención de “EL AFILIADO” será definido por “AVIVE” aplicando el mismo criterio señalado en el apartado referente al video de lanzamiento.';
            PDF::writeHTML( $txt, true, 0, true, false, 'J');

            PDF::SetFont('helvetica', 'B', 12);
            
            if($contrato->membresia->id == 2){
                PDF::Ln();
                PDF::writeHTML( "2.5.4 Presencia en Material Visual el día del Evento ", true, 0, true, false, 'J');
                //PDF::MultiCell(170, 2,"2.5.4 Presencia en Material Visual el día del Evento ", 0, 'J', 0, 2, 17 ,55, true,0,true);
                PDF::Ln();
                PDF::writeHTML( "2.5.4.1 No se contempla para Membresía Óptima", true, 0, true, false, 'J');
                PDF::Ln();
                PDF::writeHTML( "2.6 COWORKING", true, 0, true, false, 'J');

                PDF::SetFont('helvetica', '', 12);
                PDF::Ln();
                $txt='<b>2.6.1</b> “AVIVE” organizará al menos dos sesiones de trabajo durante la vigencia de la membresía de “EL AFILIADO” a la que le convocará a participar con el fin de compartir sus experiencias con los otros expertos afiliados participantes en “EL PROYECTO”.  Esta experiencia de trabajo será presencial y se llevará a cabo en las instalaciones que determine “AVIVE” y ponga a disposición de los participantes en la fecha y en el horario que les comunique al menos con 20 días de anticipación.';
                PDF::writeHTML( $txt, true, 0, true, false, 'J');

                PDF::Ln();
                $txt='<b>2.6.2</b> “AVIVE” pondrá a disposición de “EL AFILIADO” el listado de personas y datos de contacto de las mismas que entren en contacto con “AVIVE” por estar interesadas en “EL PROYECTO” o que ingresen al sitio especializado que “AVIVE” establezca en Internet específicamente para obtener información en general del mismo o que mencionen su interés en la obra o los servicios que ofrezca “EL AFILIADO”.  Tal información deberá ser manejada acorde con el aviso de privacidad que en su momento establezca “AVIVE” para efectos de “EL PROYECTO”.';
                PDF::writeHTML( $txt, true, 0, true, false, 'J');

                

            }else{
                PDF::Ln();
                PDF::writeHTML( "2.5.4 Presencia en Material Visual el día del Evento ", true, 0, true, false, 'J');

                PDF::SetFont('helvetica', '', 12);
                PDF::Ln();
                $txt='“AVIVE” utilizará el día del evento de Lanzamiento un caballete donde se colocará una imagen de “EL AFILIADO” con su nombre y algún otro dato que estime conveniente “AVIVE” vinculado a “EL PROYECTO”.  La imagen estará contenida en una de pieza de material sólido cuyas dimensiones no serán  inferiores a los 40 cm de un lado por 35 cm del otro.  El orden de colocación de los caballetes en caso de existir más de un afiliado con membresía Integral, será definido por “AVIVE” aplicando el mismo criterio de orden establecido en el apartado referente al video de lanzamiento.';
                PDF::writeHTML( $txt, true, 0, true, false, 'J');

                
                PDF::SetFont('helvetica', 'B', 12);
                PDF::Ln();
                PDF::writeHTML( "2.6 COWORKING", true, 0, true, false, 'J');

                PDF::SetFont('helvetica', '', 12);
                PDF::Ln();
                $txt='<b>2.6.1</b> “AVIVE” organizará al menos dos sesiones de trabajo durante la vigencia de la membresía de “EL AFILIADO” a la que le convocará a participar con el fin de compartir sus experiencias con los otros expertos afiliados participantes en “EL PROYECTO”.  Esta experiencia de trabajo será presencial y se llevará a cabo en las instalaciones que determine “AVIVE” y ponga a disposición de los participantes en la fecha y en el horario que les comunique al menos con 20 días de anticipación.';
                PDF::writeHTML( $txt, true, 0, true, false, 'J');

                

            }


            //-------- INICIO DE HOJA 10 ------
                PDF::AddPage('P','LETTER');
                //inicio del texto del costado
                PDF::StartTransform();
                PDF::SetFont('helvetica', '', 9);
                PDF::Rotate(-90);
                PDF::MultiCell(170, 3,$texto_margen, 0, 'J', 0, 2, 18 ,-155, true);
                PDF::StopTransform();
                PDF::SetFont('helvetica', '', 12);
                //fin texto del costado

                if($contrato->membresia->id == 2){
                    PDF::SetFont('helvetica', 'B', 12);
                    PDF::Ln();
                    PDF::MultiCell(170, 2,"COMPROMISOS A CARGO DE “EL AFILIADO", 0, 'J', 0, 2, 17 ,35, true,0,true);                
                }else{
                    PDF::Ln();
                    $txt='<b>2.6.2</b> “AVIVE” pondrá a disposición de “EL AFILIADO” el listado de personas y datos de contacto de las mismas que entren en contacto con “AVIVE” por estar interesadas en “EL PROYECTO” o que ingresen al sitio especializado que “AVIVE” establezca en Internet específicamente para obtener información en general del mismo o que mencionen su interés en la obra o los servicios que ofrezca “EL AFILIADO”.  Tal información deberá ser manejada acorde con el aviso de privacidad que en su momento establezca “AVIVE” para efectos de “EL PROYECTO”.';
                    PDF::MultiCell(170, 2,$txt, 0, 'J', 0, 2, 17 ,35, true,0,true);

                    PDF::SetFont('helvetica', 'B', 12);
                    PDF::Ln();
                    PDF::MultiCell(170, 2,"COMPROMISOS A CARGO DE “EL AFILIADO", 0, 'J', 0, 2, 17 ,75, true,0,true);
                }



            
        }
        



        


        

        


        //linea de render del PDF
        PDF::Output('prueba -.pdf');

        



    }



}
