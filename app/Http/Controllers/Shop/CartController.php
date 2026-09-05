<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        return view('shop.cart');
    }

    public function getSummary()
    {
        return response()->json($this->cartService->getCartSummary());
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'nullable|integer|min:1',
        ]);

        $product = Product::with('primaryImage')->find($validated['product_id']);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $qty = $request->input('quantity', 1);

        $summary = $this->cartService->addOrUpdateProduct(
            $product->id,
            $qty,
            $product->sale_price,
            $product->name,
            $product->primaryImage?->path ?? null
        );

        return response()->json($summary);
    }

    public function remove(int $productId)
    {
        $summary = $this->cartService->removeProduct($productId);
        return response()->json($summary);
    }

    public function updateQuantity(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'quantity'   => 'required|integer|min:1',
        ]);

        // Update Redis Cart logic
        $summary = $this->cartService->updateProductQuantity(
            (int) $request->product_id,
            (int) $request->quantity
        );

        return response()->json($summary);
    }
}
