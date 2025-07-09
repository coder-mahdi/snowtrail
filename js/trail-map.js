/**
 * Trail Map Interactive Script
 * Handles tooltip display and trail status interactions
 */

document.addEventListener("DOMContentLoaded", () => {
  const shapes = document.querySelectorAll('polygon[data-trail]');
  const tooltip = document.getElementById('tooltip');
  const tooltipName = document.getElementById('trail-tooltip-name');
  const tooltipStatus = document.getElementById('trail-tooltip-status');

  // Get trail data from data attribute
  const mapContainer = document.querySelector('.map-container');
  const trailData = mapContainer ? JSON.parse(mapContainer.dataset.trails || '{}') : {};
  
  // Debug: log the trail data
  console.log('Trail Data:', trailData);
  console.log('Available trails:', Object.keys(trailData));

  const showTooltip = (e, shape, trail) => {
    const status = trailData[trail];
    tooltipName.textContent = trail;

    tooltipStatus.innerHTML = status === 'Open'
      ? `<span class="open">✅ Open</span>`
      : `<span class="closed">❌ Closed</span>`;

    // Get the map container
    const containerRect = mapContainer.getBoundingClientRect();
    
    // Get the shape position relative to viewport
    const shapeRect = shape.getBoundingClientRect();
    
    // Calculate position relative to the map container
    const centerX = shapeRect.left + shapeRect.width / 2 - containerRect.left;
    const tooltipY = shapeRect.top - containerRect.top - 80; // 80px above the shape
    
    // Position tooltip
    tooltip.style.left = `${centerX}px`;
    tooltip.style.top = `${tooltipY}px`;
    tooltip.style.transform = 'translateX(-50%)';
    tooltip.style.display = 'block';
    
    // Debug: log the positions
    console.log('Trail:', trail);
    console.log('Status:', status);
    console.log('Container bounds:', containerRect);
    console.log('Shape bounds:', shapeRect);
    console.log('Tooltip position:', { left: centerX, top: tooltipY });
  };

  const hideTooltip = () => {
    tooltip.style.display = 'none';
  };

  shapes.forEach(shape => {
    const trail = shape.dataset.trail;
    const status = trailData[trail];

    console.log('Processing shape for trail:', trail, 'Status:', status);

    if (status) {
      shape.classList.add(status.toLowerCase());
    }

    shape.addEventListener('mouseover', (e) => showTooltip(e, shape, trail));
    shape.addEventListener('mouseout', hideTooltip);
    shape.addEventListener('click', (e) => {
      showTooltip(e, shape, trail);
      setTimeout(hideTooltip, 3000);
    });
  });
}); 