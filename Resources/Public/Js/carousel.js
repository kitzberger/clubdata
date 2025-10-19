/**
 * Clubdata Carousel JavaScript - Improved Version
 * Handles Jssor slider initialization and responsive behavior
 */

(function() {
    'use strict';

    // Configuration constants
    const CONFIG = {
        SLIDER_ID: 'jssor_1',
        TIMEOUTS: {
            INITIAL_FIX: 50,
            SECONDARY_FIX: 200,
            FINAL_FIX: 500,
            IMAGE_LOAD_CHECK: 100,
            SCALE_RETRY: 30
        },
        DEFAULTS: {
            containerWidth: 980,
            slideWidth: 700,
            slideHeight: 400,
            arrowWidth: 265,
            arrowRightPosition: 715,
            minSlideHeight: 200
        },
        SELECTORS: {
            contentMain: '#content_main',
            slides: '[data-u="slides"]',
            arrowRight: '.clubdata-slider .arrowright',
            fade: '.clubdata-slider .fade',
            sliderContainer: '.clubdata-slider'
        }
    };

    /**
     * Merges user-provided selectors with defaults
     */
    function mergeSelectors(customSelectors) {
        return Object.assign({}, CONFIG.SELECTORS, customSelectors || {});
    }

    /**
     * Validates dependencies and DOM elements
     */
    function validateDependencies() {
        const checks = [
            { condition: typeof jQuery !== 'undefined', error: 'jQuery is not loaded!' },
            { condition: typeof $JssorSlider$ !== 'undefined', error: 'Jssor Slider library not loaded' },
            { condition: typeof $JssorArrowNavigator$ !== 'undefined', error: '$JssorArrowNavigator$ is undefined' },
            { condition: typeof $JssorBulletNavigator$ !== 'undefined', error: '$JssorBulletNavigator$ is undefined' }
        ];

        for (const check of checks) {
            if (!check.condition) {
                console.error('Clubdata Carousel:', check.error);
                return false;
            }
        }
        return true;
    }

    /**
     * Validates settings and DOM container
     */
    function validateConfiguration(settings, selectors) {
        if (!settings) {
            console.error('Clubdata Carousel: Settings not provided');
            return false;
        }

        // Use configurable slider ID or fallback to default
        const sliderId = settings.sliderId || CONFIG.SLIDER_ID;
        const container = document.getElementById(sliderId);
        if (!container) {
            console.error('Clubdata Carousel: Container #' + sliderId + ' not found in DOM');
            return false;
        }

        return { settings, container, sliderId, selectors };
    }

    /**
     * Normalizes and applies default settings
     */
    function normalizeSettings(settings) {
        return {
            containerWidth: parseInt(settings.containerWidth) || CONFIG.DEFAULTS.containerWidth,
            slideWidth: parseInt(settings.slideWidth) || CONFIG.DEFAULTS.slideWidth,
            slideHeight: parseInt(settings.slideHeight) || CONFIG.DEFAULTS.slideHeight,
            autoPlay: settings.autoPlay || false,
            autoScaling: parseInt(settings.autoScaling) || 0,
            arrowWidth: parseInt(settings.arrowWidth) || CONFIG.DEFAULTS.arrowWidth,
            arrowRightPosition: parseInt(settings.arrowRightPosition) || CONFIG.DEFAULTS.arrowRightPosition,
            minSlideHeight: parseInt(settings.minSlideHeight) || CONFIG.DEFAULTS.minSlideHeight,
            sliderId: settings.sliderId || CONFIG.SLIDER_ID
        };
    }

    /**
     * Creates Jssor slider options object
     */
    function createSliderOptions(settings) {
        return {
            $AutoPlay: settings.autoPlay,
            $SlideWidth: settings.slideWidth,
            $SlideHeight: settings.slideHeight,
            $FillMode: 1, // Stretch to fill
            $AutoScaling: settings.autoScaling,
            $Lazy: 1, // Enable lazy loading
            $StartIndex: 0,
            $ArrowNavigatorOptions: {
                $Class: $JssorArrowNavigator$
            },
            $BulletNavigatorOptions: {
                $Class: $JssorBulletNavigator$
            }
        };
    }

    /**
     * Sets CSS custom properties for responsive behavior
     */
    function setCSSProperties(element, settings) {
        const properties = {
            '--container-width': settings.containerWidth + 'px',
            '--slide-width': settings.slideWidth + 'px',
            '--arrow-width': settings.arrowWidth + 'px',
            '--arrow-right-position': settings.arrowRightPosition + 'px',
            '--min-slide-height': settings.minSlideHeight + 'px'
        };

        Object.entries(properties).forEach(([property, value]) => {
            element.style.setProperty(property, value);
        });
    }

    /**
     * Logs configuration details
     */
    function logConfiguration(settings, options, selectors) {
        console.log('Creating Jssor slider with dimensions:', {
            containerWidth: settings.containerWidth,
            slideWidth: options.$SlideWidth,
            height: options.$SlideHeight,
            autoScaling: options.$AutoScaling,
            arrowWidth: settings.arrowWidth,
            arrowRightPosition: settings.arrowRightPosition,
            sliderId: settings.sliderId,
            customSelectors: JSON.stringify(selectors)
        });

        if (options.$AutoScaling === 0) {
            console.log('AutoScaling disabled - will use manual width scaling and remove transforms');
        } else {
            console.log('AutoScaling enabled - will use Jssor\'s built-in scaling');
        }
    }

    /**
     * Height management utilities
     */
    const HeightManager = {
        /**
         * Sets initial heights on slider elements
         */
        setInitialHeights(container, expectedHeight, selectors) {
            const slidesContainer = container.querySelector(selectors.slides);

            // Set main container height
            container.style.height = expectedHeight + 'px';

            // Set slides container height
            if (slidesContainer) {
                slidesContainer.style.height = expectedHeight + 'px';

                // Set individual slide heights
                Array.from(slidesContainer.children).forEach(slide => {
                    slide.style.height = expectedHeight + 'px';
                });
            }

            console.log('Set slider height to:', expectedHeight + 'px');
        },

        /**
         * Removes transform scaling if autoScaling is disabled
         */
        removeTransformScaling(container, autoScaling) {
            if (autoScaling === 0) {
                const transformProperties = ['transform', 'webkitTransform', 'msTransform'];
                transformProperties.forEach(prop => {
                    container.style[prop] = 'none';
                });
                console.log('Removed transform scaling (autoScaling disabled)');
            }
        },

        /**
         * Fixes elements with zero height
         */
        fixZeroHeightElements(container, expectedHeight) {
            const allDivs = container.querySelectorAll('div');
            let fixedCount = 0;

            allDivs.forEach(div => {
                const computedStyle = window.getComputedStyle(div);
                if (computedStyle.height === '0px' || div.offsetHeight === 0) {
                    div.style.height = expectedHeight + 'px';
                    fixedCount++;
                }
            });

            if (fixedCount > 0) {
                console.log('Fixed', fixedCount, 'zero-height elements');
            }

            // Ensure main container has correct height
            if (container.offsetHeight !== expectedHeight) {
                container.style.height = expectedHeight + 'px';
                console.log('Forced slider height to:', expectedHeight + 'px');
            }
        }
    };

    /**
     * Image loading handler
     */
    function handleImageLoading(container, expectedHeight, fixCallback) {
        const images = container.querySelectorAll('img');
        const totalImages = images.length;
        let imagesLoaded = 0;

        function onImageLoad() {
            imagesLoaded++;
            if (imagesLoaded === totalImages) {
                fixCallback();
                console.log('Fixed heights after all images loaded');
            }
        }

        if (totalImages > 0) {
            images.forEach(img => {
                if (img.complete) {
                    onImageLoad();
                } else {
                    img.addEventListener('load', onImageLoad);
                    img.addEventListener('error', onImageLoad); // Count errors as loaded
                }
            });
        } else {
            fixCallback();
        }
    }

    /**
     * Responsive scaling handler
     */
    function createScaleHandler(slider, settings, container, selectors) {
        const maxWidth = jQuery(selectors.contentMain).width();

        return function ScaleSlider() {
            const containerElement = slider.$Elmt?.parentNode;
            if (!containerElement) {
                return;
            }

            const availableWidth = containerElement.clientWidth;
            if (!availableWidth) {
                setTimeout(ScaleSlider, CONFIG.TIMEOUTS.SCALE_RETRY);
                return;
            }

            const expectedWidth = Math.min(maxWidth || availableWidth, availableWidth);
            const expectedHeight = settings.slideHeight;

            if (settings.autoScaling === 0) {
                performManualScaling(container, settings, expectedWidth, expectedHeight, selectors);
            } else {
                slider.$ScaleWidth(expectedWidth);
                console.log('Jssor auto-scaled slider to width:', expectedWidth);
            }

            updateArrowPositioning(settings, expectedWidth, selectors);
        };
    }

    /**
     * Manual scaling implementation
     */
    function performManualScaling(container, settings, expectedWidth, expectedHeight, selectors) {
        // Set container dimensions
        container.style.width = expectedWidth + 'px';
        container.style.height = expectedHeight + 'px';

        // Update CSS custom properties
        container.style.setProperty('--container-width', expectedWidth + 'px');

        // Calculate and set proportional slide width
        const slideWidthRatio = settings.slideWidth / settings.containerWidth;
        const scaledSlideWidth = expectedWidth * slideWidthRatio;
        container.style.setProperty('--slide-width', scaledSlideWidth + 'px');

        // Update slides container
        const slidesElement = container.querySelector(selectors.slides);
        if (slidesElement) {
            slidesElement.style.width = expectedWidth + 'px';
            slidesElement.style.height = expectedHeight + 'px';
        }

        console.log('Manual scaled slider - container:', expectedWidth + 'px, slides:', scaledSlideWidth + 'px');

        // Remove transform scaling
        HeightManager.removeTransformScaling(container, settings.autoScaling);
    }

    /**
     * Updates arrow positioning based on responsive width
     */
    function updateArrowPositioning(settings, expectedWidth, selectors) {
        const offset = parseInt((expectedWidth / 100) * 2);
        const arrowWidth = settings.arrowWidth;
        let fadeWidth = arrowWidth + 15;
        let leftPosition = settings.arrowRightPosition;

        if (offset > 29) {
            leftPosition += 10;
            fadeWidth = fadeWidth + offset - 5;
        }

        const $arrowRight = jQuery(selectors.arrowRight);
        const $fade = jQuery(selectors.fade);

        $fade.css('width', fadeWidth);
        $arrowRight.css('left', leftPosition);
    }

    /**
     * Initializes the carousel with proper error handling and structure
     */
    function initClubdataCarousel(rawSettings) {
        // Validate dependencies
        if (!validateDependencies()) {
            return false;
        }

        jQuery(document).ready(function($) {
            // Merge custom selectors with defaults
            const selectors = mergeSelectors(rawSettings?.selectors);

            // Validate configuration
            const validation = validateConfiguration(rawSettings, selectors);
            if (!validation) {
                return false;
            }

            const { settings: rawSettingsValidated, container, sliderId } = validation;

            // Normalize settings
            const settings = normalizeSettings(rawSettingsValidated);

            // Create Jssor options
            const sliderOptions = createSliderOptions(settings);

            // Set CSS properties
            setCSSProperties(container, settings);

            // Log configuration
            logConfiguration(settings, sliderOptions, selectors);

            try {
                // Create Jssor slider
                const slider = new $JssorSlider$(sliderId, sliderOptions);

                // Set initial heights
                HeightManager.setInitialHeights(container, settings.slideHeight, selectors);

                // Create height fix function
                const fixHeights = () => {
                    HeightManager.removeTransformScaling(container, settings.autoScaling);
                    HeightManager.fixZeroHeightElements(container, settings.slideHeight);
                };

                // Schedule height fixes at different intervals
                setTimeout(fixHeights, CONFIG.TIMEOUTS.INITIAL_FIX);
                setTimeout(fixHeights, CONFIG.TIMEOUTS.SECONDARY_FIX);
                setTimeout(fixHeights, CONFIG.TIMEOUTS.FINAL_FIX);

                // Handle image loading
                setTimeout(() => {
                    handleImageLoading(container, settings.slideHeight, fixHeights);

                    // Log final dimensions
                    console.log('Slider element dimensions after creation:', {
                        width: container.style.width,
                        height: container.style.height,
                        actualWidth: container.offsetWidth,
                        actualHeight: container.offsetHeight,
                        expectedHeight: settings.slideHeight
                    });
                }, CONFIG.TIMEOUTS.IMAGE_LOAD_CHECK);

                // Set up responsive scaling
                const scaleHandler = createScaleHandler(slider, settings, container, selectors);

                // Initial scaling
                scaleHandler();

                // Bind responsive events
                $(window).on('load resize orientationchange', scaleHandler);

                return true;

            } catch (error) {
                console.error('Clubdata Carousel: Error creating Jssor slider:', error);
                return false;
            }
        });
    }

    /**
     * Parses settings from data attributes
     */
    function parseDataAttributes(element) {
        const dataset = element.dataset;
        const settings = {};

        // Parse carousel settings from data attributes
        Object.keys(dataset).forEach(key => {
            if (key.startsWith('carousel')) {
                // Convert camelCase data attributes to settings
                // e.g., data-carousel-slide-width -> slideWidth
                const settingKey = key.replace('carousel', '').replace(/([A-Z])/g, (match, letter, index) => {
                    return index === 0 ? letter.toLowerCase() : letter;
                });

                let value = dataset[key];

                // Try to parse as number or boolean
                if (value === 'true') {
                    value = true;
                } else if (value === 'false') {
                    value = false;
                } else if (!isNaN(value) && value !== '') {
                    value = parseInt(value);
                }

                settings[settingKey] = value;
            }
        });

        // Parse selectors if provided as JSON
        if (dataset.carouselSelectors) {
            try {
                settings.selectors = JSON.parse(dataset.carouselSelectors);
            } catch (e) {
                console.warn('Clubdata Carousel: Invalid JSON in data-carousel-selectors:', e);
            }
        }

        return settings;
    }

    /**
     * Auto-initializes carousels based on data attributes
     */
    function autoInitializeCarousels() {
        jQuery(document).ready(function($) {
            $('.clubdata-slider').each(function(index, element) {
                const $element = $(element);

                // Skip if already initialized
                if ($element.data('carousel-initialized')) {
                    return;
                }

                // Parse settings from data attributes
                const settings = parseDataAttributes(element);

                // Set the slider ID based on the element's ID or generate one
                if (element.id) {
                    settings.sliderId = element.id;
                } else {
                    // Generate a unique ID if none exists
                    const uniqueId = 'clubdata_carousel_' + (index + 1);
                    element.id = uniqueId;
                    settings.sliderId = uniqueId;
                }

                console.log('Auto-initializing carousel #' + settings.sliderId + ' with settings:', settings);

                // Initialize the carousel
                const success = initClubdataCarousel(settings);

                if (success !== false) {
                    // Mark as initialized to prevent double-initialization
                    $element.data('carousel-initialized', true);
                }
            });
        });
    }

    // Auto-initialize carousels on page load
    autoInitializeCarousels();

    // Export functions to global scope for advanced usage
    window.initClubdataCarousel = initClubdataCarousel;
    window.autoInitializeCarousels = autoInitializeCarousels;

})();
