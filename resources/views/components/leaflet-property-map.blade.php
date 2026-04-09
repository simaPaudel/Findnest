@php
    $mapId = $mapId ?? 'property-map';
    $height = $height ?? '320px';
    $mode = $mode ?? 'picker';
    $latitudeInputId = $latitudeInputId ?? null;
    $longitudeInputId = $longitudeInputId ?? null;
    $initialLatitude = $initialLatitude ?? null;
    $initialLongitude = $initialLongitude ?? null;
    $defaultLatitude = $defaultLatitude ?? 27.7172; // Kathmandu
    $defaultLongitude = $defaultLongitude ?? 85.3240;
    $defaultZoom = $defaultZoom ?? 12;
    $title = $title ?? null;
    $helpText = $helpText ?? null;
@endphp

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="fn-property-map-shell">
    @if($mode !== 'readonly')
        <div class="fn-property-map-search">
            <div class="fn-property-map-search-row">
                <input
                    type="search"
                    class="fn-property-map-search-input"
                    data-property-map-search-input
                    placeholder="Search a place or address"
                    autocomplete="off"
                >
                <button type="button" class="fn-property-map-search-button" data-property-map-search-button>
                    Search
                </button>
            </div>
            <div class="fn-property-map-search-results" data-property-map-search-results hidden></div>
            <p class="fn-property-map-attribution">
                Search powered by OpenStreetMap Nominatim. Please keep searches light.
            </p>
        </div>
    @endif

    @if($title || $helpText)
        <div class="fn-property-map-copy">
            @if($title)
                <h3 class="fn-property-map-title">{{ $title }}</h3>
            @endif
            @if($helpText)
                <p class="fn-property-map-help">{{ $helpText }}</p>
            @endif
        </div>
    @endif

    <div
        id="{{ $mapId }}"
        class="fn-property-map"
        data-mode="{{ $mode }}"
        data-latitude-input="{{ $latitudeInputId }}"
        data-longitude-input="{{ $longitudeInputId }}"
        data-initial-latitude="{{ $initialLatitude }}"
        data-initial-longitude="{{ $initialLongitude }}"
        data-default-latitude="{{ $defaultLatitude }}"
        data-default-longitude="{{ $defaultLongitude }}"
        data-default-zoom="{{ $defaultZoom }}"
        style="height: {{ $height }};"
    ></div>
</div>

<style>
    .fn-property-map-shell {
        margin-top: 16px;
    }

    .fn-property-map-search {
        margin-bottom: 14px;
        padding: 14px;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        background: #fff;
    }

    .fn-property-map-search-row {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .fn-property-map-search-input {
        flex: 1;
        min-width: 0;
        height: 42px;
        padding: 0 14px;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        font-size: 0.92rem;
        color: #0f172a;
        outline: none;
    }

    .fn-property-map-search-input:focus {
        border-color: #ff385c;
        box-shadow: 0 0 0 3px rgba(255, 56, 92, 0.08);
    }

    .fn-property-map-search-button {
        height: 42px;
        padding: 0 16px;
        border: 0;
        border-radius: 12px;
        background: #ff385c;
        color: #fff;
        font-size: 0.88rem;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.18s ease;
        white-space: nowrap;
    }

    .fn-property-map-search-button:hover {
        background: #e11d48;
    }

    .fn-property-map-search-button:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .fn-property-map-search-results {
        display: grid;
        gap: 8px;
        margin-top: 12px;
    }

    .fn-property-map-search-result {
        width: 100%;
        text-align: left;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #f8fafc;
        padding: 10px 12px;
        cursor: pointer;
        transition: border-color 0.18s ease, background 0.18s ease;
    }

    .fn-property-map-search-result:hover {
        border-color: #ffb3c1;
        background: #fff7f8;
    }

    .fn-property-map-search-result strong {
        display: block;
        font-size: 0.88rem;
        color: #0f172a;
    }

    .fn-property-map-search-result span {
        display: block;
        margin-top: 4px;
        font-size: 0.78rem;
        line-height: 1.45;
        color: #64748b;
    }

    .fn-property-map-search-empty {
        padding: 10px 2px 0;
        font-size: 0.82rem;
        color: #64748b;
    }

    .fn-property-map-attribution {
        margin: 10px 0 0;
        font-size: 0.75rem;
        line-height: 1.5;
        color: #64748b;
    }

    .fn-property-map-copy {
        margin-bottom: 12px;
    }

    .fn-property-map-title {
        margin: 0;
        font-size: 0.96rem;
        font-weight: 700;
        color: #0f172a;
    }

    .fn-property-map-help {
        margin: 4px 0 0;
        font-size: 0.84rem;
        line-height: 1.55;
        color: #64748b;
    }

    .fn-property-map {
        width: 100%;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        overflow: hidden;
        background: #f8fafc;
    }
</style>

<script>
    (function () {
        const mapEl = document.getElementById(@json($mapId));
        if (!mapEl || typeof L === 'undefined') {
            return;
        }

        const mode = mapEl.dataset.mode || 'picker';
        const latitudeInputId = mapEl.dataset.latitudeInput || '';
        const longitudeInputId = mapEl.dataset.longitudeInput || '';

        const parseNumber = (value) => {
            const parsed = parseFloat(value);
            return Number.isFinite(parsed) ? parsed : null;
        };

        const initialLatitude = parseNumber(mapEl.dataset.initialLatitude);
        const initialLongitude = parseNumber(mapEl.dataset.initialLongitude);
        const defaultLatitude = parseNumber(mapEl.dataset.defaultLatitude) ?? 27.7172;
        const defaultLongitude = parseNumber(mapEl.dataset.defaultLongitude) ?? 85.3240;
        const defaultZoom = parseInt(mapEl.dataset.defaultZoom || '12', 10) || 12;

        const latitudeInput = latitudeInputId ? document.getElementById(latitudeInputId) : null;
        const longitudeInput = longitudeInputId ? document.getElementById(longitudeInputId) : null;
        const searchInput = mapEl.closest('.fn-property-map-shell')?.querySelector('[data-property-map-search-input]') || null;
        const searchButton = mapEl.closest('.fn-property-map-shell')?.querySelector('[data-property-map-search-button]') || null;
        const searchResults = mapEl.closest('.fn-property-map-shell')?.querySelector('[data-property-map-search-results]') || null;

        const inputLatitude = latitudeInput ? parseNumber(latitudeInput.value) : null;
        const inputLongitude = longitudeInput ? parseNumber(longitudeInput.value) : null;

        const hasCoordinates = initialLatitude !== null && initialLongitude !== null;
        const activeLatitude = hasCoordinates ? initialLatitude : inputLatitude;
        const activeLongitude = hasCoordinates ? initialLongitude : inputLongitude;
        const center = activeLatitude !== null && activeLongitude !== null
            ? [activeLatitude, activeLongitude]
            : [defaultLatitude, defaultLongitude];

        const map = L.map(mapEl, {
            center,
            zoom: activeLatitude !== null && activeLongitude !== null ? Math.max(defaultZoom, 15) : defaultZoom,
            scrollWheelZoom: false,
            zoomControl: mode !== 'readonly',
            dragging: mode !== 'readonly',
            touchZoom: mode !== 'readonly',
            doubleClickZoom: mode !== 'readonly',
            boxZoom: mode !== 'readonly',
            keyboard: mode !== 'readonly',
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        let marker = null;

        const formatCoordinate = (value) => {
            const normalized = Number(value).toFixed(7);
            return normalized.replace(/\.?0+$/, '');
        };

        const syncInputs = (lat, lng) => {
            if (latitudeInput) {
                latitudeInput.value = formatCoordinate(lat);
            }

            if (longitudeInput) {
                longitudeInput.value = formatCoordinate(lng);
            }
        };

        const placeMarker = (lat, lng, shouldPan = true) => {
            const position = [lat, lng];

            if (!marker) {
                marker = L.marker(position).addTo(map);
            } else {
                marker.setLatLng(position);
            }

            if (shouldPan) {
                map.setView(position, Math.max(defaultZoom, 15));
            }

            syncInputs(lat, lng);
        };

        const clearSearchResults = () => {
            if (!searchResults) {
                return;
            }

            searchResults.innerHTML = '';
            searchResults.hidden = true;
        };

        const renderSearchResults = (results) => {
            if (!searchResults) {
                return;
            }

            searchResults.innerHTML = '';

            if (!results.length) {
                searchResults.hidden = false;
                const emptyState = document.createElement('div');
                emptyState.className = 'fn-property-map-search-empty';
                emptyState.textContent = 'No locations found.';
                searchResults.appendChild(emptyState);
                return;
            }

            results.forEach((result) => {
                const item = document.createElement('button');
                item.type = 'button';
                item.className = 'fn-property-map-search-result';
                const title = document.createElement('strong');
                title.textContent = result.display_name || 'Result';
                const hint = document.createElement('span');
                hint.textContent = 'Click to place this location on the map.';
                item.append(title, hint);
                item.addEventListener('click', () => {
                    const lat = parseNumber(result.lat);
                    const lng = parseNumber(result.lon);

                    if (lat === null || lng === null) {
                        return;
                    }

                    if (searchInput) {
                        searchInput.value = result.display_name || searchInput.value;
                    }

                    placeMarker(lat, lng, true);
                    clearSearchResults();
                });
                searchResults.appendChild(item);
            });

            searchResults.hidden = false;
        };

        let lastSearchAt = 0;
        let activeSearchController = null;

        const searchPlaces = async () => {
            if (!searchInput || !searchButton || !searchResults) {
                return;
            }

            const query = searchInput.value.trim();
            if (!query) {
                clearSearchResults();
                return;
            }

            const now = Date.now();
            if (now - lastSearchAt < 1000) {
                return;
            }
            lastSearchAt = now;

            if (activeSearchController) {
                activeSearchController.abort();
            }

            activeSearchController = new AbortController();
            searchButton.disabled = true;
            searchResults.hidden = false;
            searchResults.innerHTML = '<div class="fn-property-map-search-empty">Searching...</div>';

            try {
                const url = new URL('https://nominatim.openstreetmap.org/search');
                url.searchParams.set('q', query);
                url.searchParams.set('format', 'jsonv2');
                url.searchParams.set('limit', '5');
                url.searchParams.set('addressdetails', '1');

                const bounds = map.getBounds();
                const southWest = bounds.getSouthWest();
                const northEast = bounds.getNorthEast();
                url.searchParams.set(
                    'viewbox',
                    [southWest.lng, southWest.lat, northEast.lng, northEast.lat].join(',')
                );

                const response = await fetch(url.toString(), {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                    },
                    signal: activeSearchController.signal,
                });

                if (!response.ok) {
                    throw new Error('Search failed');
                }

                const results = await response.json();
                renderSearchResults(Array.isArray(results) ? results : []);
            } catch (error) {
                if (error.name !== 'AbortError') {
                    searchResults.innerHTML = '<div class="fn-property-map-search-empty">Unable to search right now.</div>';
                    searchResults.hidden = false;
                }
            } finally {
                searchButton.disabled = false;
                activeSearchController = null;
            }
        };

        if (activeLatitude !== null && activeLongitude !== null) {
            placeMarker(activeLatitude, activeLongitude, false);
        }

        if (mode !== 'readonly') {
            map.on('click', function (event) {
                placeMarker(event.latlng.lat, event.latlng.lng, true);
            });

            const syncFromInputs = () => {
                const lat = latitudeInput ? parseNumber(latitudeInput.value) : null;
                const lng = longitudeInput ? parseNumber(longitudeInput.value) : null;

                if (lat === null || lng === null) {
                    return;
                }

                placeMarker(lat, lng, false);
            };

            if (latitudeInput) {
                latitudeInput.addEventListener('change', syncFromInputs);
            }

            if (longitudeInput) {
                longitudeInput.addEventListener('change', syncFromInputs);
            }

            if (searchButton && searchInput) {
                searchButton.addEventListener('click', searchPlaces);
                searchInput.addEventListener('keydown', (event) => {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        searchPlaces();
                    }
                });
                searchInput.addEventListener('input', () => {
                    if (!searchInput.value.trim()) {
                        clearSearchResults();
                    }
                });
            }
        }

        const invalidate = () => {
            requestAnimationFrame(() => {
                map.invalidateSize();
                if (marker) {
                    map.panTo(marker.getLatLng());
                }
            });
        };

        window.addEventListener('resize', invalidate);
        window.addEventListener('fn-property-map-invalidate', invalidate);
    })();
</script>
