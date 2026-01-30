<?php 

namespace App\Helpers\Crud;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\DataTable\WithSorting;
use App\Helpers\DataTable\WithPerPagePagination; 
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;

use Inertia\Inertia;
use Inertia\Response;
trait WithCrud
{
    use WithPerPagePagination, WithSorting;
    public bool $is_image;
    public string $message;
    public string $views;
    public string $filename;
    public $query;

    public function getRowsQuery(Request $request)
    { 
        return $this->applySorting($this->query,$request);
    }

    public function getData(Request $request)
    { 
        return $this->applyPagination($this->getRowsQuery($request),$request);
    }

    public function createview($views, Request $request ,$data = []): Response
    {  
        
        $rows = $this->getData($request); 
        $search = $request->get('search'); 
        
        $_data = [
            'data' => $rows,
            'filters' => [
                'search' => $search,
            ],
        ];
        $data = array_merge($data,$_data);
        
        return Inertia::render($views,$data);
    }

    public function destroy(Model $model)
    {
        if($this->is_image){ 
            if (isset($model[$this->filename])) {
                $oldPath = str_replace(asset("storage").'/', '', $model[$this->filename]);
               
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
                
            }
        }
        $model->delete(); 
        return redirect()->back()->with('success', 'Data '. $this->message .' berhasil dihapus');
    }

    public function showfirst(Model $model){  
        return response()->json(["data"=> $model]);
    }

    public function post(Request $request) {
        $avatarUrl = null; 
        $data = $request->all();
        if($this->is_image){ 
            if ($request->hasFile($this->filename)) {
                $file = $request->file($this->filename);

                $filename = time().'_'.$file->getClientOriginalName();

                $manager = new ImageManager(new Driver());

                $image = $manager->read($file)->toJpeg(90);
                
                Storage::disk('public')->put("image/$filename", $image);
                $avatarUrl = asset("storage/image/".$filename);
            }
            $data[$this->filename] = $avatarUrl;
        }
        

     
        $this->mModel->create($data);

        return redirect()->back()->with('success', 'Produk '. $this->message .' berhasil ditambah');
    }

    public function updated(Model $model, Request $request)
    {
        

        $data =  $request->all();
        if($this->is_image){ 
            $avatarUrl = !$request->hasFile($this->filename) ? $request->get($this->filename) : null;
            if ($request->hasFile($this->filename)) {

                if ($model->image) {
                    $oldPath = str_replace(asset("storage").'/', '', $model->image);
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }
                $file = $request->file($this->filename); 
                $filename = time().'_'.$file->getClientOriginalName();
                $manager = new ImageManager(new Driver());
                $image = $manager->read($file)->toJpeg(90); 
                Storage::disk('public')->put("image/$filename", $image);
                $avatarUrl = asset("storage/image/".$filename);
            }
            $data[$this->filename] = $avatarUrl;
        }



    
        $model->fill($data);
        $model->save();

        return redirect()->back()->with('success', 'Produk Emart berhasil diedit');
    }
}