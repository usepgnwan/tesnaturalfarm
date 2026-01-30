<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Helpers\DataTable\WithSorting;
use App\Helpers\Crud\WithCrud;
use App\Helpers\DataTable\WithPerPagePagination;  
use App\Models\Order as ModelsData; 
class Order
{
     use WithPerPagePagination, WithSorting,WithCrud;
  
    public $mModel;
    public $filters = [
        'search' => ''
    ];
    public function __construct(ModelsData $mModel,Request $request){
        $this->mModel = $mModel;
        $this->is_image = true;
        $this->message = "Produk"; 
        $this->filename = "image";
        $this->query =  $this->mModel::with('details.product')->when(!is_null($request->get('search')), function($q) use ($request){
             $q->where("no_order","ilike", "%" .$request->get('search'). "%");
        }); 
    }
 
    public function index(Request $request): Response
    {  
        return $this->createview("platform/Orders",$request);
    }
}
