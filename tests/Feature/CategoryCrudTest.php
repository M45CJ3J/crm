<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryCrudTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_category()
    {
        $response = $this->post('/categories', [
            'name' => 'Furniture',
        ]);

        $response->assertStatus(302); // Check for redirect status

        $this->assertDatabaseHas('categories', [
            'name' => 'Furniture',
        ]);
    }

    /** @test */
    public function it_can_read_a_category()
    {
        $category = Category::create(['name' => 'Office Supplies']);

        $response = $this->get('/categories/' . $category->id);

        $response->assertStatus(200);
        $response->assertSee($category->name);
    }

    /** @test */
    public function it_can_update_a_category()
    {
        $category = Category::create(['name' => 'Stationery']);

        $response = $this->put('/categories/' . $category->id, [
            'name' => 'Office Stationery',
        ]);

        $response->assertStatus(302); // Check for redirect status

        $category->refresh();
        $this->assertEquals('Office Stationery', $category->name);
    }

    /** @test */
    public function it_can_delete_a_category()
    {
        $category = Category::create(['name' => 'Gardening']);

        $response = $this->delete('/categories/' . $category->id);

        $response->assertStatus(302); // Check for redirect status
        $this->assertDatabaseMissing('categories', ['id' => $category->id]); 
    }
}
