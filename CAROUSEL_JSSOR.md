# Clubdata Carousel Documentation

## Overview

The clubdata carousel is a TYPO3 13-compatible image slider component built on the Jssor Slider library. It displays event/program data with images in a responsive, touch-enabled carousel with configurable dimensions, navigation controls, and responsive behavior.

The carousel uses **data attributes for configuration** and **auto-initializes** on page load - no JavaScript code is required in templates.

## Architecture

### Core Components

- **Template**: `Resources/Private/Templates/Club/Carousel.html`
- **Partial**: `Resources/Private/Partials/Club/CarouselItem.html`
- **JavaScript**: `Resources/Public/Js/carousel.js`
- **CSS**: `Resources/Public/Css/carousel.css`
- **Library**: `Resources/Public/Js/jssor.slider.min.js` (v28.0.0)

### Controller Action

The carousel is handled by `ClubController::carouselAction()` which:
- Filters programs to only show those with images (`$filter['image'] = 1`)
- Respects date filtering and `greaternow` settings
- Passes programs to the Carousel template

## Configuration

### Data Attributes Configuration

The carousel is configured entirely through HTML data attributes - no inline JavaScript required:

```html
<div id="jssor_1" class="clubdata-slider"
     data-carousel-auto-play="0"
     data-carousel-auto-scaling="0"
     data-carousel-container-width="980"
     data-carousel-slide-width="720"
     data-carousel-slide-height="500"
     data-carousel-arrow-width="265"
     data-carousel-arrow-right-position="715"
     data-carousel-min-slide-height="200">
```

### TypoScript Settings

The TYPO3 settings are passed to the template and converted to data attributes:

```typoscript
plugin.tx_clubdata.settings.carousel {
    datetimeFormat = %d.%M.%Y
    media {
        image {
            maxHeight = 425
        }
    }
    slider {
        arrows = 1                    # Show/hide arrow navigation
        bullets = 1                   # Show/hide bullet navigation
        autoPlay = 0                  # Auto-play slides (0=off, 1=on)
        autoScaling = 0               # Scaling mode (0=manual, 1=jssor auto)
        slideWidth = 720              # Individual slide width in pixels
        slideHeight = 500             # Slide height in pixels
        containerWidth = 980          # Overall slider container width
        arrowWidth = 265              # Arrow button width
        arrowRightPosition = 715      # Right arrow left position
        minSlideHeight = 200          # Minimum slide height (prevents collapse)
    }
}
```

### Data Attribute Mapping

| TypoScript Setting | Data Attribute | Type |
|-------------------|----------------|------|
| `autoPlay` | `data-carousel-auto-play` | Boolean (0/1) |
| `autoScaling` | `data-carousel-auto-scaling` | Number (0/1) |
| `containerWidth` | `data-carousel-container-width` | Number (pixels) |
| `slideWidth` | `data-carousel-slide-width` | Number (pixels) |
| `slideHeight` | `data-carousel-slide-height` | Number (pixels) |
| `arrowWidth` | `data-carousel-arrow-width` | Number (pixels) |
| `arrowRightPosition` | `data-carousel-arrow-right-position` | Number (pixels) |
| `minSlideHeight` | `data-carousel-min-slide-height` | Number (pixels) |

### CSS Custom Properties

The carousel automatically sets CSS custom properties for responsive behavior:

```css
.clubdata-slider {
    --container-width: 980px;        /* from data-carousel-container-width */
    --slide-width: 720px;            /* from data-carousel-slide-width */
    --arrow-width: 265px;            /* from data-carousel-arrow-width */
    --arrow-right-position: 715px;   /* from data-carousel-arrow-right-position */
    --min-slide-height: 200px;       /* from data-carousel-min-slide-height */
}
```

## Scaling Modes

### Manual Scaling (autoScaling: 0) - Default

- **Width**: Scales responsively based on container width
- **Height**: Fixed to `slideHeight` value (e.g., 500px)
- **Transform**: Disables Jssor's `transform: scale()` to prevent unwanted scaling
- **Best for**: Content layouts requiring precise height control

### Auto Scaling (autoScaling: 1)

- **Width**: Uses Jssor's built-in transform scaling
- **Height**: Scales proportionally with width
- **Transform**: Allows `transform: scale()` on the entire slider
- **Best for**: Full-screen/hero sliders

## Slide Width vs Container Width

The carousel supports the popular UX pattern where adjacent slides are partially visible:

- **Container Width**: Overall slider container dimensions (980px default)
- **Slide Width**: Individual slide dimensions (720px default)
- **Result**: When slide width < container width, adjacent slides "peek" through

### Example Configurations

```typoscript
# Traditional full-width slides
containerWidth = 980
slideWidth = 980     # Same as container = no peek effect

# Subtle peek effect
containerWidth = 980
slideWidth = 850     # 130px difference = 65px peek each side

# Prominent peek effect
containerWidth = 980
slideWidth = 720     # 260px difference = 130px peek each side
```

## HTML Structure

```html
<div class="tx-clubdata-carousel">
    <div id="jssor_1" class="clubdata-slider"
         data-carousel-auto-play="0"
         data-carousel-auto-scaling="0"
         data-carousel-container-width="980"
         data-carousel-slide-width="720"
         data-carousel-slide-height="500"
         data-carousel-arrow-width="265"
         data-carousel-arrow-right-position="715"
         data-carousel-min-slide-height="200">

        <!-- Loading Screen -->
        <div data-u="loading" class="jssorl-009-spin">
            <img src="spin.svg" />
        </div>

        <!-- Slides Container -->
        <div class="slides" data-u="slides">
            <!-- Individual Slide -->
            <div class="image-wrap">
                <img class="slider-image" data-u="image" />
                <a href="/detail/123">
                    <div class="slider-caption-wrap">
                        <div class="slider-caption">
                            <span class="slider-date">19.10.2024</span>
                            <span class="slider-title">Event Title</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Navigation (if enabled) -->
        <div data-u="navigator" class="jssorb051"><!-- Bullets --></div>
        <div data-u="arrowleft" class="jssora051 arrowleft"><!-- Left Arrow --></div>
        <div data-u="arrowright" class="jssora051 arrowright"><!-- Right Arrow --></div>
    </div>
</div>
```

## JavaScript API

### Auto-Initialization

The carousel **automatically initializes** on page load - no JavaScript code is needed in templates:

```javascript
// Automatically runs on page load
$('.clubdata-slider').each(function() {
    const settings = parseDataAttributes(this);
    initClubdataCarousel(settings);
});
```

### Advanced Manual Initialization

For custom implementations, you can manually initialize carousels:

```javascript
// Initialize a specific carousel programmatically
initClubdataCarousel({
    sliderId: 'my_carousel',
    slideWidth: 800,
    slideHeight: 400,
    selectors: {
        contentMain: '#hero-section'
    }
});

// Re-initialize all carousels (useful for dynamic content)
autoInitializeCarousels();
```

### Custom Selectors

You can override default selectors via data attributes or settings:

```html
<!-- Via JSON data attribute -->
<div class="clubdata-slider"
     data-carousel-selectors='{"contentMain": "#hero", "arrowRight": ".next-btn"}'>

<!-- Via JavaScript -->
initClubdataCarousel({
    selectors: {
        contentMain: '#main-content',
        arrowRight: '.custom-arrow-right'
    }
});
```

### Key JavaScript Features

1. **Auto-Discovery**: Finds and initializes all `.clubdata-slider` elements
2. **Data Attribute Parsing**: Converts HTML data attributes to JavaScript settings
3. **Error Handling**: Validates dependencies, DOM elements, and settings
4. **Height Management**: Forces consistent slide heights and prevents collapse
5. **Responsive Scaling**: Maintains proportions across screen sizes
6. **Multi-Instance Support**: Handles multiple carousels on the same page
7. **Transform Control**: Manages Jssor's scaling based on `autoScaling` setting
8. **Image Loading**: Waits for images to load before final adjustments

### Console Logging

The carousel provides detailed console output for debugging:

```javascript
// Auto-initialization logging
Auto-initializing carousel #jssor_1 with settings: {
    slideWidth: 720, slideHeight: 500, autoScaling: 0
}

// Configuration logging
Creating Jssor slider with dimensions: {
    containerWidth: 980, slideWidth: 720, height: 500,
    autoScaling: 0, arrowWidth: 265, arrowRightPosition: 715,
    sliderId: "jssor_1", customSelectors: "{...}"
}

// Scaling behavior
AutoScaling disabled - will use manual width scaling and remove transforms
Manual scaled slider - container: 800px, slides: 571px

// Height management
Set slider height to: 500px
Fixed 3 zero-height elements
Fixed heights after all images loaded
```

## CSS Features

### Image Handling

```css
.clubdata-slider .slider-image {
    width: 100%;
    height: 100%;
    object-fit: cover;    /* Maintains aspect ratio while filling */
    display: block;
}
```

### Caption Overlay

```css
.clubdata-slider .slider-caption-wrap {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.7));
    padding: 20px;
}
```

### Responsive Arrows

```css
.clubdata-slider .jssora051.arrowright {
    width: var(--arrow-width, 265px);
    left: var(--arrow-right-position, 715px);
}
```

## Asset Management

The template uses TYPO3's modern asset management system:

```html
<f:asset.script identifier="jssor-slider" src="EXT:clubdata/Resources/Public/Js/jssor.slider.min.js" />
<f:asset.script identifier="carousel-js" src="EXT:clubdata/Resources/Public/Js/carousel.js" />
<f:asset.css identifier="carousel-css" href="EXT:clubdata/Resources/Public/Css/carousel.css" />
```

## Image Processing

### Carousel Item Configuration

```html
<f:image class="slider-image"
         additionalAttributes="{data-u: 'image'}"
         src="{image.uid}"
         treatIdAsReference="1"
         height="{settings.carousel.image.maxHeight}"
         cropVariant="specialSlider" />
```

### Image Settings

- **Max Height**: 425px (configurable via `settings.carousel.image.maxHeight`)
- **Crop Variant**: `specialSlider` (defined in TCA)
- **Object Fit**: `cover` (maintains aspect ratio, fills space)

## Responsive Behavior

### Breakpoint Handling

The carousel automatically scales based on:
1. Parent container width (`#content_main`)
2. Available viewport width
3. Configured container and slide widths

### Width Calculation

```javascript
// Responsive scaling maintains aspect ratios
slideWidthRatio = slideWidth / containerWidth;
scaledSlideWidth = newContainerWidth * slideWidthRatio;
```

### Event Handling

```javascript
$(window).bind("load", ScaleSlider);
$(window).bind("resize", ScaleSlider);
$(window).bind("orientationchange", ScaleSlider);
```

## Browser Compatibility

- **Modern Browsers**: Full support with CSS custom properties
- **Jssor Library**: Version 28.0.0 fixes `currentStyle` errors in older browsers
- **Touch Support**: Native Jssor touch/swipe functionality
- **Performance**: Lazy loading enabled (`$Lazy: 1`)

## Troubleshooting

### Common Issues

1. **Zero Height Elements**: JavaScript automatically detects and fixes
2. **Transform Conflicts**: Manual scaling mode disables unwanted transforms
3. **Image Loading**: Height fixes run after images load
4. **Missing Dependencies**: Console errors for missing jQuery/Jssor

### Debug Information

Enable detailed logging by checking browser console for:
- Configuration values
- Element dimensions
- Scaling behavior
- Error messages

## Performance Considerations

- **Lazy Loading**: Images load as needed
- **CSS Custom Properties**: Efficient responsive updates
- **Asset Caching**: TYPO3 asset management enables caching
- **Transform Optimization**: Manual scaling prevents unnecessary repaints

## Migration Notes

This documentation reflects the current state with modern data-attribute configuration:
- ✅ **Data Attribute Configuration**: No inline JavaScript required
- ✅ **Auto-Initialization**: Carousels initialize automatically on page load
- ✅ **Multi-Instance Support**: Multiple carousels per page work seamlessly
- ✅ **CSP Compliance**: Works with strict Content Security Policies
- ✅ **Configurable Selectors**: Custom CSS selectors via data attributes
- ✅ **Modern JavaScript**: ES6+ features, modular structure, IIFE wrapper
- ✅ **TYPO3 13 Compatible**: Uses modern asset management system
- ✅ **Jssor Slider v28.0.0**: Latest stable version with bug fixes
- ✅ **Comprehensive Error Handling**: Robust validation and debugging
- ✅ **Responsive Design**: Container vs slide width support for peek effects

The carousel follows modern web development best practices and is production-ready with zero configuration overhead.
