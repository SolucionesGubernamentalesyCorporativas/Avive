<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContratoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'nombre'=>'required|min:3',
            'edad'=>'required|integer',
            'email'=> 'required|email',
            'nacionalidad' =>'required',
            'rfc'=> 'required',
            'domicilio'=> 'required|min:5',
            'membresia' => 'required',
            'pagos' => 'required',
            'curp_file'=>'required|image',
            'comprobante'=>'required|image',
            'rfc_file'=>'required|image',



        ];
    }

    public function messages(){
        return [
            'nombre.required' => 'Se requiere de un nombre para registrar la contratación.',
            'nombre.min'  => 'El nombre escrito es demasiado corto.',
            'edad.required' => 'La edad es necesaria.',
            'edad.integer' => 'La edad debe de ser numérica.',
            'email.required' => 'El correo electrónico es un campo necesario.',
            'email.email' => 'La dirección insertada no es valida.',
            'nacionalidad.required' => 'La nacionalidad es un campo necesario.',
            'rfc.required' => 'El RFC es necesario, en caso de no contar con uno, dejar por default.',
            'domicilio.required' => 'El domicilio es un campo necesario.',
            'domicilio.min' => 'El domicilio es demasiado corto.',
            'membresia.required' => 'Por favor Selecciona un tipo de membresia.',
            'pagos.required' => 'La forma de pago es un campo necesario.',
            'curp_file.required' => 'Es necesario que anexes el curp.',
            'curp_file.image' => 'Recuerda que los anexos deben ser imágenes.',
            'comprobante.required' => 'Es necesario que anexes el comprobante de domicilio.',
            'comprobante.image' => 'Recuerda que los anexos deben ser imágenes.',
            'rfc_file.required' => 'Es necesario que anexes la cédula de identificación fiscal.',
            'rfc_file.image' => 'Recuerda que los anexos deben ser imágenes.',



        ];
    }
}
