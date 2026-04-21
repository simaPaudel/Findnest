<?php $__env->startSection('title', 'Add New Property'); ?>
<?php $__env->startSection('page-title', 'Add New Property'); ?>

<?php $__env->startSection('content'); ?>
<div class="content-card form-surface">
    <?php if($errors->any()): ?>
    <div class="form-error-panel">
        <h3>Validation Errors</h3>
        <ul>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('owner.listings.store')); ?>" enctype="multipart/form-data" id="property-form" onsubmit="return validateFormBeforeSubmit(event)">
        <?php echo csrf_field(); ?>

        <div class="step-flow">
            <div class="step-flow-header">
                <div>
                    <h2 class="step-flow-title">Add Property</h2>
                    <p class="step-flow-copy">Add the details needed to publish your listing.</p>
                </div>
                <div class="step-counter">Step <span id="current-step-number">1</span> of 5</div>
            </div>
            <div class="step-progress-track"><div class="step-progress-fill" id="step-progress-fill"></div></div>
            <div class="step-list">
                <div class="step-item is-active" data-step-indicator="1"><span class="step-item-index">1</span><span class="step-item-title">Basic</span><span class="step-item-copy">Title, type, mode</span></div>
                <div class="step-item" data-step-indicator="2"><span class="step-item-index">2</span><span class="step-item-title">Location</span><span class="step-item-copy">Address</span></div>
                <div class="step-item" data-step-indicator="3"><span class="step-item-index">3</span><span class="step-item-title">Details</span><span class="step-item-copy">Rooms and rules</span></div>
                <div class="step-item" data-step-indicator="4"><span class="step-item-index">4</span><span class="step-item-title">Amenities</span><span class="step-item-copy">Features</span></div>
                <div class="step-item" data-step-indicator="5"><span class="step-item-index">5</span><span class="step-item-title">Images</span><span class="step-item-copy">Photos</span></div>
            </div>
        </div>

        <div class="form-step is-active" data-step="1">
            <div class="step-card">
                <div class="step-card-header">
                    <h3 class="step-card-title">Basic Info</h3>
                    <p class="step-card-copy">Start with the essentials.</p>
                </div>
                <div class="step-card-body">
                    <div class="form-group">
                        <label for="title" class="form-label">Title *</label>
                        <input type="text" name="title" id="title" class="form-input <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('title')); ?>" placeholder="e.g., Spacious 2BHK near Lakeside" required>
                        <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="form-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" rows="5" class="form-input <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Short overview of the property."><?php echo e(old('description')); ?></textarea>
                        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="form-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="property_type" class="form-label">Type *</label>
                            <select name="property_type" id="property_type" class="form-input <?php $__errorArgs = ['property_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="">Select type</option>
                                <option value="house" <?php echo e(old('property_type') === 'house' ? 'selected' : ''); ?>>House</option>
                                <option value="flat" <?php echo e(old('property_type') === 'flat' ? 'selected' : ''); ?>>Flat/Apartment</option>
                                <option value="apartment" <?php echo e(old('property_type') === 'apartment' ? 'selected' : ''); ?>>Multi-room Apartment</option>
                                <option value="room" <?php echo e(old('property_type') === 'room' ? 'selected' : ''); ?>>Single Room</option>
                            </select>
                            <?php $__errorArgs = ['property_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="form-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="form-group">
                            <label for="rental_mode" class="form-label">Mode *</label>
                            <select name="rental_mode" id="rental_mode" class="form-input <?php $__errorArgs = ['rental_mode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required onchange="toggleRentalSections()">
                                <option value="">Select mode</option>
                                <option value="full_property" <?php echo e(old('rental_mode') === 'full_property' ? 'selected' : ''); ?>>Full Property</option>
                                <option value="per_room" <?php echo e(old('rental_mode') === 'per_room' ? 'selected' : ''); ?>>Per Room</option>
                            </select>
                            <?php $__errorArgs = ['rental_mode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="form-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="form-group" id="rent-price-group">
                        <label for="rent_price" class="form-label">Rent / Month (NPR) *</label>
                        <input type="number" name="rent_price" id="rent_price" class="form-input <?php $__errorArgs = ['rent_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('rent_price')); ?>" min="0" step="0.01" placeholder="e.g., 25000">
                        <?php $__errorArgs = ['rent_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="form-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="step-actions">
                        <div class="step-actions-group"><a href="<?php echo e(route('owner.listings.index')); ?>" class="btn-secondary">Cancel</a></div>
                        <div class="step-actions-group"><button type="button" class="btn-primary" onclick="goToStep(2)">Next</button></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-step" data-step="2">
            <div class="step-card">
                <div class="step-card-header">
                    <h3 class="step-card-title">Location</h3>
                    <p class="step-card-copy">Add the address details.</p>
                </div>
                <div class="step-card-body">
                    <div class="form-group">
                        <label for="address" class="form-label">Address *</label>
                        <input type="text" name="address" id="address" class="form-input <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('address')); ?>" placeholder="Street, ward, or area" required>
                        <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="form-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="city" class="form-label">City *</label>
                            <input type="text" name="city" id="city" class="form-input <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('city')); ?>" placeholder="e.g., Pokhara" required>
                            <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="form-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="form-group">
                            <label for="location" class="form-label">Area</label>
                            <input type="text" name="location" id="location" class="form-input <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('location')); ?>" placeholder="e.g., Lakeside">
                            <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="form-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="landmark" class="form-label">Landmark</label>
                        <input type="text" name="landmark" id="landmark" class="form-input <?php $__errorArgs = ['landmark'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('landmark')); ?>" placeholder="Nearby landmark">
                        <?php $__errorArgs = ['landmark'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="form-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="latitude" class="form-label">Latitude</label>
                            <input type="number" name="latitude" id="latitude" class="form-input <?php $__errorArgs = ['latitude'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('latitude')); ?>" step="any" placeholder="Optional">
                            <?php $__errorArgs = ['latitude'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="form-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="form-group">
                            <label for="longitude" class="form-label">Longitude</label>
                            <input type="number" name="longitude" id="longitude" class="form-input <?php $__errorArgs = ['longitude'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('longitude')); ?>" step="any" placeholder="Optional">
                            <?php $__errorArgs = ['longitude'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="form-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <?php echo $__env->make('components.leaflet-property-map', [
                        'mapId' => 'owner-create-property-map',
                        'mode' => 'picker',
                        'latitudeInputId' => 'latitude',
                        'longitudeInputId' => 'longitude',
                        'initialLatitude' => old('latitude'),
                        'initialLongitude' => old('longitude'),
                        'defaultLatitude' => 27.7172,
                        'defaultLongitude' => 85.3240,
                        'defaultZoom' => 12,
                        'height' => '320px',
                        'title' => 'Map Location',
                        'helpText' => 'Tap the map to set the pin.',
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <div class="step-actions">
                        <div class="step-actions-group"><button type="button" class="btn-secondary" onclick="goToStep(1)">Back</button></div>
                        <div class="step-actions-group"><button type="button" class="btn-primary" onclick="goToStep(3)">Next</button></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-step" data-step="3">
            <div class="step-card">
                <div class="step-card-header">
                    <h3 class="step-card-title">Details</h3>
                    <p class="step-card-copy">Set rooms, rules, and preferences.</p>
                </div>
                <div class="step-card-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="total_rooms" class="form-label">Total Rooms</label>
                            <input type="number" name="total_rooms" id="total_rooms" class="form-input <?php $__errorArgs = ['total_rooms'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('total_rooms')); ?>" min="1" placeholder="e.g., 3">
                            <?php $__errorArgs = ['total_rooms'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="form-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="form-group">
                            <label for="gender_preference" class="form-label">Gender Preference</label>
                            <select name="gender_preference" id="gender_preference" class="form-input <?php $__errorArgs = ['gender_preference'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="any" <?php echo e(old('gender_preference', 'any') === 'any' ? 'selected' : ''); ?>>Any</option>
                                <option value="male" <?php echo e(old('gender_preference') === 'male' ? 'selected' : ''); ?>>Male Only</option>
                                <option value="female" <?php echo e(old('gender_preference') === 'female' ? 'selected' : ''); ?>>Female Only</option>
                            </select>
                            <?php $__errorArgs = ['gender_preference'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="form-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-checkbox">
                            <input type="checkbox" name="furnished" value="1" <?php echo e(old('furnished') ? 'checked' : ''); ?>>
                            <span>Furnished</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="rules" class="form-label">House Rules</label>
                        <textarea name="rules" id="rules" rows="4" class="form-input <?php $__errorArgs = ['rules'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Important rules or move-in notes."><?php echo e(old('rules')); ?></textarea>
                        <?php $__errorArgs = ['rules'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="form-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-section" id="rooms-section" style="display: none; margin-top: 10px;">
                        <div class="form-panel">
                            <div class="form-panel-header">
                                <div>
                                    <h3 class="form-panel-title">Rooms</h3>
                                    <p class="form-panel-copy">Add rooms for per-room listings.</p>
                                </div>
                                <button type="button" class="btn-primary" onclick="addRoom(); return false;">Add Room</button>
                            </div>
                            <div id="rooms-error-message" class="form-error-panel" style="display: none; margin: 0 0 14px 0;">
                                <h3>Add at least one room for per-room mode.</h3>
                            </div>
                            <div id="rooms-container" class="room-stack"></div>
                            <div id="rooms-empty-state" class="form-panel-empty">No rooms added yet.</div>
                        </div>
                    </div>

                    <div class="step-actions">
                        <div class="step-actions-group"><button type="button" class="btn-secondary" onclick="goToStep(2)">Back</button></div>
                        <div class="step-actions-group"><button type="button" class="btn-primary" onclick="goToStep(4)">Next</button></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-step" data-step="4">
            <div class="step-card">
                <div class="step-card-header">
                    <h3 class="step-card-title">Amenities</h3>
                    <p class="step-card-copy">Choose the available features.</p>
                </div>
                <div class="step-card-body">
                    <div class="form-group">
                        <label class="form-label">Features</label>
                        <?php $__errorArgs = ['amenity_ids'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="form-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="amenity-pill-grid">
                            <?php $__currentLoopData = $amenities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $amenity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="amenity-pill <?php echo e(in_array($amenity->id, old('amenity_ids', [])) ? 'is-selected' : ''); ?>">
                                <input type="checkbox" name="amenity_ids[]" value="<?php echo e($amenity->id); ?>" <?php echo e(in_array($amenity->id, old('amenity_ids', [])) ? 'checked' : ''); ?> onchange="syncAmenityPill(this)">
                                <span><?php echo e($amenity->name); ?></span>
                            </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <div class="step-actions">
                        <div class="step-actions-group"><button type="button" class="btn-secondary" onclick="goToStep(3)">Back</button></div>
                        <div class="step-actions-group"><button type="button" class="btn-primary" onclick="goToStep(5)">Next</button></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-step" data-step="5">
            <div class="step-card">
                <div class="step-card-header">
                    <h3 class="step-card-title">Images</h3>
                    <p class="step-card-copy">Upload clear listing photos.</p>
                </div>
                <div class="step-card-body">
                    <div class="form-group" id="images-section">
                        <label for="images" class="form-label">Photos <span id="images-required"></span></label>
                        <div class="dropzone" id="property-dropzone">
                            <div class="dropzone-copy">
                                <span class="dropzone-title">Drop images here</span>
                                <span class="dropzone-text">JPG, PNG, WEBP. Up to 20 files, 5MB each.</span>
                            </div>
                            <div class="upload-frame">
                                <input type="file" name="images[]" id="images" class="form-input <?php $__errorArgs = ['images.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" multiple accept="image/jpeg,image/png,image/webp">
                            </div>
                        </div>
                        <div id="file-preview" class="mt-4">
                            <p class="form-panel-copy">Selected: <span id="file-count">0</span></p>
                            <div id="preview-container" class="preview-grid"></div>
                        </div>
                        <?php $__errorArgs = ['images'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="form-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <?php $__errorArgs = ['images.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="form-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="step-actions">
                        <div class="step-actions-group"><button type="button" class="btn-secondary" onclick="goToStep(4)">Back</button></div>
                        <div class="step-actions-group">
                            <a href="<?php echo e(route('owner.listings.index')); ?>" class="btn-secondary">Cancel</a>
                            <button type="submit" class="btn-primary">Create Listing</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
let roomCount = 0, currentStep = 1, totalSteps = 5;

function toggleRentalSections() {
    const rentalMode = document.getElementById('rental_mode').value;
    const roomsSection = document.getElementById('rooms-section');
    const imagesSection = document.getElementById('images-section');
    const rentPriceGroup = document.getElementById('rent-price-group');
    const rentPriceInput = document.getElementById('rent_price');
    const imagesInput = document.getElementById('images');
    const imagesRequired = document.getElementById('images-required');
    if (!roomsSection || !imagesSection) return;
    if (rentalMode === 'full_property') {
        rentPriceInput.setAttribute('required', 'required');
        rentPriceGroup.style.display = 'block';
        roomsSection.style.display = 'none';
        imagesInput.setAttribute('required', 'required');
        imagesRequired.textContent = '*';
        imagesSection.style.display = 'block';
    } else if (rentalMode === 'per_room') {
        rentPriceInput.removeAttribute('required');
        rentPriceGroup.style.display = 'none';
        roomsSection.style.display = 'block';
        imagesInput.removeAttribute('required');
        imagesRequired.textContent = '';
        imagesSection.style.display = 'block';
    } else {
        roomsSection.style.display = 'none';
    }
}

function syncAmenityPill(input) {
    const label = input.closest('.amenity-pill');
    if (label) label.classList.toggle('is-selected', input.checked);
}

function updateStepIndicator() {
    document.getElementById('current-step-number').textContent = currentStep;
    document.getElementById('step-progress-fill').style.width = `${(currentStep / totalSteps) * 100}%`;
    document.querySelectorAll('[data-step-indicator]').forEach((item) => {
        const step = Number(item.dataset.stepIndicator);
        item.classList.toggle('is-active', step === currentStep);
        item.classList.toggle('is-complete', step < currentStep);
    });
}

function showStep(step) {
    currentStep = step;
    document.querySelectorAll('.form-step').forEach((section) => {
        section.classList.toggle('is-active', Number(section.dataset.step) === step);
    });
    updateStepIndicator();
    if (step === 2) {
        window.dispatchEvent(new Event('fn-property-map-invalidate'));
    }
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function validateCurrentStep(step) {
    const section = document.querySelector(`.form-step[data-step="${step}"]`);
    if (!section) return true;
    const fields = section.querySelectorAll('input, select, textarea');
    for (const field of fields) {
        if (field.type === 'hidden' || field.disabled || field.offsetParent === null) continue;
        if (!field.reportValidity()) return false;
    }
    if (step === 3 && document.getElementById('rental_mode').value === 'per_room') {
        const roomItems = document.querySelectorAll('#rooms-container [id^="room-"]');
        if (roomItems.length === 0) {
            document.getElementById('rooms-error-message').style.display = 'block';
            return false;
        }
    }
    return true;
}

function goToStep(step) {
    if (step > currentStep && !validateCurrentStep(currentStep)) return;
    showStep(step);
}

function addRoom() {
    const container = document.getElementById('rooms-container');
    const emptyState = document.getElementById('rooms-empty-state');
    if (!container) return;
    const roomIndex = roomCount++;
    document.getElementById('rooms-error-message').style.display = 'none';
    if (emptyState) emptyState.style.display = 'none';
    container.insertAdjacentHTML('beforeend', `
        <div class="room-card" id="room-${roomIndex}">
            <div class="room-card-header">
                <div><h4 class="room-card-title">Room ${roomIndex + 1}</h4><p class="room-card-status">New room</p></div>
                <button type="button" class="room-card-action danger" onclick="removeRoom(${roomIndex})">Remove</button>
            </div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Name *</label><input type="text" name="rooms[${roomIndex}][room_name]" class="form-input" placeholder="e.g., Master Bedroom" required></div>
                <div class="form-group"><label class="form-label">Number</label><input type="text" name="rooms[${roomIndex}][room_number]" class="form-input" placeholder="e.g., 101"></div>
                <div class="form-group"><label class="form-label">Capacity *</label><input type="number" name="rooms[${roomIndex}][capacity]" class="form-input" min="1" value="1" required></div>
                <div class="form-group"><label class="form-label">Price / Month *</label><input type="number" name="rooms[${roomIndex}][price]" class="form-input" min="0" step="0.01" placeholder="e.g., 12000" required></div>
            </div>
            <div class="form-group"><label class="form-label">Features</label><textarea name="rooms[${roomIndex}][room_features]" class="form-input" rows="2" placeholder="e.g., Attached bath, balcony, wardrobe"></textarea></div>
            <div class="form-group">
                <label class="form-label">Images</label>
                <div class="upload-stack">
                    <div class="upload-frame"><input type="file" name="rooms[${roomIndex}][images][]" class="form-input room-image-input" multiple accept="image/jpeg,image/png,image/webp" data-room-index="${roomIndex}"></div>
                    <small class="form-hint">Optional room photos. JPG, PNG, WEBP. Max 5MB each.</small>
                    <div class="room-image-preview preview-grid" id="preview-room-${roomIndex}"></div>
                </div>
            </div>
        </div>`);
    attachRoomImagePreviewListener(roomIndex);
}

function removeRoom(index) {
    const room = document.getElementById(`room-${index}`);
    if (room) room.remove();
    const count = document.querySelectorAll('#rooms-container [id^="room-"]').length;
    document.getElementById('rooms-empty-state').style.display = count ? 'none' : 'block';
    if (!count) document.getElementById('rooms-error-message').style.display = 'block';
}

function attachRoomImagePreviewListener(roomIndex) {
    const input = document.querySelector(`input[name="rooms[${roomIndex}][images][]"]`);
    const preview = document.getElementById(`preview-room-${roomIndex}`);
    if (!input || !preview) return;
    input.addEventListener('change', function() {
        preview.innerHTML = '';
        const files = this.files;
        if (files.length > 10) { alert('You can only upload up to 10 images per room'); this.value = ''; return; }
        [...files].forEach((file) => {
            if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) return;
            if (file.size > 5 * 1024 * 1024) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                const card = document.createElement('div');
                card.className = 'preview-card';
                card.innerHTML = `<img src="${e.target.result}" alt="Room preview"><div class="preview-card-meta">${((file.size / 1024) / 1024).toFixed(2)} MB</div>`;
                preview.appendChild(card);
            };
            reader.readAsDataURL(file);
        });
    });
}

function bindPropertyImagePreview() {
    const input = document.getElementById('images');
    const count = document.getElementById('file-count');
    const preview = document.getElementById('preview-container');
    const dropzone = document.getElementById('property-dropzone');
    if (!input || !count || !preview || !dropzone) return;
    const renderFiles = (files) => {
        count.textContent = files.length;
        preview.innerHTML = '';
        if (files.length > 20) { alert('You can only upload up to 20 files'); input.value = ''; count.textContent = '0'; return; }
        [...files].forEach((file) => {
            if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) return;
            if (file.size > 5 * 1024 * 1024) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                const card = document.createElement('div');
                card.className = 'preview-card';
                card.innerHTML = `<img src="${e.target.result}" alt="Preview"><div class="preview-card-meta">${((file.size / 1024) / 1024).toFixed(2)} MB</div>`;
                preview.appendChild(card);
            };
            reader.readAsDataURL(file);
        });
    };
    input.addEventListener('change', function() { renderFiles(this.files); });
    ['dragenter', 'dragover'].forEach((name) => dropzone.addEventListener(name, (e) => { e.preventDefault(); dropzone.classList.add('is-dragging'); }));
    ['dragleave', 'drop'].forEach((name) => dropzone.addEventListener(name, (e) => { e.preventDefault(); dropzone.classList.remove('is-dragging'); }));
    dropzone.addEventListener('drop', (e) => { input.files = e.dataTransfer.files; renderFiles(e.dataTransfer.files); });
}

function validateFormBeforeSubmit(event) {
    try {
        event.preventDefault();
        if (!validateCurrentStep(currentStep)) return false;
        const rentalMode = document.getElementById('rental_mode').value;
        const rooms = document.querySelectorAll('#rooms-container [id^="room-"]').length;
        const errorBox = document.getElementById('rooms-error-message');
        if (rentalMode === 'per_room' && rooms === 0) {
            errorBox.style.display = 'block';
            showStep(3);
            alert('Please add at least one room for Per Room rental mode.');
            return false;
        }
        if (rentalMode === 'full_property') {
            const imagesInput = document.getElementById('images');
            if (!imagesInput.files || imagesInput.files.length === 0) {
                showStep(5);
                alert('Please upload at least one property image for Full Property rental.');
                return false;
            }
        }
        const form = document.getElementById('property-form');
        const formData = new FormData(form);
        fetch(form.action, { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then((response) => {
                if (response.ok) {
                    if (response.redirected) window.location.href = response.url;
                    else window.location.href = '<?php echo e(route("owner.listings.index")); ?>';
                    return null;
                }
                return response.status === 422 ? response.json() : response.text();
            })
            .then((data) => {
                if (!data) return;
                if (data.errors) alert('Validation errors:\n' + Object.values(data.errors).flat().join('\n'));
                else if (typeof data === 'string') alert('Error: ' + data);
            })
            .catch((error) => alert('Submission failed: ' + error.message));
        return false;
    } catch (error) {
        alert('An error occurred during form submission. Check console for details.');
        return false;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    showStep(1);
    toggleRentalSections();
    bindPropertyImagePreview();
    document.querySelectorAll('.amenity-pill input').forEach((input) => syncAmenityPill(input));
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('owner.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\FindNest\resources\views/owner/listings/create.blade.php ENDPATH**/ ?>