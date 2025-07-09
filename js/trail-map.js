/**
 * Trail Map Interactive Script
 * Handles tooltip display and trail status interactions
 */

document.addEventListener("DOMContentLoaded", () => {
  const shapes = document.querySelectorAll('polygon[data-trail]');
  const tooltip = document.getElementById('tooltip');
  const tooltipName = document.getElementById('trail-tooltip-name');
  const tooltipStatus = document.getElementById('trail-tooltip-status');
  const trailCards = document.querySelectorAll('.trail-status li');

  // Get trail data from data attribute
  const mapContainer = document.querySelector('.map-container');
  const trailData = mapContainer ? JSON.parse(mapContainer.dataset.trails || '{}') : {};
  
  // Create a map of trail names to shapes and cards
  const trailMap = new Map();
  
  shapes.forEach(shape => {
    const trail = shape.dataset.trail;
    if (trail) {
      trailMap.set(trail, { shape });
    }
  });
  
  trailCards.forEach(card => {
    const trailName = card.querySelector('strong').textContent;
    if (trailMap.has(trailName)) {
      trailMap.get(trailName).card = card;
    }
  });

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
    
  };

  const hideTooltip = () => {
    tooltip.style.display = 'none';
  };

  // Function to highlight trail on map
  const highlightTrailOnMap = (trailName) => {
    const trailInfo = trailMap.get(trailName);
    if (trailInfo && trailInfo.shape) {
      trailInfo.shape.style.fill = 'rgba(255, 255, 255, 0.4)';
      trailInfo.shape.style.stroke = '#ffffff';
      trailInfo.shape.style.strokeWidth = '2';
      trailInfo.shape.style.filter = 'drop-shadow(0 0 6px rgba(255, 255, 255, 0.6))';
      trailInfo.shape.setAttribute('data-card-hover', 'true');
    }
  };

  // Function to remove highlight from trail on map
  const removeTrailHighlight = (trailName) => {
    const trailInfo = trailMap.get(trailName);
    if (trailInfo && trailInfo.shape) {
      trailInfo.shape.style.fill = 'transparent';
      trailInfo.shape.style.stroke = 'transparent';
      trailInfo.shape.style.strokeWidth = '0';
      trailInfo.shape.style.filter = 'none';
      trailInfo.shape.removeAttribute('data-card-hover');
    }
  };

  // Function to highlight trail card
  const highlightTrailCard = (trailName) => {
    const trailInfo = trailMap.get(trailName);
    if (trailInfo && trailInfo.card) {
      trailInfo.card.style.transform = 'translateY(-2px)';
      trailInfo.card.style.boxShadow = trailInfo.card.classList.contains('open') 
        ? '0 4px 8px rgba(40, 167, 69, 0.2)' 
        : '0 4px 8px rgba(220, 53, 69, 0.2)';
    }
  };

  // Function to remove highlight from trail card
  const removeTrailCardHighlight = (trailName) => {
    const trailInfo = trailMap.get(trailName);
    if (trailInfo && trailInfo.card) {
      trailInfo.card.style.transform = 'translateY(0)';
      trailInfo.card.style.boxShadow = 'none';
    }
  };

  // Add event listeners to shapes
  shapes.forEach(shape => {
    const trail = shape.dataset.trail;
    const status = trailData[trail];

    if (status) {
      shape.classList.add(status.toLowerCase());
    }

    shape.addEventListener('mouseover', (e) => {
      // Only show tooltip and highlight card if not already highlighted by card hover
      if (!shape.hasAttribute('data-card-hover')) {
        showTooltip(e, shape, trail);
        highlightTrailCard(trail);
      }
    });
    
    shape.addEventListener('mouseout', () => {
      // Only hide tooltip and remove card highlight if not highlighted by card hover
      if (!shape.hasAttribute('data-card-hover')) {
        hideTooltip();
        removeTrailCardHighlight(trail);
      }
    });
    
    shape.addEventListener('click', (e) => {
      showTooltip(e, shape, trail);
      setTimeout(hideTooltip, 3000);
    });
  });

  // Add event listeners to trail cards
  trailCards.forEach(card => {
    const trailName = card.querySelector('strong').textContent;
    const trailInfo = trailMap.get(trailName);
    
    if (trailInfo && trailInfo.shape) {
      card.addEventListener('mouseenter', () => {
        highlightTrailOnMap(trailName);
        showTooltip(null, trailInfo.shape, trailName);
      });
      
      card.addEventListener('mouseleave', () => {
        removeTrailHighlight(trailName);
        hideTooltip();
      });
    }
  });
}); 