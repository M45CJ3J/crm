<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCrudTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_product_with_a_category()
    {
        // Create a category
        $category = Category::create(['name' => 'Electronics']);

        // Create a product and assign it to the category
        $response = $this->post('/products', [
            'name' => 'Laptop',
            'description' => 'A high-performance laptop',
            'price' => 1000,
            'category_id' => $category->id,
        ]);

        $response->assertStatus(302); // Check for redirect status

        $this->assertDatabaseHas('products', [
            'name' => 'Laptop',
            'price' => 1000,
        ]);

        $product = Product::where('name', 'Laptop')->first();
        $this->assertEquals($category->id, $product->category_id);
    }

    /** @test */
    public function it_can_read_a_product_and_its_category()
    {
        $category = Category::create(['name' => 'Appliances']);
        $product = Product::create([
            'name' => 'Washing Machine',
            'description' => 'A top-load washing machine',
            'price' => 500,
            'category_id' => $category->id,
        ]);

        $response = $this->get('/products/' . $product->id);

        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee($category->name);
    }

    /** @test */
    public function it_can_update_a_product_and_its_category()
    {
        $category = Category::create(['name' => 'Furniture']);
        $newCategory = Category::create(['name' => 'Home Decor']);
        $product = Product::create([
            'name' => 'Sofa',
            'description' => 'A comfortable sofa',
            'price' => 300,
            'category_id' => $category->id,
        ]);

        $response = $this->put('/products/' . $product->id, [
            'name' => 'Luxury Sofa',
            'description' => 'A luxurious and comfortable sofa',
            'price' => 600,
            'category_id' => $newCategory->id,
        ]);

        $response->assertStatus(302); // Check for redirect status

        $product->refresh();
        $this->assertEquals('Luxury Sofa', $product->name);
        $this->assertEquals($newCategory->id, $product->category_id);
    }

    /** @test */
    public function it_can_delete_a_product()
    {
        $product = Product::create([
            'name' => 'Table',
            'description' => 'A wooden dining table',
            'price' => 150,
            'category_id' => Category::create(['name' => 'Dining'])->id,
        ]);

        $response = $this->delete('/products/' . $product->id);

        $response->assertStatus(302); // Check for redirect status
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
