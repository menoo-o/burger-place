<!DOCTYPE html>
     <html lang="en">
     <head>
         <meta charset="UTF-8">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <title>Burger Bonanza</title>
         <link rel="stylesheet" href="styles.css">
     </head>
     <body>
         <?php include 'includes/navbar.php'; ?>
         <?php include 'includes/hero.php'; ?>
         <?php include 'includes/main-content.php'; ?>

         <script>
             document.addEventListener('DOMContentLoaded', () => {
                 const cartIcon = document.getElementById('cartIcon');
                 const cartContainer = document.getElementById('cartContainer');
                 const closeCart = document.getElementById('closeCart');
                 const cartItems = document.getElementById('cartItems');
                 const cartCount = document.getElementById('cartCount');
                 const cartTotal = document.getElementById('cartTotal');
                 const checkoutBtn = document.getElementById('checkoutBtn');
                 const menuItems = document.getElementById('menuItems');
                 const viewOrdersBtn = document.getElementById('viewOrdersBtn');
                 const orderHistoryTable = document.getElementById('orderHistoryTable');

                 let cart = [];

                 // Load cart from database
                 function loadCart() {
                     fetch('get_cart.php')
                         .then(response => response.json())
                         .then(data => {
                             cart = data;
                             updateCart();
                         })
                         .catch(error => {
                             console.error('Error loading cart:', error);
                         });
                 }

                 // Save cart to database
                 function saveCart() {
                     fetch('save_cart.php', {
                         method: 'POST',
                         headers: { 'Content-Type': 'application/json' },
                         body: JSON.stringify({ cart })
                     })
                     .then(response => response.json())
                     .then(data => {
                         if (!data.success) {
                             console.error('Error saving cart:', data.message);
                         }
                     })
                     .catch(error => {
                         console.error('Error saving cart:', error);
                     });
                 }

                 // Toggle cart visibility
                 cartIcon.addEventListener('click', () => {
                     cartContainer.style.display = 'block';
                     cartContainer.classList.remove('cart-closing');
                     if (cartContainer.closeTimeout) {
                         clearTimeout(cartContainer.closeTimeout);
                     }
                 });

                 closeCart.addEventListener('click', () => {
                     cartContainer.classList.add('cart-closing');
                     setTimeout(() => {
                         cartContainer.style.display = 'none';
                         cartContainer.classList.remove('cart-closing');
                     }, 500);
                     if (cartContainer.closeTimeout) {
                         clearTimeout(cartContainer.closeTimeout);
                     }
                 });

                 // Load menu items
                 function loadMenuItems() {
                     fetch('get_menu_items.php')
                         .then(response => response.json())
                         .then(data => {
                             displayMenuItems(data);
                         })
                         .catch(error => {
                             console.error('Error:', error);
                             menuItems.innerHTML = '<p>Error loading menu.</p>';
                         });
                 }

                 // Display menu items
                 function displayMenuItems(items) {
                     menuItems.innerHTML = '';
                     items.forEach(item => {
                         const menuItem = document.createElement('div');
                         menuItem.className = 'menu-item';
                         menuItem.innerHTML = `
                             <img src="${item.image}" alt="${item.name}">
                             <div>
                                 <h3>${item.name}</h3>
                                 <p>${item.description}</p>
                                 <p>Rs.${item.price}</p>
                                 <button class="add-to-cart" data-id="${item.id}">Add to Cart</button>
                             </div>
                         `;
                         menuItems.appendChild(menuItem);
                     });

                     document.querySelectorAll('.add-to-cart').forEach(button => {
                         button.addEventListener('click', addToCart);
                     });
                 }

                 // Add item to cart
                 function addToCart(e) {
                     const itemId = parseInt(e.target.dataset.id);
                     fetch(`get_menu_item.php?id=${itemId}`)
                         .then(response => response.json())
                         .then(item => {
                             if (item.error) {
                                 alert('Item not found');
                                 return;
                             }
                             const existingItem = cart.find(cartItem => cartItem.id === item.id);
                             if (existingItem) {
                                 existingItem.quantity += 1;
                             } else {
                                 cart.push({ ...item, quantity: 1 });
                             }
                             updateCart();
                             saveCart();
                             showCartTemporarily();
                         })
                         .catch(error => {
                             console.error('Error:', error);
                             alert('Error adding item');
                         });
                 }

                 // Show cart temporarily
                 function showCartTemporarily() {
                     cartContainer.style.display = 'block';
                     cartContainer.classList.remove('cart-closing');
                     if (cartContainer.closeTimeout) {
                         clearTimeout(cartContainer.closeTimeout);
                     }
                     cartContainer.closeTimeout = setTimeout(() => {
                         cartContainer.classList.add('cart-closing');
                         setTimeout(() => {
                             cartContainer.style.display = 'none';
                             cartContainer.classList.remove('cart-closing');
                         }, 500);
                     }, 2500);
                 }

                 // Update cart display
                 function updateCart() {
                     cartItems.innerHTML = '';
                     let total = 0;
                     cart.forEach(item => {
                         const cartItem = document.createElement('div');
                         cartItem.className = 'cart-item';
                         cartItem.innerHTML = `
                             <img src="${item.image}" alt="${item.name}">
                             <div>
                                 <h4>${item.name}</h4>
                                 <p>Rs.${item.price} x ${item.quantity}</p>
                             </div>
                             <div>
                                 <button class="quantity-btn minus" data-id="${item.id}">-</button>
                                 <span>${item.quantity}</span>
                                 <button class="quantity-btn plus" data-id="${item.id}">+</button>
                             </div>
                         `;
                         cartItems.appendChild(cartItem);
                         total += item.price * item.quantity;
                     });

                     document.querySelectorAll('.quantity-btn.minus').forEach(button => {
                         button.addEventListener('click', decreaseQuantity);
                     });

                     document.querySelectorAll('.quantity-btn.plus').forEach(button => {
                         button.addEventListener('click', increaseQuantity);
                     });

                     const itemCount = cart.reduce((sum, item) => sum + item.quantity, 0);
                     cartTotal.textContent = total.toFixed(2);
                     cartCount.textContent = itemCount;
                     cartCount.style.display = itemCount > 0 ? 'inline-block' : 'none';
                 }

                 // Decrease quantity
                 function decreaseQuantity(e) {
                     const itemId = parseInt(e.target.dataset.id);
                     const itemIndex = cart.findIndex(item => item.id === itemId);
                     if (itemIndex !== -1) {
                         if (cart[itemIndex].quantity > 1) {
                             cart[itemIndex].quantity -= 1;
                         } else {
                             cart.splice(itemIndex, 1);
                         }
                         updateCart();
                         saveCart();
                     }
                 }

                 // Increase quantity
                 function increaseQuantity(e) {
                     const itemId = parseInt(e.target.dataset.id);
                     const item = cart.find(item => item.id === itemId);
                     if (item) {
                         item.quantity += 1;
                         updateCart();
                         saveCart();
                     }
                 }

                 // Redirect to checkout page
                 checkoutBtn.addEventListener('click', () => {
                     if (cart.length === 0) {
                         alert('Your cart is empty!');
                         return;
                     }
                     window.location.href = 'checkout.php';
                 });

                 // Load order history
                 viewOrdersBtn.addEventListener('click', () => {
                     fetch('get_order_history.php')
                         .then(response => response.json())
                         .then(orders => {
                             orderHistoryTable.innerHTML = `
                                 <table>
                                     <thead>
                                         <tr>
                                             <th>Order ID</th>
                                             <th>Date</th>
                                             <th>Items</th>
                                             <th>Total</th>
                                         </tr>
                                     </thead>
                                     <tbody>
                                         ${orders.map(order => `
                                             <tr>
                                                 <td>${order.id}</td>
                                                 <td>${order.created_at}</td>
                                                 <td>${order.items.map(item => `${item.quantity} x ${item.name}`).join(', ')}</td>
                                                 <td>Rs.${order.total_amount}</td>
                                             </tr>
                                         `).join('')}
                                     </tbody>
                                 </table>
                             `;
                         })
                         .catch(error => {
                             console.error('Error:', error);
                             orderHistoryTable.innerHTML = '<p>Error loading orders.</p>';
                         });
                 });

                 // Smooth scroll for hero button
                 document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                     anchor.addEventListener('click', (e) => {
                         e.preventDefault();
                         document.querySelector(anchor.getAttribute('href')).scrollIntoView({
                             behavior: 'smooth'
                         });
                     });
                 });

                 // Initialize
                 loadCart();
                 loadMenuItems();
             });
         </script>
     </body>
     </html>