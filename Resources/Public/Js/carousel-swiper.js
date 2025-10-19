/**
 * Swiper Carousel Initialization for Clubdata Extension
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all Swiper carousels on the page
    const swiperContainers = document.querySelectorAll('.clubdata-swiper');
    
    swiperContainers.forEach(function(container) {
        // Get configuration from data attributes
        const autoplay = container.dataset.autoplay === 'true' || container.dataset.autoplay === '1';
        const loop = container.dataset.loop === 'true';
        const slidesPerView = parseInt(container.dataset.slidesPerView) || 1;
        const spaceBetween = parseInt(container.dataset.spaceBetween) || 30;
        const centeredSlides = container.dataset.centeredSlides === 'true';
        const autoplayDelay = parseInt(container.dataset.autoplayDelay) || 5000;
        
        // Get responsive breakpoint configuration
        const breakpointMobile = parseInt(container.dataset.breakpointMobile) || 320;
        const breakpointTablet = parseInt(container.dataset.breakpointTablet) || 768;
        const breakpointDesktop = parseInt(container.dataset.breakpointDesktop) || 1024;
        const mobileSlides = parseInt(container.dataset.mobileSlides) || 1;
        const tabletSlides = parseInt(container.dataset.tabletSlides) || Math.min(2, slidesPerView);
        const desktopSlides = parseInt(container.dataset.desktopSlides) || slidesPerView;
        const mobileSpacing = parseInt(container.dataset.mobileSpacing) || 20;
        const tabletSpacing = parseInt(container.dataset.tabletSpacing) || 25;
        const desktopSpacing = parseInt(container.dataset.desktopSpacing) || spaceBetween;
        
        // Check if navigation elements exist
        const hasNavigation = container.querySelector('.swiper-button-next') && 
                            container.querySelector('.swiper-button-prev');
        const hasPagination = container.querySelector('.swiper-pagination');
        const hasScrollbar = container.querySelector('.swiper-scrollbar');
        
        
        // Swiper configuration
        const swiperConfig = {
            // Basic settings
            loop: loop,
            slidesPerView: slidesPerView,
            spaceBetween: spaceBetween,
            centeredSlides: centeredSlides,
            
            // Autoplay configuration
            autoplay: autoplay ? {
                delay: autoplayDelay,
                disableOnInteraction: false,
                pauseOnMouseEnter: true
            } : false,
            
            // Effects
            effect: 'slide',
            speed: 600,
            
            // Responsive breakpoints (configurable)
            breakpoints: {
                [breakpointMobile]: {
                    slidesPerView: mobileSlides,
                    spaceBetween: mobileSpacing
                },
                [breakpointTablet]: {
                    slidesPerView: tabletSlides,
                    spaceBetween: tabletSpacing
                },
                [breakpointDesktop]: {
                    slidesPerView: desktopSlides,
                    spaceBetween: desktopSpacing
                }
            },
            
            // Keyboard control
            keyboard: {
                enabled: true,
                onlyInViewport: false
            },
            
            // Mouse wheel control
            mousewheel: {
                invert: false
            },
            
            // Touch settings
            touchRatio: 1,
            touchAngle: 45,
            grabCursor: true,
            
            // Lazy loading
            lazy: {
                loadPrevNext: true,
                loadPrevNextAmount: 1
            },
            
            // Additional options for peek effect
            watchOverflow: true,
            normalizeSlideIndex: false,
            watchSlidesProgress: true,
            watchSlidesVisibility: true
        };
        
        // Add navigation if elements exist
        if (hasNavigation) {
            swiperConfig.navigation = {
                nextEl: container.querySelector('.swiper-button-next'),
                prevEl: container.querySelector('.swiper-button-prev')
            };
        }
        
        // Add pagination if element exists
        if (hasPagination) {
            swiperConfig.pagination = {
                el: container.querySelector('.swiper-pagination'),
                clickable: true,
                dynamicBullets: false,
                dynamicMainBullets: 5
            };
        }
        
        // Add scrollbar if element exists
        if (hasScrollbar) {
            swiperConfig.scrollbar = {
                el: container.querySelector('.swiper-scrollbar'),
                draggable: true,
                hide: false
            };
        }
        
        // Initialize Swiper
        const swiper = new Swiper(container, swiperConfig);
        
        // Add custom event listeners
        swiper.on('slideChange', function() {
            // Ensure caption visibility is updated after slide transition
            updateActiveCaptions(container);
            
            // Optional: Add analytics tracking or custom behavior
            console.log('Slide changed to:', swiper.activeIndex);
        });
        
        // Also update on transition end for extra reliability
        swiper.on('slideChangeTransitionEnd', function() {
            updateActiveCaptions(container);
        });
        
        // Initialize captions on load
        setTimeout(function() {
            updateActiveCaptions(container);
        }, 100);
        
        
        // Pause autoplay on hover if enabled
        if (autoplay) {
            container.addEventListener('mouseenter', function() {
                swiper.autoplay.stop();
            });
            
            container.addEventListener('mouseleave', function() {
                swiper.autoplay.start();
            });
        }
        
        // Handle visibility change (pause when tab is not active)
        if (autoplay) {
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    swiper.autoplay.stop();
                } else {
                    swiper.autoplay.start();
                }
            });
        }
        
        // Store swiper instance for potential external access
        container.swiperInstance = swiper;
    });
});

/**
 * Utility function to get Swiper instance from container
 * @param {Element} container - The swiper container element
 * @returns {Swiper|null} - The Swiper instance or null
 */
window.getSwiperInstance = function(container) {
    return container && container.swiperInstance ? container.swiperInstance : null;
};

/**
 * Utility function to control all Swiper instances on the page
 */
window.controlAllSwipers = {
    play: function() {
        document.querySelectorAll('.clubdata-swiper').forEach(function(container) {
            if (container.swiperInstance && container.swiperInstance.autoplay) {
                container.swiperInstance.autoplay.start();
            }
        });
    },
    pause: function() {
        document.querySelectorAll('.clubdata-swiper').forEach(function(container) {
            if (container.swiperInstance && container.swiperInstance.autoplay) {
                container.swiperInstance.autoplay.stop();
            }
        });
    },
    destroy: function() {
        document.querySelectorAll('.clubdata-swiper').forEach(function(container) {
            if (container.swiperInstance) {
                container.swiperInstance.destroy(true, true);
                container.swiperInstance = null;
            }
        });
    }
};

/**
 * Helper function to ensure proper caption visibility for active slide
 * @param {Element} container - The swiper container element
 */
function updateActiveCaptions(container) {
    // Force a reflow to ensure Swiper classes are properly applied
    container.offsetHeight;
    
    // Remove any custom active-caption classes
    const allSlides = container.querySelectorAll('.swiper-slide');
    allSlides.forEach(function(slide) {
        slide.classList.remove('active-caption');
    });
    
    // The CSS rule .swiper-slide-active handles the visibility automatically
    // This function mainly ensures proper cleanup and can add custom logic if needed
    const activeSlide = container.querySelector('.swiper-slide-active');
    if (activeSlide) {
        // Optional: Add custom class for additional styling if needed
        activeSlide.classList.add('current-slide');
        
        // Remove current-slide class from other slides
        allSlides.forEach(function(slide) {
            if (slide !== activeSlide) {
                slide.classList.remove('current-slide');
            }
        });
    }
}


