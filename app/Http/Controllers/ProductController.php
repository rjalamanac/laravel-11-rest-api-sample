<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Interfaces\ProductRepositoryInterface;
use App\Classes\ApiResponseClass;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\DB;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Products",
 *     description="API Endpoints of Products"
 * )
 */
class ProductController extends Controller
{
    private ProductRepositoryInterface $productRepositoryInterface;

    public function __construct(ProductRepositoryInterface $productRepositoryInterface)
    {
        $this->productRepositoryInterface = $productRepositoryInterface;
    }

    /**
     * @OA\Get(
     *     path="/products",
     *     summary="Get a list of products",
     *     tags={"Products"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="List of products"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Product")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="No Products Available"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function index()
    {
        $perPage = 10;
        $data = $this->productRepositoryInterface->index($perPage);

        if ($data->isEmpty()) {
            return ApiResponseClass::sendResponse('No Products Available', '', 200);
        }

        return ApiResponseClass::sendResponse(ProductResource::collection($data)->response()->getData(true), '', 200);
    }

    /**
     * @OA\Post(
     *     path="/products",
     *     summary="Store a new product",
     *     tags={"Products"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Product data",
     *         @OA\JsonContent(
     *             required={"name", "details"},
     *             @OA\Property(property="name", type="string", example="Product Name"),
     *             @OA\Property(property="details", type="string", example="Product Details")
     *         )
     *     ),
     *     @OA\Response(response="201", description="Product created successfully"),
     *     @OA\Response(response="422", description="Validation error"),
     *     @OA\Response(response="500", description="Server error")
     * )
     */
    public function store(StoreProductRequest $request)
    {
        $details = [
            'name' => $request->name,
            'details' => $request->details
        ];
        DB::beginTransaction();
        try {
            $product = $this->productRepositoryInterface->store($details);
            DB::commit();
            return ApiResponseClass::sendResponse(new ProductResource($product), 'Product Create Successful', 201);
        } catch (\Exception $ex) {
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * @OA\Get(
     *     path="/products/{id}",
     *     summary="Display a specific product",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the product",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Product details"),
     *     @OA\Response(response="404", description="Product not found")
     * )
     */
    public function show($id)
    {
        $product = $this->productRepositoryInterface->getById($id);

        if ($product) {
            return ApiResponseClass::sendResponse(new ProductResource($product), '', 200);
        }

        return ApiResponseClass::sendResponse('Product Not Found', '', 404);
    }

    /**
     * @OA\Put(
     *     path="/products/{id}",
     *     summary="Update a specific product",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the product",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 description="Name of the product"
     *             ),
     *             @OA\Property(
     *                 property="details",
     *                 type="string",
     *                 description="Details of the product"
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Product updated successfully"),
     *     @OA\Response(response="404", description="Product not found"),
     *     @OA\Response(response="500", description="Internal server error")
     * )
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $updateDetails = [
            'name' => $request->name,
            'details' => $request->details
        ];

        DB::beginTransaction();

        try {
            $product = $this->productRepositoryInterface->update($updateDetails, $id);

            if ($product) {
                DB::commit();
                return ApiResponseClass::sendResponse(new ProductResource($product), 'Product Update Successful', 200);
            } else {
                return ApiResponseClass::sendResponse('Product Not Found', '', 404);
            }
        } catch (\Exception $ex) {
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * @OA\Delete(
     *     path="/products/{id}",
     *     summary="Delete a specific product",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the product",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Product deleted successfully"),
     *     @OA\Response(response="404", description="Product not found"),
     *     @OA\Response(response="500", description="Internal server error")
     * )
     */
    public function destroy($id)
    {
        $productDeleted = $this->productRepositoryInterface->delete($id);

        if ($productDeleted) {
            return ApiResponseClass::sendResponse('Product Deleted Successfully', '', 200);
        }

        return ApiResponseClass::sendResponse('Product Not Found, Failed to Delete Product', '', 404);
    }
}