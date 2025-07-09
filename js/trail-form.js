/**
 * Trail Form Mobile Enhancement
 * Improves form submission on mobile devices
 */

document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('trail-status-form');
  const submitBtn = document.getElementById('submit-btn');
  
  // Check if form exists on this page
  if (!form || !submitBtn) {
    return;
  }
  
  // Add touch event handling for mobile
  submitBtn.addEventListener('touchstart', function(e) {
    e.preventDefault();
    this.style.transform = 'scale(0.95)';
  }, { passive: false });
  
  submitBtn.addEventListener('touchend', function(e) {
    e.preventDefault();
    this.style.transform = '';
    form.submit();
  }, { passive: false });
  
  // Regular click event for desktop
  submitBtn.addEventListener('click', function(e) {
    if (!('ontouchstart' in window)) {
      // Only handle click on non-touch devices
      return;
    }
    e.preventDefault();
  });
  
  // Form submission handler
  form.addEventListener('submit', function(e) {
    submitBtn.disabled = true;
    submitBtn.textContent = 'Saving...';
    submitBtn.classList.add('loading');
  });
  
  // Debug: Log form data on mobile
  if ('ontouchstart' in window) {
    console.log('Mobile device detected');
    form.addEventListener('submit', function(e) {
      const formData = new FormData(form);
      console.log('Form submitted with data:', Object.fromEntries(formData));
    });
  }
}); 