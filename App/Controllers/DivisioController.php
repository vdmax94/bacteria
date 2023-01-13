<?php
namespace App\Controllers;
use App\Models\Divisio;
use Core\Controller;

class DivisioController extends Controller
{
    public function show(int $id){
       d (Divisio::select()->where('id','=',$id)->get());
    }

    public function index (){
        d(Divisio::selectAll()->get());
    }

    public function create(){
        d(Divisio::create([
            'name' => 'Spirochaetae'
        ]));
    }
}