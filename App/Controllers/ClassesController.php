<?php
namespace App\Controllers;
use App\Models\Classes;
use Core\Controller;

class ClassesController extends Controller
{
    public function show(int $id){
       d (Classes::select()->where('id','=',$id)->get());
    }

    public function index ($id = null, $supertaxon = null){
        if($supertaxon && $id){
            $result = match($supertaxon){
                'divisio' => Classes::select()->where('divisio_id', '=', $id)->get()
            };

            d($result);
        }else{
            d(Classes::selectAll()->get());
        }
    }

    public function create(){
        d(Classes::create([
            'name' => 'Spirochaetes',
            'divisio_id' => 6
        ]));
    }
}