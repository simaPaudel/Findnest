<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Browse Listings - FindNest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --fn-red: #FF385C;
            --fn-red-hover: #E11D48;
            --fn-charcoal: #1F2937;
            --fn-gray-border: #E5E7EB;
            --fn-gray-dark: #6B7280;
            --fn-gray-soft: #F8FAFC;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #ffffff;
            color: var(--fn-charcoal);
            line-height: 1.5;
        }

        .search-shell {
            background: #fff;
            border: 1px solid var(--fn-gray-border);
            border-radius: 999px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        }

        .listing-card {
            display: block;
            color: inherit;
            text-decoration: none;
        }

        .listing-card:hover .listing-image {
            transform: scale(1.04);
        }

        .listing-image-wrap {
            position: relative;
            overflow: hidden;
            border-radius: 22px;
            background: #f1f5f9;
        }

        .listing-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.35s ease;
        }

        .listing-chip {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 0.45rem 0.85rem;
            font-size: 0.72rem;
            font-weight: 700;
            line-height: 1;
        }

        .listing-details {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .listing-status-pill {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 0.3rem 0.65rem;
            font-size: 0.68rem;
            font-weight: 700;
            line-height: 1;
            white-space: nowrap;
        }

        .listing-status-pill.available {
            background: #ecfdf5;
            color: #047857;
        }

        .listing-status-pill.unavailable {
            background: #fff7ed;
            color: #c2410c;
        }

        .listing-price-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            margin-top: 0.15rem;
        }

        .listing-price {
            display: flex;
            align-items: baseline;
            gap: 0.2rem;
            min-width: 0;
        }

        .listing-price-value {
            font-size: 0.95rem;
            font-weight: 700;
        }

        .listing-price-value.is-red {
            color: #e11d48;
        }

        .listing-price-value.is-muted {
            color: #64748b;
        }

        .listing-price-suffix {
            color: #94a3b8;
            font-size: 0.72rem;
            font-weight: 600;
        }

        .listing-view-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: #ff385c;
            color: #ffffff;
            padding: 0.42rem 0.85rem;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            text-decoration: none;
            transition: background 0.2s ease, transform 0.2s ease;
            white-space: nowrap;
        }

        .listing-view-btn:hover {
            background: #e11d48;
            transform: translateY(-1px);
        }

        .save-btn {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 40px;
            height: 40px;
            border: none;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(15, 23, 42, 0.32);
            backdrop-filter: blur(8px);
            color: white;
            cursor: pointer;
            z-index: 10;
        }

        .save-btn svg {
            width: 19px;
            height: 19px;
            stroke-width: 2;
            transition: transform 0.2s ease;
        }

        .save-btn:hover svg {
            transform: scale(1.08);
        }

        .save-btn.saved {
            background: var(--fn-red);
        }

        .line-clamp-1,
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-1 {
            -webkit-line-clamp: 1;
        }

        .line-clamp-2 {
            -webkit-line-clamp: 2;
        }
    </style>
</head>

<body>
    <?php echo $__env->make('components.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main>
        <section class="border-b border-slate-200 bg-white px-4 sm:px-6 lg:px-8 py-6">
            <div class="w-full">
                <form action="<?php echo e(route('listings.index')); ?>" method="GET" class="max-w-5xl mx-auto">
                    <div class="search-shell p-2 flex flex-col gap-2 md:flex-row md:items-center md:gap-0">
                        <div class="flex-1 px-4 py-3 md:border-r md:border-slate-200">
                            <label class="block text-xs font-semibold text-slate-500 mb-1">Where</label>
                            <input type="text" name="q" placeholder="Search destinations" value="<?php echo e(request('q')); ?>" class="w-full bg-transparent outline-none text-sm text-slate-800">
                        </div>

                        <div class="flex-1 px-4 py-3 md:border-r md:border-slate-200">
                            <label class="block text-xs font-semibold text-slate-500 mb-1">Max Price</label>
                            <input type="number" name="max_price" placeholder="Set budget" value="<?php echo e(request('max_price')); ?>" class="w-full bg-transparent outline-none text-sm text-slate-800">
                        </div>

                        <div class="flex-1 px-4 py-3 md:border-r md:border-slate-200">
                            <label class="block text-xs font-semibold text-slate-500 mb-1">Property Type</label>
                            <select name="property_type" class="w-full bg-transparent outline-none text-sm text-slate-800">
                                <option value="">All types</option>
                                <option value="house" <?php echo e(request('property_type') == 'house' ? 'selected' : ''); ?>>House</option>
                                <option value="flat" <?php echo e(request('property_type') == 'flat' ? 'selected' : ''); ?>>Flat</option>
                                <option value="apartment" <?php echo e(request('property_type') == 'apartment' ? 'selected' : ''); ?>>Apartment</option>
                                <option value="room" <?php echo e(request('property_type') == 'room' ? 'selected' : ''); ?>>Room</option>
                            </select>
                        </div>

                        <div class="px-4 py-3 md:border-r md:border-slate-200">
                            <label class="block text-xs font-semibold text-slate-500 mb-1">Sort</label>
                            <select name="sort" class="w-full bg-transparent outline-none text-sm text-slate-800" onchange="this.form.submit()">
                                <option value="">Latest</option>
                                <option value="newest" <?php echo e(request('sort') == 'newest' ? 'selected' : ''); ?>>Newest</option>
                                <option value="price_low" <?php echo e(request('sort') == 'price_low' ? 'selected' : ''); ?>>Price Low</option>
                                <option value="price_high" <?php echo e(request('sort') == 'price_high' ? 'selected' : ''); ?>>Price High</option>
                            </select>
                        </div>

                        <button type="submit" class="h-12 w-12 shrink-0 rounded-full bg-rose-500 text-white flex items-center justify-center hover:bg-rose-600 transition md:mr-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <section class="px-4 sm:px-6 lg:px-8 py-10">
            <div class="mx-auto max-w-[1650px]">
                <div class="mb-8 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-950">
                            <?php if(request('q')): ?>
                                Stays in <?php echo e(request('q')); ?>

                            <?php else: ?>
                                Explore stays
                            <?php endif; ?>
                        </h1>
                        <p class="mt-2 text-sm text-slate-500">
                            <span class="font-semibold text-slate-900"><?php echo e($properties->total()); ?></span>
                            <?php echo e($properties->total() === 1 ? 'property' : 'properties'); ?> available
                        </p>
                    </div>

                    <?php if(request()->hasAny(['q', 'max_price', 'property_type', 'sort'])): ?>
                        <a href="<?php echo e(route('listings.index')); ?>" class="inline-flex items-center gap-2 text-sm font-semibold text-rose-500 hover:text-rose-600">
                            Clear filters
                        </a>
                    <?php endif; ?>
                </div>

                <?php if($properties->count() > 0): ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-x-8 gap-y-12 mb-12 items-start">
                        <?php $__currentLoopData = $properties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $primaryImage = $property->images->firstWhere('is_primary', true) ?? $property->images->sortBy('order')->first();
                                $imageUrl = $primaryImage ? $primaryImage->getUrl() : asset('images/property-placeholder.jpg');
                                $minRoomPrice = $property->min_room_price !== null ? (float) $property->min_room_price : ($property->rooms->min('price') !== null ? (float) $property->rooms->min('price') : null);
                                $maxRoomPrice = $property->max_room_price !== null ? (float) $property->max_room_price : ($property->rooms->max('price') !== null ? (float) $property->rooms->max('price') : null);
                                $availableRooms = (int) ($property->available_rooms_count ?? 0);

                                if ($property->rental_mode === 'per_room') {
                                    if ($minRoomPrice === null || $maxRoomPrice === null) {
                                        $priceAmount = 'Price on request';
                                        $priceSuffix = null;
                                    } elseif ($minRoomPrice === $maxRoomPrice) {
                                        $priceAmount = 'Rs ' . number_format($minRoomPrice);
                                        $priceSuffix = '/month';
                                    } else {
                                        $priceAmount = 'Rs ' . number_format($minRoomPrice) . ' - Rs ' . number_format($maxRoomPrice);
                                        $priceSuffix = '/month';
                                    }

                                    $subtitle = $property->property_availability_label
                                        ?? ($availableRooms === 1
                                            ? '1 room available'
                                            : ($availableRooms > 1 ? $availableRooms . ' rooms available' : 'No rooms available right now'));
                                } else {
                                    $priceAmount = 'Rs ' . number_format((float) $property->rent_price);
                                    $priceSuffix = '/month';
                                    $subtitle = $property->property_availability_label ?? 'Available for booking';
                                }

                                $availabilityLabel = $property->is_property_bookable ? 'Available' : 'Unavailable';
                                $availabilityClass = $property->is_property_bookable ? 'available' : 'unavailable';
                                $priceToneClass = $priceAmount === 'Price on request' ? 'is-muted' : 'is-red';
                            ?>

                            <a href="<?php echo e(route('listings.show', $property)); ?>" class="listing-card">
                                <div class="listing-image-wrap aspect-[4/3] mb-3">
                                    <img src="<?php echo e($imageUrl); ?>" alt="<?php echo e($property->title); ?>" class="listing-image">

                                    <div class="absolute inset-x-0 top-0 flex items-start justify-between gap-3 p-3">
                                        <span class="listing-chip bg-white/92 text-slate-800 shadow-sm">
                                            <?php echo e($property->rental_mode === 'per_room' ? 'Room choice' : 'Whole place'); ?>

                                        </span>

                                        <?php if(auth()->guard()->check()): ?>
                                            <button class="save-btn save-property-btn"
                                                data-property-id="<?php echo e($property->id); ?>"
                                                title="Save this listing"
                                                onclick="event.preventDefault(); event.stopPropagation();">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                                </svg>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="listing-details">
                                    <div class="flex items-start justify-between gap-3">
                                        <h2 class="text-[15px] font-semibold text-slate-950 leading-6 line-clamp-1"><?php echo e($property->title); ?></h2>
                                        <span class="shrink-0 text-sm font-semibold text-slate-900"><?php echo e($property->city ?: 'Nepal'); ?></span>
                                    </div>

                                    <p class="text-sm text-slate-500 line-clamp-1"><?php echo e($property->address ?: ($property->location ?: 'Location not specified')); ?></p>
                                    <div class="flex items-center gap-2">
                                        <span class="listing-status-pill <?php echo e($availabilityClass); ?>"><?php echo e($availabilityLabel); ?></span>
                                        <p class="text-sm text-slate-500 line-clamp-1"><?php echo e($subtitle); ?></p>
                                    </div>
                                    <p class="text-sm text-slate-500 line-clamp-1"><?php echo e($property->getPropertyTypeLabel()); ?></p>
                                    <div class="listing-price-row">
                                        <p class="listing-price">
                                            <span class="listing-price-value <?php echo e($priceToneClass); ?>"><?php echo e($priceAmount); ?></span>
                                            <?php if($priceSuffix): ?>
                                                <span class="listing-price-suffix"><?php echo e($priceSuffix); ?></span>
                                            <?php endif; ?>
                                        </p>
                                        <span class="listing-view-btn">View</span>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="flex justify-center">
                        <?php echo e($properties->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="rounded-3xl border border-slate-200 bg-white px-6 py-14 text-center shadow-sm">
                        <svg class="w-20 h-20 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h2 class="text-2xl font-bold text-slate-900 mb-3">No Properties Found</h2>
                        <p class="text-slate-500 mb-6">Try adjusting your search criteria or clearing filters.</p>
                        <a href="<?php echo e(route('listings.index')); ?>" class="inline-flex items-center justify-center rounded-xl bg-rose-500 px-6 py-3 font-semibold text-white hover:bg-rose-600 transition">View All Properties</a>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php echo $__env->make('components.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const saveButtons = document.querySelectorAll('.save-property-btn');

            saveButtons.forEach(button => {
                const propertyId = button.dataset.propertyId;

                checkSaveStatus(propertyId, button);

                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleSave(propertyId, button);
                });
            });

            function checkSaveStatus(propertyId, button) {
                fetch(`/user/saved-listings/check/${propertyId}`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.is_saved) {
                            button.classList.add('saved');
                        }
                    })
                    .catch(error => console.error('Error checking save status:', error));
            }

            function toggleSave(propertyId, button) {
                const isSaved = button.classList.contains('saved');
                const url = isSaved ? `/user/saved-listings/unsave/${propertyId}` : `/user/saved-listings/save/${propertyId}`;
                const method = isSaved ? 'DELETE' : 'POST';

                fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (data.is_saved) {
                                button.classList.add('saved');
                                showNotification('Listing saved', 'success');
                            } else {
                                button.classList.remove('saved');
                                showNotification('Listing removed', 'info');
                            }
                        } else {
                            showNotification(data.message || 'Error', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Error saving listing', 'error');
                    });
            }

            function showNotification(message, type) {
                const notification = document.createElement('div');
                notification.textContent = message;
                notification.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    padding: 12px 20px;
                    border-radius: 8px;
                    font-weight: 500;
                    z-index: 9999;
                    animation: slideIn 0.3s ease;
                    ${type === 'success' ? 'background: #10b981; color: white;' : ''}
                    ${type === 'error' ? 'background: #ef4444; color: white;' : ''}
                    ${type === 'info' ? 'background: #3b82f6; color: white;' : ''}
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                `;
                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.style.animation = 'slideOut 0.3s ease';
                    setTimeout(() => notification.remove(), 300);
                }, 2500);
            }
        });

        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(400px);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\FindNest\resources\views/listings/index.blade.php ENDPATH**/ ?>