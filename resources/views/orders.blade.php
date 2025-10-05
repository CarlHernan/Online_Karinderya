<x-layout>
    <div class="min-h-screen" style="background-color: #dddbd9;">
        <!-- Header Section -->
        <div>

    <div class="max-w-6xl mx-auto p-4">
        <!-- Filter Tabs -->
        <div class="bg-white rounded-lg p-4 mb-4 shadow-sm">
            <div class="flex gap-3 flex-wrap">
                <button class="filter-btn active px-6 py-2 rounded-full text-sm font-medium transition-all" data-filter="all">All</button>
                <button class="filter-btn px-6 py-2 rounded-full text-sm font-medium transition-all" data-filter="pending">Pending Order</button>
                <button class="filter-btn px-6 py-2 rounded-full text-sm font-medium transition-all" data-filter="delivery">On Delivery</button>
                <button class="filter-btn px-6 py-2 rounded-full text-sm font-medium transition-all" data-filter="completed">Completed Orders</button>
                <button class="filter-btn px-6 py-2 rounded-full text-sm font-medium transition-all" data-filter="review">To Review</button>
            </div>
        </div>

        <!-- Orders tabs -->
        <div id="ordersContainer">
            <!-- Sinigang na Baboy -->
            <div class="order-card bg-white rounded-lg p-6 mb-4 shadow-sm" data-status="pending completed delivery review">
                <div class="flex gap-4">
                    <img src="width='150' height='150'" class="w-32 h-32 object-cover rounded-lg">
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xl font-bold">Sinigang na Baboy</h3>
                            <span class="text-xl font-bold">₱85</span>
                        </div>
                        <p class="text-gray-600 mb-2">pork belly in a sour tamarind broth with vegetables.</p>
                        <p class="text-sm text-gray-500 mb-4">1x</p>
                        <div class="flex justify-between items-center pt-4 border-t">
                            <span class="order-status text-red-600 font-medium">Pending Order</span>
                            <span class="text-lg font-bold">Order Total: ₱85</span>
                        </div>
                    </div>
                </div>
                <div class="order-actions flex justify-between items-center mt-4 pt-4 border-t">
                    <span class="text-gray-400 text-sm italic">rate now!</span>
                    <div class="flex gap-3">
                        <button class="px-6 py-2 border-2 border-gray-300 rounded-lg font-medium hover:bg-gray-50 transition-colors">Buy Again</button>
                        <button class="px-8 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors">Rate</button>
                    </div>
                </div>
            </div>

            <!-- Adobo -->
            <div class="order-card bg-white rounded-lg p-6 mb-4 shadow-sm" data-status="pending completed delivery review">
                <div class="flex gap-4">
                    <img src="width='150' height='150'" class="w-32 h-32 object-cover rounded-lg">
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xl font-bold">Adobo</h3>
                            <span class="text-xl font-bold">₱75</span>
                        </div>
                        <p class="text-gray-600 mb-2">Classic pork or chicken stew braised in soy sauce, vinegar, and spices.</p>
                        <p class="text-sm text-gray-500 mb-4">2x</p>
                        <div class="flex justify-between items-center pt-4 border-t">
                            <span class="order-status text-red-600 font-medium">On Delivery</span>
                            <span class="text-lg font-bold">Order Total: ₱150</span>
                        </div>
                    </div>
                </div>
                <div class="order-actions flex justify-between items-center mt-4 pt-4 border-t">
                    <span class="text-gray-400 text-sm italic">rate now!</span>
                    <div class="flex gap-3">
                        <button class="px-6 py-2 border-2 border-gray-300 rounded-lg font-medium hover:bg-gray-50 transition-colors">Buy Again</button>
                        <button class="px-8 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors">Rate</button>
                    </div>
                </div>
            </div>

            <!--Kare-Kare -->
            <div class="order-card bg-white rounded-lg p-6 mb-4 shadow-sm" data-status="pending completed delivery review">
                <div class="flex gap-4">
                    <img src="width='150' height='150'" class="w-32 h-32 object-cover rounded-lg">
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xl font-bold">Kare-Kare</h3>
                            <span class="text-xl font-bold">₱100</span>
                        </div>
                        <p class="text-gray-600 mb-2">Oxtail and vegetables stewed in a peanut sauce, served with bagoong.</p>
                        <p class="text-sm text-gray-500 mb-4">1x</p>
                        <div class="flex justify-between items-center pt-4 border-t">
                            <span class="order-status text-red-600 font-medium">Completed</span>
                            <span class="text-lg font-bold">Order Total: ₱100</span>
                        </div>
                    </div>
                </div>
                <div class="order-actions flex justify-between items-center mt-4 pt-4 border-t hidden">
                    <span class="text-gray-400 text-sm italic">rate now!</span>
                    <div class="flex gap-3">
                        <button class="px-6 py-2 border-2 border-gray-300 rounded-lg font-medium hover:bg-gray-50 transition-colors">Buy Again</button>
                        <button class="px-8 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors">Rate</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const filterButtons = document.querySelectorAll('.filter-btn');
        const orderCards = document.querySelectorAll('.order-card');

        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Update active button
                filterButtons.forEach(btn => {
                    btn.classList.remove('active', 'bg-green-900', 'text-white');
                    btn.classList.add('bg-gray-200', 'text-gray-700');
                });
                button.classList.add('active', 'bg-green-900', 'text-white');
                button.classList.remove('bg-gray-200', 'text-gray-700');

                // Filter orders
                const filter = button.dataset.filter;
                orderCards.forEach(card => {
                    if (filter === 'all') {
                        card.style.display = 'block';
                        updateCardStatus(card, 'pending');
                    } else {
                        const statuses = card.dataset.status.split(' ');
                        if (statuses.includes(filter)) {
                            card.style.display = 'block';
                            updateCardStatus(card, filter);
                        } else {
                            card.style.display = 'none';
                        }
                    }
                });
            });
        });

        function updateCardStatus(card, status) {
            const statusElement = card.querySelector('.order-status');
            const actionsElement = card.querySelector('.order-actions');
            const rateButton = actionsElement.querySelector('.bg-red-600');
            const rateText = actionsElement.querySelector('.text-gray-400');
            
            let statusText = '';

            switch(status) {
                case 'pending':
                    statusText = 'Pending Order';
                    actionsElement.classList.add('hidden');
                    break;
                case 'delivery':
                    statusText = 'On Delivery';
                    actionsElement.classList.add('hidden');
                    break;
                case 'completed':
                    statusText = 'Completed';
                    actionsElement.classList.remove('hidden');
                    rateButton.classList.remove('hidden');
                    rateText.classList.remove('hidden');
                    break;
                case 'review':
                    statusText = 'Completed';
                    actionsElement.classList.remove('hidden');
                    rateButton.classList.remove('hidden');
                    rateText.classList.remove('hidden');
                    break;
                default:
                    statusText = 'Pending Order';
                    actionsElement.classList.add('hidden');
            }

            statusElement.textContent = statusText;
        }

        // Set initial active state
        document.querySelector('.filter-btn.active').classList.add('bg-green-900', 'text-white');
        filterButtons.forEach(btn => {
            if (!btn.classList.contains('active')) {
                btn.classList.add('bg-gray-200', 'text-gray-700');
            }
        });

        // Initialize with "all" view
        updateCardStatus(orderCards[0], 'pending');
        updateCardStatus(orderCards[1], 'delivery');
        updateCardStatus(orderCards[2], 'completed');
        updateCardStatus(orderCards[3], 'completed');
    </script>
</x-layout>