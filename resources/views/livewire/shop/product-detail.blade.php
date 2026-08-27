<div class="w-full">
    <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">

        <!-- Breadcrumb Navigation -->
        <nav class="flex items-center gap-2 text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-8">
            <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Home</a>
            <flux:icon icon="chevron-right" class="size-3 text-zinc-400" />
            <a href="{{ route('home') }}#categories" class="hover:text-blue-600 dark:hover:text-blue-400 transition">
                {{ $product->category?->name ?? 'Category' }}
            </a>
            <flux:icon icon="chevron-right" class="size-3 text-zinc-400" />
            <span class="text-zinc-900 dark:text-white font-semibold truncate max-w-xs sm:max-w-md">{{ $product->name }}</span>
        </nav>

        <!-- Main Product Section -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 mb-16">

            <!-- Left: Image Gallery Area (Cols 1-6) -->
            <div class="lg:col-span-6 space-y-4">
                @php
                $categoryIcon = 'shopping-bag';
                $gradientFrom = 'from-zinc-800';
                $gradientTo = 'to-zinc-950';

                if ($product->category?->name === 'Networking') {
                $gradientFrom = 'from-blue-900';
                $gradientTo = 'to-zinc-950';
                $categoryIcon = 'wifi';
                } elseif ($product->category?->name === 'CCTV & Security') {
                $gradientFrom = 'from-emerald-900';
                $gradientTo = 'to-zinc-950';
                $categoryIcon = 'video-camera';
                } elseif ($product->category?->name === 'Storage') {
                $gradientFrom = 'from-purple-900';
                $gradientTo = 'to-zinc-950';
                $categoryIcon = 'circle-stack';
                } elseif ($product->category?->name === 'Computer Peripherals') {
                $gradientFrom = 'from-amber-900';
                $gradientTo = 'to-zinc-950';
                $categoryIcon = 'computer-desktop';
                } elseif ($product->category?->name === 'Power & Accessories') {
                $gradientFrom = 'from-orange-900';
                $gradientTo = 'to-zinc-950';
                $categoryIcon = 'bolt';
                } elseif ($product->category?->name === 'Printing') {
                $gradientFrom = 'from-indigo-900';
                $gradientTo = 'to-zinc-950';
                $categoryIcon = 'printer';
                }

                // Helper to get image URL
                $getImageUrl = function($path) {
                if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                return $path;
                }
                if (str_starts_with($path, 'storage/')) {
                return asset($path);
                }
                return asset('storage/' . $path);
                };

                $hasImages = $selectedImage || $product->images->isNotEmpty() || $product->primaryImage;
                @endphp

                <!-- Main Viewport Box -->
                <div class="relative aspect-4/3 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 overflow-hidden flex items-center justify-center p-6 group">
                    @if($selectedImage)
                    <img
                        src="{{ $getImageUrl($selectedImage) }}"
                        alt="{{ $product->name }}"
                        class="size-full object-contain group-hover:scale-105 transition-transform duration-500" />
                    @else
                    <!-- High-End Gradient Placeholder if product image does not exist -->
                    <div class="absolute inset-0 bg-gradient-to-br {{ $gradientFrom }} {{ $gradientTo }} flex items-center justify-center p-8 text-white">
                        <!-- Overlay Grid -->
                        <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:20px_20px]"></div>
                        <div class="absolute size-40 rounded-full bg-blue-500/10 blur-3xl pointer-events-none"></div>

                        <div class="relative z-10 p-8 rounded-3xl bg-white/10 border border-white/20 backdrop-blur-md shadow-2xl scale-125 group-hover:scale-135 transition-transform duration-500">
                            <flux:icon icon="{{ $categoryIcon }}" class="size-16 text-white" />
                        </div>

                        <span class="absolute bottom-6 left-6 text-xs font-semibold uppercase tracking-wider text-white/60 bg-white/10 px-3 py-1 rounded-full backdrop-blur-xs">
                            {{ $product->brand?->name ?? 'Enterprise Hardware' }}
                        </span>
                    </div>
                    @endif

                    <!-- Stock & Discount Badges over Main Viewport -->
                    <div class="absolute top-4 right-4 flex flex-col gap-2 items-end z-20">
                        @php
                        $discount = $product->mrp > 0 ? round((($product->mrp - $product->sale_price) / $product->mrp) * 100) : 0;
                        @endphp
                        @if($discount > 0)
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-extrabold bg-rose-500 text-white shadow-md">
                            {{ $discount }}% OFF
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Thumbnails Gallery -->
                @if($product->images->isNotEmpty())
                <div class="grid grid-cols-5 gap-3 pt-2">
                    @foreach($product->images as $img)
                    @php
                    $imgUrl = $getImageUrl($img->path);
                    $isSelected = $selectedImage === $img->path;
                    @endphp
                    <button
                        type="button"
                        wire:click="selectImage('{{ addslashes($img->path) }}')"
                        class="relative aspect-square rounded-xl border overflow-hidden transition-all duration-200 cursor-pointer bg-white dark:bg-zinc-900 p-1 {{ $isSelected ? 'border-blue-600 ring-1 ring-blue-600/50 scale-105' : 'border-zinc-200 dark:border-zinc-800 hover:border-zinc-400 dark:hover:border-zinc-600' }}">
                        <img src="{{ $imgUrl }}" alt="Thumbnail" class="size-full object-contain rounded-lg">
                    </button>
                    @endforeach
                </div>
                @endif

                <!-- Guarantee / Trust Icons Under Gallery -->
                <div class="grid grid-cols-3 gap-4 pt-6 border-t border-zinc-200 dark:border-zinc-800 text-center">
                    <div class="p-3 rounded-2xl bg-zinc-100/70 dark:bg-zinc-900/70 border border-zinc-200/50 dark:border-zinc-800/50">
                        <flux:icon icon="shield-check" class="size-5 text-blue-600 dark:text-blue-400 mx-auto mb-1" />
                        <span class="block text-[11px] font-bold text-zinc-900 dark:text-white">100% Genuine</span>
                        <span class="text-[10px] text-zinc-500">Official Brand Warranty</span>
                    </div>
                    <div class="p-3 rounded-2xl bg-zinc-100/70 dark:bg-zinc-900/70 border border-zinc-200/50 dark:border-zinc-800/50">
                        <flux:icon icon="bolt" class="size-5 text-emerald-600 dark:text-emerald-400 mx-auto mb-1" />
                        <span class="block text-[11px] font-bold text-zinc-900 dark:text-white">Fast Dispatch</span>
                        <span class="text-[10px] text-zinc-500">Express Freight Shipping</span>
                    </div>
                    <div class="p-3 rounded-2xl bg-zinc-100/70 dark:bg-zinc-900/70 border border-zinc-200/50 dark:border-zinc-800/50">
                        <flux:icon icon="circle-stack" class="size-5 text-purple-600 dark:text-purple-400 mx-auto mb-1" />
                        <span class="block text-[11px] font-bold text-zinc-900 dark:text-white">Bulk Discount</span>
                        <span class="text-[10px] text-zinc-500">Corporate Quotes</span>
                    </div>
                </div>
            </div>

            <!-- Right: Product Information & CTAs (Cols 7-12) -->
            <div class="lg:col-span-6 space-y-6 flex flex-col justify-between">
                <div class="space-y-6">

                    <!-- Header badges -->
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-900/30">
                            <flux:icon icon="{{ $categoryIcon }}" class="size-3.5" />
                            {{ $product->category?->name ?? 'Hardware' }}
                        </span>
                        @if($product->brand)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300">
                            {{ $product->brand->name }}
                        </span>
                        @endif
                        <span class="text-xs text-zinc-400 font-medium ml-auto">
                            SKU: <span class="font-mono text-zinc-600 dark:text-zinc-300">{{ $product->sku ?? 'N/A' }}</span>
                        </span>
                    </div>

                    <!-- Title -->
                    <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-zinc-950 dark:text-white leading-tight">
                        {{ $product->name }}
                    </h1>

                    <!-- Pricing Box -->
                    <div class="p-6 rounded-3xl bg-zinc-100/80 dark:bg-zinc-900/80 border border-zinc-200/80 dark:border-zinc-800/80 backdrop-blur-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <div class="text-xs text-zinc-500 dark:text-zinc-400 font-medium mb-1">Corporate Offer Price</div>
                            <div class="flex items-baseline gap-3">
                                <span class="text-3xl sm:text-4xl font-black tracking-tight text-zinc-950 dark:text-white">
                                    ₹{{ number_format($product->sale_price, 2) }}
                                </span>
                                @if($product->mrp > $product->sale_price)
                                <span class="text-lg text-zinc-400 line-through">
                                    ₹{{ number_format($product->mrp, 2) }}
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- Stock Badge -->
                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 w-fit">
                            <span class="size-2.5 rounded-full {{ $product->stock > 0 ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' }}"></span>
                            <span class="text-xs font-bold {{ $product->stock > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                {{ $product->stock > 0 ? 'In Stock ('.$product->stock.' available)' : 'Out of Stock' }}
                            </span>
                        </div>
                    </div>

                    <!-- Overview / Short Description -->
                    <div class="space-y-2">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Overview</h3>
                        <p class="text-sm sm:text-base text-zinc-600 dark:text-zinc-300 leading-relaxed font-normal">
                            {{ $product->short_description ?? 'Enterprise-grade hardware device designed for maximum reliability and seamless performance.' }}
                        </p>
                    </div>

                    <!-- Quantity Control & Enquire CTAs -->
                    <div class="space-y-4 pt-4 border-t border-zinc-200 dark:border-zinc-800">
                        @unless(auth()->check() && auth()->user()->hasRole('super-admin'))
                        <div class="flex items-center gap-4">
                            <span class="text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Quantity</span>
                            <div class="flex items-center border border-zinc-200 dark:border-zinc-800 rounded-xl bg-white dark:bg-zinc-900 p-1 shadow-sm">
                                <button type="button" wire:click="decrementQuantity" class="size-6 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-600 dark:text-zinc-300 font-bold transition">
                                    -
                                </button>
                                <span class="w-10 text-center text-sm font-bold text-zinc-900 dark:text-white">{{ $quantity }}</span>
                                <button type="button" wire:click="incrementQuantity" class="size-6 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-600 dark:text-zinc-300 font-bold transition">
                                    +
                                </button>
                            </div>
                        </div>

                        <!-- Buttons Row for Guests & Customers -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <flux:button wire:click="addToCart" icon="shopping-cart" class="cursor-pointer">
                                Add to Cart
                            </flux:button>
                            <flux:button wire:click="toggleWishlist" icon="heart" variant="outline" class="cursor-pointer {{ \App\Support\WishlistManager::contains($product->id) ? 'text-rose-500 hover:text-rose-600 [&>svg]:fill-rose-500' : '' }}">
                                {{ \App\Support\WishlistManager::contains($product->id) ? 'Wishlisted' : 'Add to Wishlist' }}
                            </flux:button>
                            <flux:button wire:click="toggleComparison" icon="scale" variant="outline" class="cursor-pointer {{ in_array($product->id, session()->get('compared_product_ids', []), true) ? 'text-blue-500 hover:text-blue-600' : '' }}">
                                {{ in_array($product->id, session()->get('compared_product_ids', []), true) ? 'Compared' : 'Compare' }}
                            </flux:button>
                        </div>
                        @endunless

                        @auth
                        @role('super-admin')
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <flux:button href="{{ route('dashboard.products.show', $product->id) }}" class="cursor-pointer" wire:navigate>
                                View in Dashboard
                            </flux:button>
                        </div>
                        @endrole
                        @endauth
                    </div>

                </div>

                <!-- HSN & Tax Note -->
                @if($product->hsn_code)
                <div class="text-xs text-zinc-500 dark:text-zinc-400 pt-4 border-t border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                    <span>HSN Code: <strong class="text-zinc-700 dark:text-zinc-300 font-mono">{{ $product->hsn_code }}</strong></span>
                    <span>Applicable GST & Tax Included</span>
                </div>
                @endif

            </div>

        </div>

        <!-- Technical Specifications & Detailed Description Section -->
        <section class="mb-16 space-y-12">

            <!-- Specifications Table -->
            @if($product->specifications->isNotEmpty())

            <h3 class="text-xl font-bold tracking-tight text-zinc-900 dark:text-white mb-6 flex items-center gap-2">
                <flux:icon icon="cog" class="size-5 text-blue-600" />
                Specifications
            </h3>

            <div class="columns-1 lg:columns-2 gap-6 mb-16">
                @foreach($product->specifications->groupBy('group_name') as $groupName => $specs)
                <div class="break-inside-avoid-column mb-6">
                    <div class="border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden shadow-xs">
                        <table class="w-full text-sm text-left border-collapse">
                            <tbody>
                                <tr class="bg-zinc-100 dark:bg-zinc-900 font-bold">
                                    <td colspan="2" class="px-6 py-3">
                                        {{ $groupName }}
                                    </td>
                                </tr>

                                @foreach($specs as $spec)
                                <tr class="border-b border-zinc-100 dark:border-zinc-800 last:border-0 hover:bg-zinc-50/80 dark:hover:bg-zinc-800/40 transition">
                                    <td class="px-6 py-3.5 text-zinc-500 dark:text-zinc-400 bg-zinc-50/50 dark:bg-zinc-800/20 border-r border-zinc-100 dark:border-zinc-800">
                                        {{ $spec->key }}
                                    </td>
                                    <td class="px-6 py-3.5 text-zinc-900 dark:text-zinc-200">
                                        {{ $spec->value }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Full Description Block -->
            @if($product->description)
            <div class="rounded-3xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 sm:p-8 shadow-sm">
                <h3 class="text-xl font-bold tracking-tight text-zinc-900 dark:text-white mb-4 flex items-center gap-2">
                    <flux:icon icon="book-open-text" class="size-5 text-blue-600" />
                    Detailed Product Information
                </h3>
                <div class="text-sm sm:text-base text-zinc-600 dark:text-zinc-300 leading-relaxed space-y-4">
                    {!! $product->description !!}
                </div>
            </div>
            @endif

        </section>

        <!-- Customer Ratings & Reviews Section -->
        <section id="reviews" class="mb-16 pt-12 border-t border-zinc-200 dark:border-zinc-800">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                
                <!-- Left Column: Summary & Submission Form (Cols 1-5) -->
                <div class="lg:col-span-5 space-y-8">
                    <div>
                        <h3 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">Customer Reviews</h3>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Real feedback from verified corporate buyers.</p>
                    </div>

                    <!-- Overall Rating Card -->
                    @php
                        $reviews = $product->reviews;
                        $totalReviews = $reviews->count();
                        $averageRating = $totalReviews > 0 ? $reviews->avg('rating') : 0;
                    @endphp
                    <div class="p-6 rounded-3xl bg-zinc-100/80 dark:bg-zinc-900/80 border border-zinc-200 dark:border-zinc-800 flex items-center gap-6">
                        <div class="text-center">
                            <span class="text-4xl font-black text-zinc-950 dark:text-white">{{ number_format($averageRating, 1) }}</span>
                            <div class="flex items-center justify-center gap-1 mt-1 text-amber-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <flux:icon icon="star" class="size-4 {{ $i <= round($averageRating) ? 'fill-current' : 'text-zinc-300 dark:text-zinc-700' }}" />
                                @endfor
                            </div>
                            <span class="text-xs text-zinc-500 mt-1 block">{{ $totalReviews }} Review(s)</span>
                        </div>
                        <div class="flex-1 border-l border-zinc-200 dark:border-zinc-800 pl-6 space-y-1.5 text-xs text-zinc-500">
                            <div class="flex items-center justify-between">
                                <span>Quality & Durability</span>
                                <span class="font-bold text-zinc-900 dark:text-white">4.9 / 5.0</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Shipping & Packaging</span>
                                <span class="font-bold text-zinc-900 dark:text-white">4.8 / 5.0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Write a Review Box -->
                    @auth
                        @role('customer')
                        <div class="p-6 rounded-3xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm space-y-4">
                            <h4 class="text-base font-bold text-zinc-900 dark:text-white">Leave a Review</h4>
                            
                            <form wire:submit="submitReview" class="space-y-4">
                                <!-- Interactive Star Rating Selector -->
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-2">Rating</label>
                                    <select wire:model="rating" class="w-full text-sm border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900 dark:text-white rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="5">⭐⭐⭐⭐⭐ - 5 Star (Exceptional)</option>
                                        <option value="4">⭐⭐⭐⭐ - 4 Star (Good)</option>
                                        <option value="3">⭐⭐⭐ - 3 Star (Average)</option>
                                        <option value="2">⭐⭐ - 2 Star (Below Expectations)</option>
                                        <option value="1">⭐ - 1 Star (Poor)</option>
                                    </select>
                                    @error('rating') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Comment Textarea -->
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-2">Review Comment</label>
                                    <textarea wire:model="comment" rows="4" placeholder="Write details about your experience with this hardware..." class="w-full text-sm border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900 dark:text-white rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                                    @error('comment') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <flux:button type="submit" variant="primary" class="w-full cursor-pointer">
                                    Post Review
                                </flux:button>
                            </form>
                        </div>
                        @endrole
                    @else
                        <div class="p-6 rounded-3xl border border-dashed border-zinc-200 dark:border-zinc-800 text-center bg-zinc-50/50 dark:bg-zinc-900/50">
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-3">Want to share your experience with this device?</p>
                            <flux:button href="{{ route('login') }}" size="sm" variant="outline" wire:navigate>Login to Write a Review</flux:button>
                        </div>
                    @endauth
                </div>

                <!-- Right Column: User Reviews Feed (Cols 6-12) -->
                <div class="lg:col-span-7 space-y-4">
                    <h4 class="text-lg font-bold text-zinc-900 dark:text-white mb-6">Verified Buyer Feedback</h4>

                    <div class="space-y-4 max-h-[600px] overflow-y-auto pr-2">
                        @forelse($reviews as $review)
                        <div class="p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-xs space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="size-10 rounded-full bg-blue-100 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-sm">
                                        {{ substr($review->user->name ?? 'U', 0, 1) }}
                                    </div>
                                    <div>
                                        <h5 class="text-sm font-bold text-zinc-900 dark:text-white">{{ $review->user->name ?? 'Verified Buyer' }}</h5>
                                        <span class="text-xs text-zinc-400">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>

                                <!-- Star Badges & Owner Action Icons -->
                                <div class="flex items-center gap-3">
                                    @if($editingReviewId !== $review->id)
                                        <div class="flex items-center gap-1 text-amber-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                <flux:icon icon="star" class="size-3.5 {{ $i <= $review->rating ? 'fill-current' : 'text-zinc-300 dark:text-zinc-700' }}" />
                                            @endfor
                                        </div>
                                    @endif

                                    @auth
                                        @if($review->user_id === auth()->id())
                                            <div class="flex items-center gap-1.5 pl-2 border-l border-zinc-200 dark:border-zinc-800">
                                                @if($editingReviewId !== $review->id)
                                                    <button type="button" wire:click="editReview({{ $review->id }})" class="p-1 text-zinc-400 hover:text-blue-600 dark:hover:text-blue-400 transition cursor-pointer" title="Edit Review">
                                                        <flux:icon icon="pencil-square" class="size-4" />
                                                    </button>
                                                    <button type="button" wire:click="deleteReview({{ $review->id }})" wire:confirm="Are you sure you want to delete your review?" class="p-1 text-zinc-400 hover:text-rose-600 dark:hover:text-rose-400 transition cursor-pointer" title="Delete Review">
                                                        <flux:icon icon="trash" class="size-4" />
                                                    </button>
                                                @endif
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                            </div>

                            <!-- Inline Edit Form or Normal Comment View -->
                            @if($editingReviewId === $review->id)
                                <div wire:key="edit-review-{{ $review->id }}" class="space-y-3 pt-2">
                                    <div>
                                        <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-1">Update Rating</label>
                                        <select wire:model="editRating" class="w-full text-sm border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900 dark:text-white rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="5">⭐⭐⭐⭐⭐ - 5 Star</option>
                                            <option value="4">⭐⭐⭐⭐ - 4 Star</option>
                                            <option value="3">⭐⭐⭐ - 3 Star</option>
                                            <option value="2">⭐⭐ - 2 Star</option>
                                            <option value="1">⭐ - 1 Star</option>
                                        </select>
                                        @error('editRating') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-1">Update Comment</label>
                                        <textarea wire:model="editComment" rows="3" class="w-full text-sm border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900 dark:text-white rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                                        @error('editComment') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="flex items-center gap-2 pt-1">
                                        <flux:button size="sm" variant="primary" wire:click="updateReview" class="cursor-pointer">Save Changes</flux:button>
                                        <flux:button size="sm" variant="subtle" wire:click="cancelEdit" class="cursor-pointer">Cancel</flux:button>
                                    </div>
                                </div>
                            @else
                                <p class="text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                                    {{ $review->comment }}
                                </p>
                            @endif
                        </div>
                        @empty
                        <div class="p-12 text-center rounded-3xl border border-dashed border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50">
                            <flux:icon icon="chat-bubble-bottom-center-text" class="size-10 text-zinc-400 mx-auto mb-3" />
                            <h5 class="text-base font-semibold text-zinc-900 dark:text-white">No reviews yet</h5>
                            <p class="text-sm text-zinc-500 mt-1">Be the first corporate buyer to review this product!</p>
                        </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </section>

        <!-- Related Products Section (Matching Homepage Card Layout) -->
        @if($relatedProducts->isNotEmpty())
        <section class="border-t border-zinc-200 dark:border-zinc-800 pt-12">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">Related Products</h2>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">Other devices in {{ $product->category?->name ?? 'this category' }}</p>
                </div>

                <flux:button size="sm" href="{{ route('shop.products') }}" wire:navigate>View All Products</flux:button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $relProduct)
                <x-shop.product-card :product="$relProduct" wire:key="product-{{ $relProduct->id }}" />
                @endforeach
            </div>
        </section>
        @endif

    </main>
</div>