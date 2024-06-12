<?php

namespace Tests\Feature;

use Mockery;
use Tests\TestCase;
use App\Models\Product;
use App\Classes\ApiResponseClass;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ProductController;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Foundation\Testing\WithFaker;
use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $productRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productRepositoryMock = Mockery::mock(ProductRepositoryInterface::class);
        $this->app->instance(ProductRepositoryInterface::class, $this->productRepositoryMock);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testIndexReturnsNoProductsAvailable()
    {
        $this->productRepositoryMock
            ->shouldReceive('index')
            ->once()
            ->andReturn(new LengthAwarePaginator(collect(), 0, 10));

        $response = $this->getJson(route('products.index'));

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => 'No Products Available',
                 ]);
    }

    public function testIndexReturnsProducts()
    {
        $products = Collection::make([
            (object)['id' => 1, 'name' => 'Product 1', 'details' => 'details 1'],
            (object)['id' => 2, 'name' => 'Product 2', 'details' => 'details 2'],
        ]);

        $paginator = new LengthAwarePaginator($products, $products->count(), 10);

        $this->productRepositoryMock
            ->shouldReceive('index')
            ->once()
            ->with(10)
            ->andReturn($paginator);

        $response = $this->getJson(route('products.index'));

        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data.data')
                 ->assertJson([
                     'data' => [
                         'data' => [
                             ['id' => 1, 'name' => 'Product 1', 'details' => 'details 1'],
                             ['id' => 2, 'name' => 'Product 2', 'details' => 'details 2'],
                         ],
                     ],
                 ]);
    }

    public function testShowProductFound()
    {
        $productId = 1;
        $productData = [
            'id' => $productId,
            'name' => 'Product 1',
            'details' => 'Product details',
        ];

        $this->productRepositoryMock
            ->shouldReceive('getById')
            ->once()
            ->with($productId)
            ->andReturn((object)$productData);

        $response = $this->getJson(route('products.show', $productId));

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'data' => $productData,
                ]);
    }

    public function testShowProductNotFound()
    {
        $productId = 999;

        $this->productRepositoryMock
            ->shouldReceive('getById')
            ->once()
            ->with($productId)
            ->andReturn(null);

        $response = $this->getJson(route('products.show', $productId));

        $response->assertStatus(404)
                ->assertJson([
                    'success' => true, 
                    'data' => 'Product Not Found',
                ]);
    }

    public function testStoreCreatesProduct()
    {
        $productData = [
            'name' => 'New Product',
            'details' => 'New product details',
        ];

        $product = new Product();
        $product->id = 1;
        $product->name = 'New Product';
        $product->details = 'New product details';

        $this->productRepositoryMock
            ->shouldReceive('store')
            ->once()
            ->with($productData)
            ->andReturn($product);

        $response = $this->postJson(route('products.store'), $productData);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'id' => 1,
                         'name' => 'New Product',
                         'details' => 'New product details',
                     ],
                     'message' => 'Product Create Successful',
                 ]);
    }

    public function testStoreValidationErrors()
    {
        $invalidData = [];

        $response = $this->postJson(route('products.store'), $invalidData);

        $response->assertStatus(422)
                ->assertJson([
                    'success' => false,
                    'message' => 'Validation errors',
                    'data'  => [
                        'name' => ['The name field is required.'],
                        'details' => ['The details field is required.']
                    ]
                ]);
    }

    public function testUpdateProduct()
    {
        $productId = 1;
        $requestData = [
            'name' => 'Updated Product Name',
            'details' => 'Updated product details',
        ];

        $updatedProduct = new Product();
        $updatedProduct->id = $productId;
        $updatedProduct->name = $requestData['name'];
        $updatedProduct->details = $requestData['details'];

        $this->productRepositoryMock
            ->shouldReceive('update')
            ->once()
            ->with($requestData, $productId)
            ->andReturn($updatedProduct);

        $response = $this->putJson(route('products.update', $productId), $requestData);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'data' => [
                        'id' => $productId,
                        'name' => 'Updated Product Name',
                        'details' => 'Updated product details',
                    ],
                    'message' => 'Product Update Successful',
                ]);
    }

    public function testUpdateValidationErrors()
    {
        $product = [
            'id' => 1,
            'name' => 'Initial Product Name',
            'details' => 'Initial Product Details'
        ];

        $invalidData = [
            'id' => $product['id'],
        ];

        $response = $this->putJson(route('products.update', ['product' => $product['id']]), $invalidData);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation errors',
                'data'  => [
                    'name' => ['The name field is required.'],
                    'details' => ['The details field is required.']
                ]
            ]);
    }

    public function testDestroy()
    {
        $mockRepository = $this->getMockBuilder(ProductRepositoryInterface::class)
                            ->disableOriginalConstructor()
                            ->getMock();
        
        $controller = new ProductController($mockRepository);

        $mockRepository->expects($this->once())
                    ->method('delete')
                    ->with(1) 
                    ->willReturn(true); 

        $response = $controller->destroy(1);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Product Deleted Successfully', $response->getData()->data);
    }

    public function testDestroyProductNotFound()
    {
        $mockRepository = $this->getMockBuilder(ProductRepositoryInterface::class)
                            ->disableOriginalConstructor()
                            ->getMock();
        
        $controller = new ProductController($mockRepository);

        $mockRepository->expects($this->once())
                    ->method('delete')
                    ->with(1) 
                    ->willReturn(false);

        $response = $controller->destroy(1); 

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('Product Not Found, Failed to Delete Product', $response->getData()->data);
    }

}
