<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Helpers\DataTable\WithSorting;
use App\Helpers\Crud\WithCrud;
use App\Helpers\DataTable\WithPerPagePagination;  
use App\Models\Product as ModelsData; 
class Post extends Controller
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
        $this->query =  $this->mModel::when(!is_null($request->get('search')), function($q) use ($request){
             $q->where("title","ilike", "%" .$request->get('search'). "%");
        }); 
    }
 
    public function index(Request $request): Response
    {  
        return $this->createview("platform/Post",$request);
    }

    // /**
    //  * Show the form for creating a new resource.
    //  */
    // public function create()
    // {
    //     //
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(Request $request)
    // {
    //     //
    // }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(string $id)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(string $id)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, string $id)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(string $id)
    // {
    //     //
    // }
}
