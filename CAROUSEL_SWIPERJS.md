# SwiperJS Carousel Documentation

## Overview

The SwiperJS carousel implementation for the clubdata TYPO3 extension provides a modern, feature-rich image slider using the Swiper library. It displays event/program data with images in a high-performance, mobile-first carousel with advanced navigation, effects, and customization options.

SwiperJS is known for its excellent touch support, smooth animations, and extensive feature set including parallax effects, lazy loading, virtual slides, and advanced navigation options.

## Architecture

### Core Components

- **Template**: `Resources/Private/Templates/Club/CarouselSwiper.html`
- **Partial**: `Resources/Private/Partials/Club/CarouselSwiperItem.html`
- **JavaScript**: `Resources/Public/Js/carousel-swiper.js`
- **CSS**: `Resources/Public/Css/carousel-swiper.css`
- **Library**: Swiper 11.x (latest stable version)

### Controller Action

The SwiperJS carousel uses the same controller action as other carousels:
- `ClubController::carouselAction()` filters programs with images
- Respects date filtering and `greaternow` settings
- Passes programs to the Swiper carousel template

## Configuration

### TypoScript Settings

```typoscript
plugin.tx_clubdata.settings.carousel.swiper {
    # Core Swiper Settings
    slidesPerView = 1                   # Number of slides per view (can be 'auto')
    spaceBetween = 30                   # Space between slides in px
    centeredSlides = 0                  # Center slides
    loop = 1                            # Enable infinite loop
    speed = 300                         # Transition speed in ms
    
    # Autoplay Settings
    autoplay {
        delay = 5000                    # Delay between transitions in ms (0 = disabled)
        pauseOnMouseEnter = 1           # Pause autoplay on mouse enter
        disableOnInteraction = 0        # Don't disable after user interactions
        reverseDirection = 0            # Reverse autoplay direction
    }
    
    # Navigation
    navigation = 1                      # Enable prev/next arrows
    pagination = 1                      # Enable pagination dots/bullets
    scrollbar = 0                       # Enable scrollbar
    
    # Pagination Settings
    paginationType = bullets            # bullets, fraction, progressbar, custom
    clickablePagination = 1             # Make pagination clickable
    dynamicBullets = 1                  # Dynamic bullet pagination
    
    # Effects
    effect = slide                      # slide, fade, cube, coverflow, flip, cards, creative
    
    # Responsive Breakpoints
    breakpoints {
        320 {
            slidesPerView = 1
            spaceBetween = 20
        }
        768 {
            slidesPerView = 2
            spaceBetween = 30
        }
        1024 {
            slidesPerView = 3
            spaceBetween = 40
        }
    }
    
    # Image Settings
    media {
        image {
            maxHeight = 500
            cropVariant = swiperSlider
        }
    }
    
    # Advanced Features
    lazy = 1                            # Enable lazy loading
    grabCursor = 1                      # Change cursor to grab
    keyboard = 1                        # Enable keyboard control
    mousewheel = 0                      # Enable mousewheel control
    parallax = 0                        # Enable parallax effects
    
    # Display Settings
    showCaptions = 1                    # Show slide captions
    datetimeFormat = %d.%m.%Y
}
```

### Advanced Effect Configurations

#### Coverflow Effect
```typoscript
effect = coverflow
coverflowEffect {
    rotate = 50
    stretch = 0
    depth = 100
    modifier = 1
    slideShadows = 1
}
```

#### Cube Effect
```typoscript
effect = cube
cubeEffect {
    shadow = 1
    slideShadows = 1
    shadowOffset = 20
    shadowScale = 0.94
}
```

#### Cards Effect
```typoscript
effect = cards
cardsEffect {
    perSlideOffset = 8
    perSlideRotate = 2
    rotate = 1
    slideShadows = 1
}
```

## HTML Structure

### Basic Structure

```html
<div class="tx-clubdata-carousel-swiper">
    <div class="swiper clubdata-swiper">
        <!-- Wrapper for slides -->
        <div class="swiper-wrapper">
            <!-- Individual Slide -->
            <div class="swiper-slide">
                <div class="slide-content">
                    <img src="image.jpg" class="slide-image" alt="Event Image" loading="lazy">
                    <div class="slide-caption">
                        <div class="caption-content">
                            <span class="event-date">19.10.2024</span>
                            <h3 class="event-title">Event Title</h3>
                            <p class="event-subtitle">Event Subtitle</p>
                            <a href="/detail/123" class="btn btn-primary">Details</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Navigation arrows (if enabled) -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        
        <!-- Pagination (if enabled) -->
        <div class="swiper-pagination"></div>
        
        <!-- Scrollbar (if enabled) -->
        <div class="swiper-scrollbar"></div>
    </div>
</div>
```

### Lazy Loading Structure

```html
<div class="swiper-slide">
    <div class="slide-content">
        <!-- Lazy loading placeholder -->
        <div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>
        
        <!-- Lazy loaded image -->
        <img data-src="image.jpg" class="swiper-lazy slide-image" alt="Event Image">
        
        <!-- Background lazy loading -->
        <div class="slide-bg swiper-lazy" data-background="image.jpg"></div>
    </div>
</div>
```

## JavaScript Integration

### Basic Initialization

```javascript
// Initialize Swiper
const swiper = new Swiper('.clubdata-swiper', {
    // Core parameters
    slidesPerView: 1,
    spaceBetween: 30,
    loop: true,
    speed: 300,
    
    // Autoplay
    autoplay: {
        delay: 5000,
        pauseOnMouseEnter: true,
        disableOnInteraction: false,
    },
    
    // Navigation arrows
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    
    // Pagination
    pagination: {
        el: '.swiper-pagination',
        type: 'bullets',
        clickable: true,
        dynamicBullets: true,
    },
    
    // Responsive breakpoints
    breakpoints: {
        320: { slidesPerView: 1, spaceBetween: 20 },
        768: { slidesPerView: 2, spaceBetween: 30 },
        1024: { slidesPerView: 3, spaceBetween: 40 }
    },
    
    // Additional features
    lazy: true,
    grabCursor: true,
    keyboard: { enabled: true },
});
```

### Event Handling

```javascript
// Swiper event listeners
swiper.on('slideChange', function () {
    console.log('Slide changed to:', swiper.activeIndex);
});

swiper.on('autoplayStart', function () {
    console.log('Autoplay started');
});

swiper.on('autoplayStop', function () {
    console.log('Autoplay stopped');
});

swiper.on('lazyImageReady', function (swiper, slideEl, imageEl) {
    console.log('Lazy image loaded');
});
```

### Custom Controls and Methods

```javascript
// Custom navigation
document.getElementById('custom-prev').addEventListener('click', () => {
    swiper.slidePrev();
});

document.getElementById('custom-next').addEventListener('click', () => {
    swiper.slideNext();
});

// Go to specific slide
document.getElementById('go-to-slide-3').addEventListener('click', () => {
    swiper.slideTo(2); // 0-indexed
});

// Toggle autoplay
document.getElementById('toggle-autoplay').addEventListener('click', () => {
    if (swiper.autoplay.running) {
        swiper.autoplay.stop();
    } else {
        swiper.autoplay.start();
    }
});

// Update slides dynamically
function updateSlides(newSlides) {
    swiper.removeAllSlides();
    swiper.appendSlide(newSlides);
    swiper.update();
}
```

## Advanced Features

### Virtual Slides

For large datasets, enable virtual slides for better performance:

```javascript
const swiper = new Swiper('.clubdata-swiper', {
    virtual: true,
    slidesPerView: 3,
    spaceBetween: 30,
    
    virtual: {
        slides: (function() {
            const slides = [];
            for (let i = 0; i < 1000; i++) {
                slides.push(`<div class="swiper-slide">Slide ${i + 1}</div>`);
            }
            return slides;
        })(),
    },
});
```

### Parallax Effects

```html
<!-- Enable parallax on container -->
<div class="swiper clubdata-swiper" data-swiper-parallax="-23%">
    <div class="swiper-wrapper">
        <div class="swiper-slide">
            <!-- Parallax elements -->
            <div class="slide-bg" data-swiper-parallax="-300"></div>
            <div class="slide-caption" data-swiper-parallax="-100" data-swiper-parallax-opacity="0.5">
                <h3 data-swiper-parallax="-200">Title</h3>
                <p data-swiper-parallax="-300">Subtitle</p>
            </div>
        </div>
    </div>
</div>
```

### Thumbs Navigation

```html
<!-- Main swiper -->
<div class="swiper main-swiper">
    <div class="swiper-wrapper"><!-- slides --></div>
</div>

<!-- Thumbs swiper -->
<div class="swiper thumbs-swiper">
    <div class="swiper-wrapper"><!-- thumb slides --></div>
</div>
```

```javascript
const thumbsSwiper = new Swiper('.thumbs-swiper', {
    spaceBetween: 10,
    slidesPerView: 4,
    freeMode: true,
    watchSlidesProgress: true,
});

const mainSwiper = new Swiper('.main-swiper', {
    spaceBetween: 10,
    thumbs: {
        swiper: thumbsSwiper,
    },
});
```

## Styling and Customization

### CSS Custom Properties

```css
.tx-clubdata-carousel-swiper {
    --swiper-theme-color: #007aff;
    --swiper-navigation-size: 44px;
    --swiper-pagination-bullet-size: 8px;
    --swiper-pagination-bullet-horizontal-gap: 4px;
    --slide-height: 500px;
    --caption-bg: rgba(0, 0, 0, 0.7);
}

.clubdata-swiper {
    height: var(--slide-height);
}

.swiper-slide {
    text-align: center;
    font-size: 18px;
    background: #fff;
    display: flex;
    justify-content: center;
    align-items: center;
}

.slide-image {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
}
```

### Custom Navigation Styling

```css
.swiper-button-next,
.swiper-button-prev {
    color: var(--swiper-theme-color);
    background: rgba(255, 255, 255, 0.9);
    border-radius: 50%;
    width: var(--swiper-navigation-size);
    height: var(--swiper-navigation-size);
    margin-top: calc(0px - var(--swiper-navigation-size) / 2);
}

.swiper-button-next:after,
.swiper-button-prev:after {
    font-size: 20px;
    font-weight: 900;
}
```

### Custom Pagination Styling

```css
.swiper-pagination-bullet {
    width: var(--swiper-pagination-bullet-size);
    height: var(--swiper-pagination-bullet-size);
    background: rgba(255, 255, 255, 0.5);
}

.swiper-pagination-bullet-active {
    background: var(--swiper-theme-color);
}

/* Progress bar pagination */
.swiper-pagination-progressbar {
    background: rgba(0, 0, 0, 0.25);
}

.swiper-pagination-progressbar-fill {
    background: var(--swiper-theme-color);
}
```

## Asset Management

### TYPO3 Asset Integration

```html
<!-- Swiper CSS -->
<f:asset.css identifier="swiper-css" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<!-- Custom Swiper styles -->
<f:asset.css identifier="carousel-swiper-css" href="EXT:clubdata/Resources/Public/Css/carousel-swiper.css" />

<!-- Swiper JavaScript -->
<f:asset.script identifier="swiper-js" src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" />

<!-- Custom Swiper JavaScript -->
<f:asset.script identifier="carousel-swiper-js" src="EXT:clubdata/Resources/Public/Js/carousel-swiper.js" />
```

### Modular Swiper (Smaller Bundle)

```html
<!-- Load only required Swiper modules -->
<f:asset.script identifier="swiper-core">
import Swiper, { Navigation, Pagination, Autoplay } from 'swiper';
Swiper.use([Navigation, Pagination, Autoplay]);
</f:asset.script>
```

## Image Processing and Optimization

### Responsive Images with Swiper

```html
<f:image class="swiper-lazy slide-image"
         data-src="{f:uri.image(src: image.uid, treatIdAsReference: 1, cropVariant: 'swiperSlider')}"
         src="{f:uri.image(src: image.uid, treatIdAsReference: 1, width: 50, cropVariant: 'swiperSlider')}"
         additionalAttributes="{
             alt: '{program.title}',
             data-srcset: '{f:uri.image(src: image.uid, treatIdAsReference: 1, width: 576, cropVariant: \'swiperSlider\')} 576w,
                          {f:uri.image(src: image.uid, treatIdAsReference: 1, width: 768, cropVariant: \'swiperSlider\')} 768w,
                          {f:uri.image(src: image.uid, treatIdAsReference: 1, width: 1024, cropVariant: \'swiperSlider\')} 1024w,
                          {f:uri.image(src: image.uid, treatIdAsReference: 1, width: 1200, cropVariant: \'swiperSlider\')} 1200w',
             data-sizes: '100vw'
         }" />
```

### Background Images

```html
<div class="swiper-slide">
    <div class="slide-bg swiper-lazy" 
         data-background="{f:uri.image(src: image.uid, treatIdAsReference: 1, cropVariant: 'swiperSlider')}">
        <div class="swiper-lazy-preloader"></div>
    </div>
</div>
```

## Performance Optimization

### Bundle Size Optimization

```javascript
// Import only required modules
import Swiper, { 
    Navigation, 
    Pagination, 
    Autoplay, 
    Lazy,
    EffectFade 
} from 'swiper';

// Configure Swiper to use only these modules
Swiper.use([Navigation, Pagination, Autoplay, Lazy, EffectFade]);
```

### Virtual Slides for Large Datasets

```javascript
const swiper = new Swiper('.clubdata-swiper', {
    virtual: {
        slides: virtualSlides,
        renderSlide: function (slide, index) {
            return `<div class="swiper-slide" style="left: ${index * 100}%">${slide}</div>`;
        },
    },
});
```

### Image Optimization

```css
.slide-image {
    will-change: transform;
    backface-visibility: hidden;
    transform: translateZ(0);
}
```

## Browser Compatibility

- **Modern Browsers**: Full support with all features
- **IE11**: Requires polyfills for newer JavaScript features
- **Touch Devices**: Excellent native touch support
- **Performance**: Hardware acceleration on supported devices
- **Accessibility**: ARIA attributes and keyboard navigation support

## Troubleshooting

### Common Issues

1. **Slides Not Showing**: Check `.swiper-wrapper` and `.swiper-slide` classes
2. **Touch Not Working**: Ensure proper viewport meta tag and touch-action CSS
3. **Lazy Loading Issues**: Verify `data-src` attributes and lazy loading configuration
4. **Performance Issues**: Consider virtual slides for large datasets
5. **CSS Conflicts**: Check for conflicting styles affecting `.swiper-*` classes

### Debug Configuration

```javascript
const swiper = new Swiper('.clubdata-swiper', {
    // Enable debug mode
    on: {
        init: function () {
            console.log('Swiper initialized');
        },
        slideChange: function () {
            console.log('Current slide index: ' + this.activeIndex);
        },
        error: function (swiper, error) {
            console.error('Swiper error:', error);
        },
    },
});

// Check swiper instance
console.log('Swiper instance:', swiper);
console.log('Swiper params:', swiper.params);
```

## Advantages Over Other Sliders

- **Performance**: Hardware-accelerated animations and virtual slides
- **Touch Support**: Industry-leading touch and gesture support
- **Effects**: Wide variety of transition effects (cube, coverflow, etc.)
- **Responsive**: Advanced breakpoint system
- **Modular**: Import only the features you need
- **API**: Comprehensive JavaScript API with events
- **Accessibility**: Built-in ARIA support and keyboard navigation
- **Mobile-First**: Designed primarily for mobile devices
- **Framework Support**: Works with React, Vue, Angular, etc.

## Migration Guide

### From Jssor to Swiper

1. Replace Jssor library with Swiper
2. Update HTML structure from Jssor format to Swiper format
3. Convert Jssor configuration to Swiper parameters
4. Update CSS classes from `jssor-*` to `swiper-*`
5. Replace Jssor API calls with Swiper methods

### From Bootstrap to Swiper

1. Replace Bootstrap carousel classes with Swiper classes
2. Convert Bootstrap data attributes to Swiper configuration
3. Update navigation controls from Bootstrap format to Swiper format
4. Adjust CSS from Bootstrap carousel to Swiper styling