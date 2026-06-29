/**
 * VITRINO Premium Preloader
 * ES6, No Libraries, High Performance
 */

class VitrinoPreloader {
    constructor() {
        this.container = document.getElementById('vitrino-preloader');
        
        if (!this.container) return;
        
        this.textElements = this.container.querySelectorAll('.vitrino-preloader-text');
        this.config = window.vitrinoPreloaderConfig || {
            minDisplayTime: 1800,
            fadeOutDuration: 500
        };
        
        this.startTime = performance.now();
        this.isLoaded = false;
        this.isReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        
        this.init();
    }
    
    init() {
        if (!this.isReducedMotion && this.textElements.length > 0) {
            this.startTextAnimation();
        } else if (this.isReducedMotion && this.textElements.length > 0) {
            // Show the last text immediately if reduced motion
            this.textElements[this.textElements.length - 1].classList.add('active');
        }
        
        // Listen for window load
        window.addEventListener('load', () => {
            this.isLoaded = true;
            this.checkAndClose();
        });
        
        // Fallback in case window load never fires or takes too long, 
        // we'll still enforce the minimum time before we check
        setTimeout(() => {
            // If already loaded by event, it's handled. 
            // If not, we wait for the event.
            if (this.isLoaded) this.checkAndClose();
        }, this.config.minDisplayTime);
    }
    
    startTextAnimation() {
        const totalDuration = 1600; // Match progress bar fill time
        const interval = totalDuration / this.textElements.length;
        let currentIndex = 0;
        
        const showNextText = () => {
            if (currentIndex >= this.textElements.length) return;
            
            // Hide all
            this.textElements.forEach(el => el.classList.remove('active'));
            
            // Show current
            this.textElements[currentIndex].classList.add('active');
            
            currentIndex++;
            
            if (currentIndex < this.textElements.length) {
                setTimeout(showNextText, interval);
            }
        };
        
        // Start immediately
        showNextText();
    }
    
    checkAndClose() {
        const elapsed = performance.now() - this.startTime;
        const remaining = Math.max(0, this.config.minDisplayTime - elapsed);
        
        if (remaining > 0) {
            setTimeout(() => this.close(), remaining);
        } else {
            // We use requestAnimationFrame for smooth execution
            requestAnimationFrame(() => this.close());
        }
    }
    
    close() {
        if (this.isReducedMotion) {
            this.cleanup();
            return;
        }
        
        // Preloader Outro Animation
        const logoImg = this.container.querySelector('.vitrino-preloader-logo img');
        
        requestAnimationFrame(() => {
            // Fade out overlay
            this.container.style.opacity = '0';
            
            // Slight scale out logo
            if (logoImg) {
                logoImg.style.transform = 'scale(1.05)';
                logoImg.style.transition = `transform ${this.config.fadeOutDuration}ms ease-out`;
            }
            
            // Remove from DOM after transition
            setTimeout(() => {
                this.cleanup();
            }, this.config.fadeOutDuration);
        });
    }
    
    cleanup() {
        if (this.container && this.container.parentNode) {
            this.container.parentNode.removeChild(this.container);
        }
        // Fade in main site content can be handled globally or it's just visible now.
        // Assuming body is already rendered underneath the fixed preloader.
    }
}

// Initialize immediately to capture DOM
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => new VitrinoPreloader());
} else {
    new VitrinoPreloader();
}
