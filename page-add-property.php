<?php
/**
 * Template Name: Add Property
 *
 * Multi-step wizard for agents to add a property listing.
 */

defined('ABSPATH') || exit;

// Redirect to login if not logged in
if (!is_user_logged_in()) {
    wp_redirect(wp_login_url(get_permalink()));
    exit;
}

// ── Edit mode: detect ?property_id= ──────────────────────────
$edit_id   = isset($_GET['property_id']) ? absint($_GET['property_id']) : 0;
$is_edit   = $edit_id > 0 && get_post_type($edit_id) === 'property' && current_user_can('edit_post', $edit_id);
$edit_data = null;

if ($is_edit) {
    $ep = get_post($edit_id);
    $raw_ids = (array) get_post_meta($edit_id, 'sb_image_ids', true);
    $images  = [];
    foreach ($raw_ids as $att_id) {
        $att_id = absint($att_id);
        if (!$att_id) continue;
        $images[] = [
            'id'    => $att_id,
            'url'   => wp_get_attachment_url($att_id) ?: '',
            'thumb' => wp_get_attachment_image_url($att_id, 'thumbnail') ?: wp_get_attachment_url($att_id) ?: '',
        ];
    }
    $edit_data = [
        'id'          => $edit_id,
        'title'       => $ep->post_title,
        'description' => $ep->post_content,
        'status'      => get_post_meta($edit_id, 'sb_status',     true),
        'build_type'  => get_post_meta($edit_id, 'sb_build_type', true),
        'featured'    => get_post_meta($edit_id, 'sb_featured',   true),
        'price'       => get_post_meta($edit_id, 'sb_price',      true),
        'size'        => get_post_meta($edit_id, 'sb_size',       true),
        'bedrooms'    => get_post_meta($edit_id, 'sb_bedrooms',   true),
        'bathrooms'   => get_post_meta($edit_id, 'sb_bathrooms',  true),
        'ref'         => get_post_meta($edit_id, 'sb_ref',        true),
        'city'        => get_post_meta($edit_id, 'sb_city',       true),
        'address'     => get_post_meta($edit_id, 'sb_address',    true),
        'lat'         => get_post_meta($edit_id, 'sb_lat',        true),
        'lng'         => get_post_meta($edit_id, 'sb_lng',        true),
        'images'      => $images,
    ];
}

get_header();
?>

<div class="add-property-page">

    <!-- AP Header -->
    <div class="ap-header">
        <div class="ap-header__center">
            <span class="ap-header__title"><?php echo $is_edit ? 'Edit Property' : 'Add New Property'; ?></span>
            <span class="ap-header__step-label">Step <span id="ap-current-step-num">1</span> of 4</span>
        </div>
        <div class="ap-header__actions">
            <a href="<?php echo esc_url(home_url('/properties')); ?>" class="ap-header__exit">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                Exit
            </a>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="ap-progress-wrap">
        <div class="ap-progress">
            <div class="ap-progress__track">
                <div class="ap-progress__fill" id="ap-progress-fill"></div>
            </div>
            <div class="ap-progress__steps">
                <div class="ap-progress__step ap-progress__step--active" data-step="1">
                    <div class="ap-progress__circle">
                        <span class="ap-progress__num">1</span>
                        <svg class="ap-progress__check" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <span class="ap-progress__label">Basic Info</span>
                </div>
                <div class="ap-progress__step" data-step="2">
                    <div class="ap-progress__circle">
                        <span class="ap-progress__num">2</span>
                        <svg class="ap-progress__check" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <span class="ap-progress__label">Details</span>
                </div>
                <div class="ap-progress__step" data-step="3">
                    <div class="ap-progress__circle">
                        <span class="ap-progress__num">3</span>
                        <svg class="ap-progress__check" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <span class="ap-progress__label">Location</span>
                </div>
                <div class="ap-progress__step" data-step="4">
                    <div class="ap-progress__circle">
                        <span class="ap-progress__num">4</span>
                        <svg class="ap-progress__check" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <span class="ap-progress__label">Photos</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="ap-form-wrap">
        <?php if ($is_edit): ?>
        <script>var sbEditProp = <?php echo wp_json_encode($edit_data); ?>;</script>
        <?php endif; ?>

        <form id="ap-form" novalidate>
            <?php wp_nonce_field('sb_add_property', 'ap_nonce'); ?>
            <?php if ($is_edit): ?>
            <input type="hidden" name="ap_property_id" id="ap-property-id" value="<?php echo esc_attr($edit_id); ?>">
            <?php endif; ?>

            <!-- ═══ STEP 1 — Basic Info ═══ -->
            <div class="ap-step ap-step--active" data-step="1">
                <div class="ap-card">
                    <div class="ap-card__header">
                        <h2 class="ap-card__title">Basic Information</h2>
                        <p class="ap-card__subtitle">Start with the essentials — the title, description and listing type.</p>
                    </div>

                    <div class="ap-card__body">

                        <div class="ap-field">
                            <label class="ap-label" for="ap-title">
                                Property Title <span class="ap-required">*</span>
                            </label>
                            <input type="text" id="ap-title" name="ap_title" class="ap-input ap-input--lg"
                                   placeholder="e.g. Stunning 3-bed Villa with Pool in Ciudad Quesada"
                                   required maxlength="200">
                            <span class="ap-field-error"></span>
                        </div>

                        <div class="ap-field">
                            <label class="ap-label" for="ap-description">Description</label>
                            <textarea id="ap-description" name="ap_description" class="ap-input ap-textarea"
                                      placeholder="Describe the property — location highlights, features, condition, surroundings…"
                                      rows="6"></textarea>
                        </div>

                        <div class="ap-field">
                            <label class="ap-label">Listing Status <span class="ap-required">*</span></label>
                            <div class="ap-radio-cards ap-radio-cards--3">
                                <label class="ap-radio-card" data-value="for-sale">
                                    <input type="radio" name="ap_status" value="for-sale" class="ap-radio-input" required>
                                    <span class="ap-radio-card__icon">
                                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                    </span>
                                    <span class="ap-radio-card__label">For Sale</span>
                                </label>
                                <label class="ap-radio-card" data-value="for-rent">
                                    <input type="radio" name="ap_status" value="for-rent" class="ap-radio-input">
                                    <span class="ap-radio-card__icon">
                                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                                    </span>
                                    <span class="ap-radio-card__label">For Rent</span>
                                </label>
                                <label class="ap-radio-card" data-value="sold">
                                    <input type="radio" name="ap_status" value="sold" class="ap-radio-input">
                                    <span class="ap-radio-card__icon">
                                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                    </span>
                                    <span class="ap-radio-card__label">Sold</span>
                                </label>
                            </div>
                            <span class="ap-field-error" id="ap-status-error"></span>
                        </div>

                        <div class="ap-field">
                            <label class="ap-label">Build Type <span class="ap-required">*</span></label>
                            <div class="ap-radio-cards ap-radio-cards--2">
                                <label class="ap-radio-card" data-value="resale">
                                    <input type="radio" name="ap_build_type" value="resale" class="ap-radio-input" required>
                                    <span class="ap-radio-card__icon">
                                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>
                                    </span>
                                    <span class="ap-radio-card__label">Resale</span>
                                    <span class="ap-radio-card__sub">Second-hand property</span>
                                </label>
                                <label class="ap-radio-card" data-value="new_build">
                                    <input type="radio" name="ap_build_type" value="new_build" class="ap-radio-input">
                                    <span class="ap-radio-card__icon">
                                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
                                    </span>
                                    <span class="ap-radio-card__label">New Build</span>
                                    <span class="ap-radio-card__sub">Brand new development</span>
                                </label>
                            </div>
                            <span class="ap-field-error" id="ap-build-error"></span>
                        </div>

                        <div class="ap-field">
                            <div class="ap-toggle-row">
                                <div class="ap-toggle-text">
                                    <span class="ap-toggle-label">Featured / Exclusive</span>
                                    <span class="ap-toggle-hint">Highlight this listing at the top of search results</span>
                                </div>
                                <label class="ap-toggle">
                                    <input type="checkbox" name="ap_featured" id="ap-featured" value="1">
                                    <span class="ap-toggle__track">
                                        <span class="ap-toggle__thumb"></span>
                                    </span>
                                </label>
                            </div>
                        </div>

                    </div><!-- /.ap-card__body -->
                </div><!-- /.ap-card -->

                <div class="ap-nav ap-nav--right">
                    <button type="button" class="btn btn-primary btn-lg ap-next" data-next="2">
                        Continue to Details
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                </div>
            </div><!-- /.ap-step[1] -->


            <!-- ═══ STEP 2 — Details ═══ -->
            <div class="ap-step" data-step="2">
                <div class="ap-card">
                    <div class="ap-card__header">
                        <h2 class="ap-card__title">Property Details</h2>
                        <p class="ap-card__subtitle">Pricing, size, and reference information.</p>
                    </div>

                    <div class="ap-card__body">

                        <div class="ap-fields-grid ap-fields-grid--2">
                            <div class="ap-field">
                                <label class="ap-label" for="ap-price">
                                    Price (€) <span class="ap-required">*</span>
                                </label>
                                <div class="ap-input-group">
                                    <span class="ap-input-prefix">€</span>
                                    <input type="number" id="ap-price" name="ap_price" class="ap-input ap-input--prefixed"
                                           placeholder="e.g. 189000" min="0" required>
                                </div>
                                <span class="ap-field-error"></span>
                            </div>

                            <div class="ap-field">
                                <label class="ap-label" for="ap-size">Size (m²)</label>
                                <div class="ap-input-group">
                                    <input type="number" id="ap-size" name="ap_size" class="ap-input ap-input--suffixed"
                                           placeholder="e.g. 120" min="0">
                                    <span class="ap-input-suffix">m²</span>
                                </div>
                            </div>
                        </div>

                        <div class="ap-fields-grid ap-fields-grid--2">
                            <div class="ap-field">
                                <label class="ap-label" for="ap-bedrooms">Bedrooms</label>
                                <div class="ap-stepper">
                                    <button type="button" class="ap-stepper__btn ap-stepper__minus" aria-label="Decrease bedrooms">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                    </button>
                                    <input type="number" id="ap-bedrooms" name="ap_bedrooms" class="ap-input ap-stepper__input"
                                           value="0" min="0" max="20" readonly>
                                    <button type="button" class="ap-stepper__btn ap-stepper__plus" aria-label="Increase bedrooms">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                    </button>
                                </div>
                            </div>

                            <div class="ap-field">
                                <label class="ap-label" for="ap-bathrooms">Bathrooms</label>
                                <div class="ap-stepper">
                                    <button type="button" class="ap-stepper__btn ap-stepper__minus" aria-label="Decrease bathrooms">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                    </button>
                                    <input type="number" id="ap-bathrooms" name="ap_bathrooms" class="ap-input ap-stepper__input"
                                           value="0" min="0" max="20" readonly>
                                    <button type="button" class="ap-stepper__btn ap-stepper__plus" aria-label="Increase bathrooms">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="ap-fields-grid ap-fields-grid--2">
                            <div class="ap-field">
                                <label class="ap-label" for="ap-ref">Reference #</label>
                                <input type="text" id="ap-ref" name="ap_ref" class="ap-input"
                                       placeholder="e.g. CQ-2024-001">
                            </div>

                            <div class="ap-field">
                                <label class="ap-label" for="ap-city">City / Location</label>
                                <input type="text" id="ap-city" name="ap_city" class="ap-input"
                                       value="Ciudad Quesada" placeholder="e.g. Ciudad Quesada">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="ap-nav ap-nav--split">
                    <button type="button" class="btn btn-outline btn-lg ap-back" data-back="1">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                        Back
                    </button>
                    <button type="button" class="btn btn-primary btn-lg ap-next" data-next="3">
                        Continue to Location
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                </div>
            </div><!-- /.ap-step[2] -->


            <!-- ═══ STEP 3 — Location ═══ -->
            <div class="ap-step" data-step="3">
                <div class="ap-card">
                    <div class="ap-card__header">
                        <h2 class="ap-card__title">Location</h2>
                        <p class="ap-card__subtitle">Pin the property on the map — click to place a marker or geocode from address.</p>
                    </div>

                    <div class="ap-card__body">

                        <div class="ap-field">
                            <label class="ap-label" for="ap-address">Street Address</label>
                            <div class="ap-address-row">
                                <input type="text" id="ap-address" name="ap_address" class="ap-input"
                                       placeholder="e.g. Calle Mayor 12, Ciudad Quesada">
                                <button type="button" id="ap-geocode-btn" class="btn btn-outline ap-geocode-btn">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                                    Find on Map
                                </button>
                            </div>
                            <span class="ap-geocode-status" id="ap-geocode-status"></span>
                        </div>

                        <div class="ap-map-wrap">
                            <div id="ap-map"></div>
                            <div class="ap-map-hint">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                Click anywhere on the map to place a pin
                            </div>
                        </div>

                        <div class="ap-fields-grid ap-fields-grid--2">
                            <div class="ap-field">
                                <label class="ap-label" for="ap-lat">Latitude</label>
                                <input type="text" id="ap-lat" name="ap_lat" class="ap-input ap-input--mono"
                                       placeholder="e.g. 37.9838" inputmode="decimal">
                            </div>
                            <div class="ap-field">
                                <label class="ap-label" for="ap-lng">Longitude</label>
                                <input type="text" id="ap-lng" name="ap_lng" class="ap-input ap-input--mono"
                                       placeholder="e.g. -0.6821" inputmode="decimal">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="ap-nav ap-nav--split">
                    <button type="button" class="btn btn-outline btn-lg ap-back" data-back="2">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                        Back
                    </button>
                    <button type="button" class="btn btn-primary btn-lg ap-next" data-next="4">
                        Continue to Photos
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                </div>
            </div><!-- /.ap-step[3] -->


            <!-- ═══ STEP 4 — Photos ═══ -->
            <div class="ap-step" data-step="4">
                <div class="ap-card">
                    <div class="ap-card__header">
                        <h2 class="ap-card__title">Photos</h2>
                        <p class="ap-card__subtitle">Upload up to 20 photos. The first photo will be the cover image.</p>
                    </div>

                    <div class="ap-card__body">

                        <div class="ap-drop-zone" id="ap-drop-zone" role="button" tabindex="0" aria-label="Upload photos">
                            <input type="file" id="ap-file-input" accept="image/*" multiple class="ap-file-input">
                            <div class="ap-drop-zone__inner">
                                <div class="ap-drop-zone__icon">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                </div>
                                <p class="ap-drop-zone__title">Drag &amp; drop photos here</p>
                                <p class="ap-drop-zone__sub">or <span class="ap-drop-zone__browse">click to browse</span></p>
                                <p class="ap-drop-zone__meta">JPG, PNG, WebP — max 20 photos — up to 10 MB each</p>
                            </div>
                        </div>

                        <div class="ap-upload-progress" id="ap-upload-progress" style="display:none">
                            <div class="ap-upload-progress__bar">
                                <div class="ap-upload-progress__fill" id="ap-upload-bar"></div>
                            </div>
                            <span class="ap-upload-progress__label" id="ap-upload-label">Uploading…</span>
                        </div>

                        <div class="ap-image-grid" id="ap-image-grid"></div>

                        <!-- Hidden: stores attachment IDs -->
                        <div id="ap-attachment-ids"></div>

                    </div>
                </div>

                <!-- Submit error message -->
                <div class="ap-submit-error" id="ap-submit-error" style="display:none" role="alert"></div>

                <div class="ap-nav ap-nav--split">
                    <button type="button" class="btn btn-outline btn-lg ap-back" data-back="3">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                        Back
                    </button>
                    <button type="submit" id="ap-submit-btn" class="btn btn-primary btn-lg ap-submit-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        <?php echo $is_edit ? 'Update Listing' : 'Publish Listing'; ?>
                    </button>
                </div>
            </div><!-- /.ap-step[4] -->

        </form><!-- /#ap-form -->

        <!-- ═══ SUCCESS STATE ═══ -->
        <div class="ap-success" id="ap-success" style="display:none" role="status" aria-live="polite">
            <div class="ap-success__icon">
                <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <h2 class="ap-success__title"><?php echo $is_edit ? 'Listing Updated!' : 'Property Published!'; ?></h2>
            <p class="ap-success__sub"><?php echo $is_edit ? 'Your changes have been saved successfully.' : 'Your listing is now live and visible on the site.'; ?></p>
            <div class="ap-success__actions">
                <a href="#" id="ap-success-link" class="btn btn-primary btn-lg" target="_blank">
                    View Listing
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                </a>
                <?php if ($is_edit): ?>
                <a href="<?php echo esc_url(home_url('/dashboard')); ?>" class="btn btn-outline btn-lg">
                    Back to Dashboard
                </a>
                <?php else: ?>
                <a href="<?php echo esc_url(get_permalink()); ?>" class="btn btn-outline btn-lg">
                    Add Another Property
                </a>
                <?php endif; ?>
        </div>

    </div><!-- /.ap-form-wrap -->

</div><!-- /.add-property-page -->

<?php get_footer(); ?>
