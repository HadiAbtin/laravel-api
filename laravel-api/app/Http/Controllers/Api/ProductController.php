<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * Get all products
     */
    public function index(): JsonResponse
    {
        $products = [
            [
                'id' => 1,
                'name' => 'Laptop Pro',
                'description' => 'High-performance laptop for professionals',
                'price' => 1299.99,
                'category' => 'Electronics',
                'stock' => 50,
                'sku' => 'LAPTOP-PRO-001',
                'created_at' => '2024-01-15T10:30:00Z'
            ],
            [
                'id' => 2,
                'name' => 'Wireless Mouse',
                'description' => 'Ergonomic wireless mouse with precision tracking',
                'price' => 29.99,
                'category' => 'Accessories',
                'stock' => 200,
                'sku' => 'MOUSE-WIRELESS-001',
                'created_at' => '2024-01-16T14:20:00Z'
            ],
            [
                'id' => 3,
                'name' => 'Mechanical Keyboard',
                'description' => 'RGB mechanical keyboard with tactile switches',
                'price' => 149.99,
                'category' => 'Accessories',
                'stock' => 75,
                'sku' => 'KEYBOARD-MECH-001',
                'created_at' => '2024-01-17T09:15:00Z'
            ]
        ];

        return response()->json([
            'success' => true,
            'message' => 'Products retrieved successfully',
            'data' => $products,
            'total' => count($products),
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Get product by ID
     */
    public function show($id): JsonResponse
    {
        $id = (int) $id;
        $products = [
            1 => [
                'id' => 1,
                'name' => 'Laptop Pro',
                'description' => 'High-performance laptop for professionals',
                'price' => 1299.99,
                'category' => 'Electronics',
                'stock' => 50,
                'sku' => 'LAPTOP-PRO-001',
                'specifications' => [
                    'processor' => 'Intel Core i7',
                    'ram' => '16GB DDR4',
                    'storage' => '512GB SSD',
                    'display' => '15.6" 4K',
                    'battery' => '8 hours'
                ],
                'images' => [
                    'https://via.placeholder.com/400x300',
                    'https://via.placeholder.com/400x300'
                ],
                'created_at' => '2024-01-15T10:30:00Z'
            ],
            2 => [
                'id' => 2,
                'name' => 'Wireless Mouse',
                'description' => 'Ergonomic wireless mouse with precision tracking',
                'price' => 29.99,
                'category' => 'Accessories',
                'stock' => 200,
                'sku' => 'MOUSE-WIRELESS-001',
                'specifications' => [
                    'connectivity' => 'Bluetooth 5.0',
                    'battery' => '12 months',
                    'dpi' => '1600',
                    'buttons' => '3'
                ],
                'images' => [
                    'https://via.placeholder.com/400x300'
                ],
                'created_at' => '2024-01-16T14:20:00Z'
            ],
            3 => [
                'id' => 3,
                'name' => 'Mechanical Keyboard',
                'description' => 'RGB mechanical keyboard with tactile switches',
                'price' => 149.99,
                'category' => 'Accessories',
                'stock' => 75,
                'sku' => 'KEYBOARD-MECH-001',
                'specifications' => [
                    'switch_type' => 'Cherry MX Blue',
                    'layout' => 'Full-size',
                    'backlight' => 'RGB',
                    'connectivity' => 'USB-C'
                ],
                'images' => [
                    'https://via.placeholder.com/400x300',
                    'https://via.placeholder.com/400x300'
                ],
                'created_at' => '2024-01-17T09:15:00Z'
            ]
        ];

        if (!isset($products[$id])) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
                'error' => 'PRODUCT_NOT_FOUND'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product retrieved successfully',
            'data' => $products[$id],
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Create new product
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|max:50'
        ]);

        $newProduct = [
            'id' => rand(100, 999),
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'category' => $validated['category'],
            'stock' => $validated['stock'],
            'sku' => $validated['sku'],
            'created_at' => now()->toISOString()
        ];

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => $newProduct,
            'timestamp' => now()->toISOString()
        ], 201);
    }

    /**
     * Update product
     */
    public function update(Request $request, $id): JsonResponse
    {
        $id = (int) $id;
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'category' => 'sometimes|string|max:100',
            'stock' => 'sometimes|integer|min:0',
            'sku' => 'sometimes|string|max:50'
        ]);

        $updatedProduct = [
            'id' => $id,
            'name' => $validated['name'] ?? 'Updated Product',
            'description' => $validated['description'] ?? 'Updated description',
            'price' => $validated['price'] ?? 99.99,
            'category' => $validated['category'] ?? 'General',
            'stock' => $validated['stock'] ?? 0,
            'sku' => $validated['sku'] ?? 'UPDATED-SKU',
            'updated_at' => now()->toISOString()
        ];

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => $updatedProduct,
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Delete product
     */
    public function destroy($id): JsonResponse
    {
        $id = (int) $id;
        
        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully',
            'data' => ['id' => $id],
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Get product statistics
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'total_products' => 3,
            'total_categories' => 2,
            'total_stock_value' => 129999.75,
            'average_price' => 493.32,
            'low_stock_products' => 1,
            'categories' => [
                'Electronics' => 1,
                'Accessories' => 2
            ]
        ];

        return response()->json([
            'success' => true,
            'message' => 'Product statistics retrieved successfully',
            'data' => $stats,
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Search products
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        $category = $request->get('category', '');
        
        // Mock search results
        $results = [
            [
                'id' => 1,
                'name' => 'Laptop Pro',
                'description' => 'High-performance laptop for professionals',
                'price' => 1299.99,
                'category' => 'Electronics',
                'relevance_score' => 0.95
            ]
        ];

        return response()->json([
            'success' => true,
            'message' => 'Search completed successfully',
            'data' => $results,
            'query' => $query,
            'category' => $category,
            'total_results' => count($results),
            'timestamp' => now()->toISOString()
        ]);
    }
}
