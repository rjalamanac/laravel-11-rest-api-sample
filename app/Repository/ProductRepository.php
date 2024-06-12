<?php

namespace App\Repository;
use App\Models\Product;
use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductRepository implements ProductRepositoryInterface
{
    public function index($perPage = 10){
      return Product::paginate($perPage);
    }

    public function getById($id){
      try {
         return Product::findOrFail($id);
      } catch (ModelNotFoundException $e) {
         return null; 
      }
    }

    public function store(array $data){
      return Product::create($data);
    }

    public function update(array $data,$id){
      $product = Product::find($id);

        if ($product) {
            $product->update($data);
            return $product;
        }

        return null;
    }
    
    public function delete($id){
      try {
         return Product::destroy($id);
      } catch (ModelNotFoundException $e) {
         return null; 
      }
    }
}