// Cart management functions
class Cart {
    constructor() {
        this.items = [];
    }

    async loadCart() {
        try {
            const response = await fetch('api/cart.php');
            if (response.ok) {
                const data = await response.json();
                this.items = Array.isArray(data) ? data : [];
            } else {
                this.items = [];
            }
        } catch (error) {
            console.log('Cart not available from PHP, using local storage');
            this.items = [];
        }
    }

    async addProduct(product) {
        try {
            const response = await fetch('api/cart.php', {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'add',
                    id: product.id,
                    name: product.name,
                    code: product.code,
                    price: product.price
                })
            });
            const result = await response.json();
            this.items = result.cart;
            return true;
        } catch (error) {
            console.error('Error adding to cart:', error);
            return false;
        }
    }

    async removeProduct(productId) {
        try {
            const response = await fetch('api/cart.php', {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'remove',
                    id: productId
                })
            });
            const result = await response.json();
            this.items = result.cart;
        } catch (error) {
            console.error('Error removing from cart:', error);
        }
    }

    async updateQuantity(productId, quantity) {
        // Limit quantity to 0 or more
        const validQuantity = Math.max(0, parseInt(quantity));
        
        try {
            const response = await fetch('api/cart.php', {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'update',
                    id: productId,
                    quantity: validQuantity
                })
            });
            const result = await response.json();
            this.items = result.cart;
        } catch (error) {
            console.error('Error updating quantity:', error);
        }
    }

    getCartCount() {
        return this.items.reduce((total, item) => total + item.quantity, 0);
    }

    async clear() {
        try {
            const response = await fetch('api/cart.php', {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'clear'
                })
            });
            const result = await response.json();
            this.items = result.cart;
        } catch (error) {
            console.error('Error clearing cart:', error);
        }
    }
}

// Initialize cart
const cart = new Cart();

// Render products on catalog page
function renderProducts() {
    const productsGrid = document.getElementById('productsGrid');
    
    if (!productsGrid) {
        console.log('ProductsGrid element not found on this page');
        return;
    }

    console.log('Rendering products:', products.length);
    
    productsGrid.innerHTML = '';
    
    if (!products || products.length === 0) {
        productsGrid.innerHTML = '<p>No products available</p>';
        return;
    }

    products.forEach(product => {
        const cartItem = cart.items.find(item => item.id === product.id);
        const quantityInCart = cartItem ? cartItem.quantity : 0;

        const productCard = document.createElement('div');
        productCard.className = 'product-card';
        productCard.innerHTML = `
            <div class="product-header">
                <h3>${product.name}</h3>
                <span class="product-code">Code: ${product.code}</span>
            </div>
            <p class="product-description">${product.description}</p>
            <div class="product-info">
                <p class="product-id">ID: ${product.id}</p>
                <p class="product-price">$${product.price.toFixed(2)}</p>
            </div>
            <div class="quantity-info">
                <p>Quantity in Cart: <strong>${quantityInCart}</strong></p>
            </div>
            <div class="product-actions">
                <button class="btn btn-add" onclick="addToCart(${product.id})">Add to Cart</button>
                ${quantityInCart > 0 ? `
                    <div class="quantity-controls">
                        <button class="btn btn-small" onclick="decreaseQuantity(${product.id})">−</button>
                        <input type="number" class="qty-input" value="${quantityInCart}" 
                               onchange="updateCartQuantity(${product.id}, this.value)" min="0">
                        <button class="btn btn-small" onclick="increaseQuantity(${product.id})">+</button>
                    </div>
                    <button class="btn btn-remove" onclick="removeFromCart(${product.id})">Remove from Cart</button>
                ` : ''}
            </div>
        `;
        productsGrid.appendChild(productCard);
    });
}

// Add product to cart
async function addToCart(productId) {
    const product = products.find(p => p.id === productId);
    if (product) {
        await cart.addProduct(product);
        renderProducts();
    }
}

// Remove product from cart
async function removeFromCart(productId) {
    await cart.removeProduct(productId);
    renderProducts();
}

// Increase quantity
async function increaseQuantity(productId) {
    const item = cart.items.find(item => item.id === productId);
    if (item) {
        await cart.updateQuantity(productId, item.quantity + 1);
        renderProducts();
    }
}

// Decrease quantity
async function decreaseQuantity(productId) {
    const item = cart.items.find(item => item.id === productId);
    if (item) {
        if (item.quantity > 1) {
            await cart.updateQuantity(productId, item.quantity - 1);
        } else {
            await cart.removeProduct(productId);
        }
        renderProducts();
    }
}

// Update quantity via input
async function updateCartQuantity(productId, quantity) {
    await cart.updateQuantity(productId, quantity);
    renderProducts();
}

// Render cart page
function renderCart() {
    const cartContainer = document.getElementById('cartContainer');
    const cartSummary = document.getElementById('cartSummary');
    
    if (!cartContainer) return; // Not on cart page

    if (cart.items.length === 0) {
        cartContainer.innerHTML = '<p class="empty-cart">Your cart is empty. <a href="catalog.html">Continue shopping</a></p>';
        cartSummary.innerHTML = '';
        return;
    }

    let total = 0;
    let html = '<table class="cart-table"><thead><tr><th>Product</th><th>Code</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th>Action</th></tr></thead><tbody>';

    cart.items.forEach(item => {
        const subtotal = item.price * item.quantity;
        total += subtotal;
        html += `
            <tr>
                <td>${item.name}</td>
                <td>${item.code}</td>
                <td>$${item.price.toFixed(2)}</td>
                <td>
                    <div class="quantity-controls">
                        <button class="btn btn-small" onclick="decreaseQuantity(${item.id})">−</button>
                        <input type="number" class="qty-input" value="${item.quantity}" 
                               onchange="updateCartQuantity(${item.id}, this.value)" min="0">
                        <button class="btn btn-small" onclick="increaseQuantity(${item.id})">+</button>
                    </div>
                </td>
                <td>$${subtotal.toFixed(2)}</td>
                <td><button class="btn btn-remove" onclick="removeFromCart(${item.id})">Remove</button></td>
            </tr>
        `;
    });

    html += '</tbody></table>';
    cartContainer.innerHTML = html;

    cartSummary.innerHTML = `
        <div class="cart-totals">
            <p><strong>Total Items:</strong> ${cart.getCartCount()}</p>
            <p><strong>Total Price:</strong> $${total.toFixed(2)}</p>
            <button class="btn btn-checkout">Proceed to Checkout</button>
            <button class="btn btn-continue" onclick="window.location.href='catalog.html'">Continue Shopping</button>
            <button class="btn btn-clear" onclick="clearCart()">Clear Cart</button>
        </div>
    `;
}

// Clear entire cart
async function clearCart() {
    if (confirm('Are you sure you want to clear your cart?')) {
        await cart.clear();
        await renderCart();
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', async function() {
    console.log('DOM loaded, products available:', typeof products, products.length);
    await cart.loadCart();
    renderProducts();
    renderCart();
});