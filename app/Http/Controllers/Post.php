<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Helpers\DataTable\WithSorting;
use App\Helpers\Crud\WithCrud;
use App\Helpers\DataTable\WithPerPagePagination;  
use App\Models\Product as ModelsData; 
use App\Models\Order; 
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

    public function store(Request $request) {
        
        $order = $request->all();
        $order["discount"] = $order["discount"] ?? 0;
        unset($order['data']);
        
        $o = Order::create($order);
        $details = collect($request->get("data"))->map(function($v)use($o){
            $data = [];
            $data["order_id"] = $o->id;
            $data["product_id"] = $v["data"]['id'];
            $data["qty"] = $v["total"];
            $data["subtotal"] = $v["total"] * $v["data"]['harga'];
            $data["sku"] = null;
            $data['created_at'] = now();
            $data['updated_at'] = now();
            return $data;
        })->toArray(); 
        $o->details()->createMany($details);

        foreach($request->get("data") as $v){
            $product = $this->mModel::find($v['data']['id']); 
            if (! $product) continue; 
            $product->update([
                'stock' => $product->stock - $v['total'],
            ]);
        }
        return redirect()->back()->with('success', 'Transaksi berhasil disimpan');
    }
}
