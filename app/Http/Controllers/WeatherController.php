<?php

namespace App\Http\Controllers;

use App\Weather;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{
    
    public function current(){
        $query = request()->get('query');

        if(empty($query))
            return response()->json([
                'success'=>'false',
                'message'=>'Debe ingresar query'
            ],201);

      
        //realiza la busqueda en la base de datos
        $weather = Weather::where('query',$query)->first();

        if(empty($weather)){
            Log::info('No se ha encontrado resultados de ' . $query . ' en la base de datos local');

           $weather = new Weather([
               "query" => $query
           ]);

           $weather->updateDataAPI();
            
        }else{

            if($weather->updated_at <= Carbon::now()->subHour() ){
                Log::info('la informacion no se encuentra actualizada ');
                $weather->updateDataAPI();
            }else{
                Log::info('devuelve la información de query ="'.$query.'" de la base de datos, se actualizo hace ' . $weather->updated_at->diffForHumans() );

            }
        }


        return response()->json(json_decode( $weather->response ),200) ;
    }
}
