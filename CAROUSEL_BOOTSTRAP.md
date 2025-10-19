# Bootstrap Carousel Documentation

## Overview

The Bootstrap carousel implementation for the clubdata TYPO3 extension provides a modern, responsive image slider using Bootstrap 5's carousel component. It displays event/program data with images in a mobile-first, accessible carousel with built-in navigation controls and responsive behavior.

## Architecture

### Core Components

- **Template**: `Resources/Private/Templates/Club/CarouselBootstrap.html`
- **Partial**: `Resources/Private/Partials/Club/CarouselBootstrapItem.html`
- **JavaScript**: `Resources/Public/Js/carousel-bootstrap.js`
- **CSS**: `Resources/Public/Css/carousel-bootstrap.css`
- **Framework**: Bootstrap 5.x (carousel component)

### Controller Action

The Bootstrap carousel uses the same controller action as other carousels:
- `ClubController::carouselAction()` filters programs with images
- Respects date filtering and `greaternow` settings
- Passes programs to the Bootstrap carousel template

## Configuration

### TypoScript Settings

```typoscript
plugin.tx_clubdata.settings.carousel.bootstrap {
    # Carousel Behavior
    interval = 5000                 # Auto-advance interval in milliseconds (0 = no auto-advance)
    keyboard = 1                    # Enable keyboard navigation
    pause = hover                   # Pause on hover ("hover" or "false")
    ride = false                    # Auto-start carousel ("carousel" or "false")
    wrap = 1                        # Enable continuous loop
    touch = 1                       # Enable touch/swipe gestures
    
    # Carousel Controls
    indicators = 1                  # Show slide indicators
    controls = 1                    # Show prev/next controls
    
    # Image Settings
    media {
        image {
            maxHeight = 500
            cropVariant = bootstrapSlider
        }
    }
    
    # Display Settings
    showCaptions = 1               # Show slide captions
    fadeTransition = 0             # Use fade instead of slide transition
    datetimeFormat = %d.%m.%Y
}
```

### HTML Data Attributes

Bootstrap carousel supports configuration via data attributes:

```html
<div id="clubdata-bootstrap-carousel" 
     class="carousel slide"
     data-bs-ride="carousel"
     data-bs-interval="5000"
     data-bs-keyboard="true"
     data-bs-pause="hover"
     data-bs-wrap="true"
     data-bs-touch="true">
```

## HTML Structure

### Basic Structure

```html
<div class="tx-clubdata-carousel-bootstrap">
    <div id="clubdata-bootstrap-carousel" class="carousel slide" data-bs-ride="carousel">
        
        <!-- Indicators (if enabled) -->
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#clubdata-bootstrap-carousel" 
                    data-bs-slide-to="0" class="active" aria-current="true"></button>
            <button type="button" data-bs-target="#clubdata-bootstrap-carousel" 
                    data-bs-slide-to="1"></button>
        </div>

        <!-- Slides -->
        <div class="carousel-inner">
            <!-- Individual Slide -->
            <div class="carousel-item active">
                <img src="image.jpg" class="d-block w-100" alt="Event Image">
                <div class="carousel-caption d-none d-md-block">
                    <div class="caption-content">
                        <span class="event-date">19.10.2024</span>
                        <h5 class="event-title">Event Title</h5>
                        <p class="event-subtitle">Event Subtitle</p>
                        <a href="/detail/123" class="btn btn-primary">Details</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Controls (if enabled) -->
        <button class="carousel-control-prev" type="button" 
                data-bs-target="#clubdata-bootstrap-carousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" 
                data-bs-target="#clubdata-bootstrap-carousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>
```

## Bootstrap Carousel Features

### Transition Effects

```typoscript
# Slide transition (default)
fadeTransition = 0

# Fade transition
fadeTransition = 1
```

With fade transition, add `carousel-fade` class:

```html
<div class="carousel slide carousel-fade" data-bs-ride="carousel">
```

### Auto-Advance Configuration

```typoscript
# Auto-advance every 5 seconds
interval = 5000

# Disable auto-advance
interval = 0
```

### Touch/Swipe Support

Bootstrap 5 includes built-in touch support:

```typoscript
# Enable touch gestures (default)
touch = 1

# Disable touch gestures
touch = 0
```

### Accessibility Features

- **ARIA Labels**: Proper labeling for screen readers
- **Keyboard Navigation**: Arrow keys and tab navigation
- **Focus Management**: Proper focus indicators
- **Semantic HTML**: Uses proper button and navigation elements

## Styling and Customization

### CSS Custom Properties

```css
.tx-clubdata-carousel-bootstrap {
    --carousel-height: 500px;
    --caption-bg: rgba(0, 0, 0, 0.7);
    --indicator-size: 12px;
    --control-size: 48px;
}

.carousel-item {
    height: var(--carousel-height);
}

.carousel-item img {
    height: 100%;
    object-fit: cover;
}
```

### Custom Caption Styling

```css
.carousel-caption .caption-content {
    background: var(--caption-bg);
    padding: 1.5rem;
    border-radius: 0.5rem;
    backdrop-filter: blur(4px);
}

.carousel-caption .event-date {
    font-size: 0.875rem;
    opacity: 0.9;
    display: block;
    margin-bottom: 0.5rem;
}

.carousel-caption .event-title {
    font-weight: 600;
    margin-bottom: 0.5rem;
}
```

### Responsive Captions

```css
/* Hide captions on small screens */
@media (max-width: 767.98px) {
    .carousel-caption {
        display: none !important;
    }
}

/* Alternative: Simplified captions on mobile */
@media (max-width: 767.98px) {
    .carousel-caption {
        position: static;
        background: var(--caption-bg);
        padding: 1rem;
    }
    
    .carousel-caption .event-subtitle,
    .carousel-caption .btn {
        display: none;
    }
}
```

## JavaScript Integration

### Basic Initialization

```javascript
// Initialize Bootstrap carousel
const carousel = new bootstrap.Carousel('#clubdata-bootstrap-carousel', {
    interval: 5000,
    keyboard: true,
    pause: 'hover',
    ride: false,
    wrap: true,
    touch: true
});
```

### Event Handling

```javascript
// Listen to carousel events
const carouselElement = document.getElementById('clubdata-bootstrap-carousel');

carouselElement.addEventListener('slide.bs.carousel', event => {
    console.log('Sliding to:', event.to);
});

carouselElement.addEventListener('slid.bs.carousel', event => {
    console.log('Slid to:', event.to);
});
```

### Custom Controls

```javascript
// Custom carousel controls
document.getElementById('custom-prev').addEventListener('click', () => {
    carousel.prev();
});

document.getElementById('custom-next').addEventListener('click', () => {
    carousel.next();
});

document.getElementById('pause-play').addEventListener('click', () => {
    if (carousel._config.interval) {
        carousel.pause();
    } else {
        carousel.cycle();
    }
});
```

## Asset Management

### TYPO3 Asset Integration

```html
<!-- Bootstrap CSS (if not already included) -->
<f:asset.css identifier="bootstrap-css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />

<!-- Custom carousel styles -->
<f:asset.css identifier="carousel-bootstrap-css" href="EXT:clubdata/Resources/Public/Css/carousel-bootstrap.css" />

<!-- Bootstrap JavaScript (if not already included) -->
<f:asset.script identifier="bootstrap-js" src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" />

<!-- Custom carousel JavaScript -->
<f:asset.script identifier="carousel-bootstrap-js" src="EXT:clubdata/Resources/Public/Js/carousel-bootstrap.js" />
```

## Image Processing

### Responsive Images

```html
<f:image class="d-block w-100"
         src="{image.uid}"
         treatIdAsReference="1"
         height="{settings.carousel.bootstrap.media.image.maxHeight}"
         cropVariant="{settings.carousel.bootstrap.media.image.cropVariant}"
         additionalAttributes="{alt: '{program.title}'}" />
```

### Multiple Image Sizes

```html
<!-- For responsive images with srcset -->
<f:image class="d-block w-100"
         src="{image.uid}"
         treatIdAsReference="1"
         cropVariant="bootstrapSlider"
         additionalAttributes="{
             alt: '{program.title}',
             srcset: '{f:uri.image(src: image.uid, treatIdAsReference: 1, width: 576, cropVariant: \'bootstrapSlider\')} 576w,
                     {f:uri.image(src: image.uid, treatIdAsReference: 1, width: 768, cropVariant: \'bootstrapSlider\')} 768w,
                     {f:uri.image(src: image.uid, treatIdAsReference: 1, width: 992, cropVariant: \'bootstrapSlider\')} 992w,
                     {f:uri.image(src: image.uid, treatIdAsReference: 1, width: 1200, cropVariant: \'bootstrapSlider\')} 1200w',
             sizes: '100vw'
         }" />
```

## Performance Optimization

### Lazy Loading (Bootstrap 5.2+)

```html
<img src="placeholder.jpg" 
     data-bs-src="actual-image.jpg" 
     class="d-block w-100 lazy" 
     loading="lazy" 
     alt="Event Image">
```

### Preloading Critical Images

```html
<f:asset.css identifier="preload-first-image">
<link rel="preload" as="image" href="{f:uri.image(src: firstImage.uid, treatIdAsReference: 1, cropVariant: 'bootstrapSlider')}">
</f:asset.css>
```

## Browser Compatibility

- **Bootstrap 5**: Requires modern browsers (IE11+ with polyfills)
- **Touch Support**: Native on all modern mobile browsers
- **Transitions**: CSS transitions with JavaScript fallbacks
- **Accessibility**: WCAG 2.1 compliant

## Troubleshooting

### Common Issues

1. **Carousel Not Auto-Playing**: Check `data-bs-ride="carousel"` attribute
2. **Touch Not Working**: Ensure Bootstrap 5+ and `data-bs-touch="true"`
3. **Images Not Responsive**: Add `w-100` class to images
4. **Caption Positioning**: Check Bootstrap grid and responsive utilities

### Debug Tips

```javascript
// Check carousel instance
const carouselElement = document.getElementById('clubdata-bootstrap-carousel');
const carousel = bootstrap.Carousel.getInstance(carouselElement);
console.log('Carousel config:', carousel._config);

// Monitor events
['slide.bs.carousel', 'slid.bs.carousel'].forEach(eventType => {
    carouselElement.addEventListener(eventType, event => {
        console.log(`${eventType}:`, event.direction, event.from, event.to);
    });
});
```

## Advantages Over Other Sliders

- **Framework Integration**: Part of Bootstrap ecosystem
- **Accessibility**: Built-in ARIA support and keyboard navigation
- **Touch Support**: Native mobile-friendly interactions
- **Lightweight**: No additional libraries beyond Bootstrap
- **Responsive**: Mobile-first responsive design
- **Customizable**: Easy styling with Bootstrap utilities
- **Semantic HTML**: Clean, accessible markup structure

## Migration from Jssor Carousel

1. Update template references from `Carousel.html` to `CarouselBootstrap.html`
2. Adjust TypoScript settings from `jssor` namespace to `bootstrap`
3. Replace Jssor-specific CSS classes with Bootstrap classes
4. Update JavaScript initialization if custom controls are needed