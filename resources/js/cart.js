document.addEventListener('alpine:init', () => {
    Alpine.store('cart', {
        items: [],
        totalCount: 0,
        totalAmount: 0,
        loading: false,

        // Page load par Redis se cart summary fetch karein
        async init() {
            try {
                let res = await fetch('/cart/summary');
                let data = await res.json();
                this.updateState(data);
            } catch (e) {
                console.error("Cart load error:", e);
            }
        },

        // Item Add karne ka function
        async addItem(productId, quantity = 1) {
            try {
                let res = await fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ product_id: productId, quantity: quantity })
                });

                let data = await res.json();
                this.updateState(data);
            } catch (e) {
                console.error("Add to cart error:", e);
            }
        },

        // Item Quantity Update on cart page
        async updateQuantity(productId, newQty) {
            if (newQty < 1) return;
            this.loading = true;

            try {
                let res = await fetch('/cart/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ product_id: parseInt(productId), quantity: parseInt(newQty) })
                });

                let data = await res.json();
                this.updateState(data);
            } catch (e) {
                console.error("Update quantity error:", e);
            } finally {
                this.loading = false;
            }
        },

        // Item Remove karne ka function
        async removeItem(productId) {
            try {
                let res = await fetch(`/cart/remove/${productId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                let data = await res.json();
                this.updateState(data);
            } catch (e) {
                console.error("Remove item error:", e);
            }
        },

        updateState(data) {
            this.items = data.items || [];
            this.totalCount = data.total_count || 0;
            this.totalAmount = data.total_amount || 0;
        }
    });
});