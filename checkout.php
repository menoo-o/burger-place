<?php session_start(); ?>
     <!DOCTYPE html>
     <html lang="en">
     <head>
         <meta charset="UTF-8">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <title>Checkout - Burger Bonanza</title>
         <link rel="stylesheet" href="checkout.css">
     </head>
     <body>
         

         <div class="checkout-page">
             <h2>Checkout</h2>
             <div class="checkout-cart">
                 <h3>Your Cart</h3>
                 <div class="checkout-cart-items" id="checkoutCartItems"></div>
                 <div class="checkout-cart-total">
                     Total: Rs.<span id="checkoutCartTotal">0.00</span>
                 </div>
             </div>
             <div class="checkout-form">
                 <form id="customerForm">
                     <label for="customerName">Name:</label>
                     <input type="text" id="customerName" required>
                     <label for="customerAddress">Address:</label>
                     <input type="text" id="customerAddress" required>
                     <p>Payment: Cash on Delivery</p>
                     <button type="submit">Place Order</button>
                 </form>
             </div>
         </div>

         <footer>
             <p>© 2025 Burger Bonanza</p>
         </footer>

         <script>
             document.addEventListener('DOMContentLoaded', () => {
                 const checkoutCartItems = document.getElementById('checkoutCartItems');
                 const checkoutCartTotal = document.getElementById('checkoutCartTotal');
                 const customerForm = document.getElementById('customerForm');

                 // Load cart
                 function loadCart() {
                     fetch('get_cart.php')
                         .then(response => response.json())
                         .then(cart => {
                             if (cart.length === 0) {
                                 checkoutCartItems.innerHTML = '<p>Your cart is empty.</p>';
                                 return;
                             }
                             let total = 0;
                             checkoutCartItems.innerHTML = '';
                             cart.forEach(item => {
                                 const cartItem = document.createElement('div');
                                 cartItem.className = 'checkout-cart-item';
                                 cartItem.innerHTML = `
                                     <img src="${item.image}" alt="${item.name}">
                                     <div>
                                         <h4>${item.name}</h4>
                                         <p>Rs.${item.price} x ${item.quantity}</p>
                                     </div>
                                 `;
                                 checkoutCartItems.appendChild(cartItem);
                                 total += item.price * item.quantity;
                             });
                             checkoutCartTotal.textContent = total.toFixed(2);
                         })
                         .catch(error => {
                             console.error('Error loading cart:', error);
                             checkoutCartItems.innerHTML = '<p>Error loading cart.</p>';
                         });
                 }

                 // Handle checkout
                 customerForm.addEventListener('submit', (e) => {
                     e.preventDefault();
                     const customer = {
                         name: document.getElementById('customerName').value,
                         address: document.getElementById('customerAddress').value
                     };

                     // Fetch cart for submission
                     fetch('get_cart.php')
                         .then(response => response.json())
                         .then(cart => {
                             if (cart.length === 0) {
                                 alert('Your cart is empty!');
                                 return;
                             }
                             fetch('process_order.php', {
                                 method: 'POST',
                                 headers: { 'Content-Type': 'application/json' },
                                 body: JSON.stringify({ cart, customer })
                             })
                             .then(response => response.json())
                             .then(data => {
                                 if (data.success) {
                                     // Clear cart
                                     fetch('clear_cart.php', {
                                         method: 'POST',
                                         headers: { 'Content-Type': 'application/json' }
                                     })
                                     .then(() => {
                                         alert('Order placed successfully!');
                                         window.location.href = 'index.php';
                                     });
                                 } else {
                                     alert('Error: ' + data.message);
                                 }
                             })
                             .catch(error => {
                                 console.error('Error:', error);
                                 alert('Error placing order');
                             });
                         });
                 });

                 // Initialize
                 loadCart();
             });
         </script>
     </body>
     </html>