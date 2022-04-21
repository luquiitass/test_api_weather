<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Weather extends Model
{
    protected $table = 'weather';

    public $timestamps = true;

    protected $fillable = ['query','response'];


    public function updateDataAPI(){

        Log::info('Obteniendo los datos de la API para almacenar en la base de datos local');

        $response = Http::get('http://api.weatherstack.com/current?access_key=53365e7c13490a81a3d126335ad317ba&query=' . $this->query );

        $this->response = $response->body();

        $this->save();

    }
    
}
