/**
 * Custom Natal Chart Renderer - 100% self-drawn with Canvas API
 * No external charting libraries - everything calculated and rendered manually
 */

// Import astrology calculations
import { Origin, Horoscope } from 'circular-natal-horoscope-js/dist/index.js';

// Zodiac signs with their symbols, names and element
const ZODIAC_SIGNS = [
    { symbol: '♈', name: 'Aries', element: 'fire', modality: 'cardinal' },
    { symbol: '♉', name: 'Taurus', element: 'earth', modality: 'fixed' },
    { symbol: '♊', name: 'Gemini', element: 'air', modality: 'mutable' },
    { symbol: '♋', name: 'Cancer', element: 'water', modality: 'cardinal' },
    { symbol: '♌', name: 'Leo', element: 'fire', modality: 'fixed' },
    { symbol: '♍', name: 'Virgo', element: 'earth', modality: 'mutable' },
    { symbol: '♎', name: 'Libra', element: 'air', modality: 'cardinal' },
    { symbol: '♏', name: 'Scorpio', element: 'water', modality: 'fixed' },
    { symbol: '♐', name: 'Sagittarius', element: 'fire', modality: 'mutable' },
    { symbol: '♑', name: 'Capricorn', element: 'earth', modality: 'cardinal' },
    { symbol: '♒', name: 'Aquarius', element: 'air', modality: 'fixed' },
    { symbol: '♓', name: 'Pisces', element: 'water', modality: 'mutable' }
];

// Planet symbols
const PLANET_SYMBOLS = {
    'Sun': '☉', 'Moon': '☽', 'Mercury': '☿', 'Venus': '♀', 'Mars': '♂',
    'Jupiter': '♃', 'Saturn': '♄', 'Uranus': '♅', 'Neptune': '♆', 'Pluto': '♇',
    'NNode': '☊', 'SNode': '☋', 'Lilith': '⚸', 'Chiron': '⚷'
};

// Planet colors
const PLANET_COLORS = {
    'Sun': '#FFD700', 'Moon': '#C0C0C0', 'Mercury': '#B5B5B5', 'Venus': '#FFB6C1',
    'Mars': '#FF4500', 'Jupiter': '#DEB887', 'Saturn': '#F4A460',
    'Uranus': '#00CED1', 'Neptune': '#4169E1', 'Pluto': '#8B4513',
    'NNode': '#9370DB', 'SNode': '#708090', 'Lilith': '#4B0082', 'Chiron': '#DAA520'
};

// Aspect colors and orbs
const ASPECT_TYPES = {
    'conjunction': { orb: 10, color: '#FFD700', symbol: '☌' },
    'opposition': { orb: 10, color: '#FF6347', symbol: '⚹' },
    'square': { orb: 8, color: '#FF4500', symbol: '□' },
    'trine': { orb: 8, color: '#32CD32', symbol: '△' },
    'sextile': { orb: 6, color: '#4169E1', symbol: '✶' },
    'quincunx': { orb: 3, color: '#9370DB', symbol: '⚻' },
    'semisextile': { orb: 2, color: '#20B2AA', symbol: '⚺' },
    'semisquare': { orb: 2, color: '#CD853F', symbol: '∠' },
    'sesquiquadrate': { orb: 2, color: '#8B4513', symbol: 'Ↄ' }
};

/**
 * Main Natal Chart class
 */
export class NatalChart {
    constructor(containerId, options = {}) {
        this.container = document.getElementById(containerId);
        if (!this.container) {
            console.error('Container not found:', containerId);
            return;
        }

        this.options = {
            size: Math.min(this.container.clientWidth || 600, 700),
            responsive: true,
            showAspects: true,
            showHouseCusps: true,
            showSignSymbols: true,
            showPlanetLabels: true,
            showDegrees: true,
            animation: true,
            ...options
        };

        this.canvas = null;
        this.ctx = null;
        this.data = null;
        this.hoveredElement = null;
        this.tooltip = null;

        this.init();
    }

    init() {
        // Create canvas
        this.canvas = document.createElement('canvas');
        this.canvas.style.width = '100%';
        this.canvas.style.height = 'auto';
        this.canvas.style.maxWidth = '700px';
        this.canvas.style.display = 'block';
        this.container.appendChild(this.canvas);

        this.ctx = this.canvas.getContext('2d');

        // Initial sizing
        this._isResizing = false;

        // Handle resize
        if (this.options.responsive) {
            window.addEventListener('resize', () => this.resize());
        }

        // Create tooltip
        this.createTooltip();

        // Mouse events
        this.canvas.addEventListener('mousemove', (e) => this.handleMouseMove(e));
        this.canvas.addEventListener('mouseleave', () => this.hideTooltip());
    }

    resize() {
        if (!this.container) return;

        const rect = this.container.getBoundingClientRect();
        const size = Math.min(rect.width || this.options.size, 700);
        const dpr = window.devicePixelRatio || 1;

        // Prevent infinite loop
        if (this._isResizing) return;
        this._isResizing = true;

        this.canvas.width = size * dpr;
        this.canvas.height = size * dpr;
        this.canvas.style.width = `${size}px`;
        this.canvas.style.height = `${size}px`;

        this.ctx.scale(dpr, dpr);
        this.options.size = size;

        this._isResizing = false;

        // Re-render if we have data
        if (this.data) {
            requestAnimationFrame(() => {
                this.render(this.data);
            });
        }
    }

    drawAll(ctx, size) {
        const center = size / 2;

        // Draw background
        this.drawBackground(ctx, size);

        // Draw zodiac wheel
        this.drawZodiacWheel(ctx, size, center);

        // Draw houses
        this.drawHouses(ctx, size, center);

        // Draw house cusps
        this.drawHouseCusps(ctx, size, center);

        // Draw aspects
        if (this.options.showAspects && this.data.aspects) {
            this.drawAspects(ctx, size, center);
        }

        // Draw planets
        if (this.data.planets) {
            this.drawPlanets(ctx, size, center);
        }

        // Draw center element (Asc/MC labels)
        this.drawCenterLabels(ctx, size, center);
    }

    createTooltip() {
        this.tooltip = document.createElement('div');
        this.tooltip.style.cssText = `
            position: fixed;
            background: rgba(15, 23, 42, 0.95);
            border: 1px solid rgba(99, 102, 241, 0.3);
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            color: #e2e8f0;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.2s;
            z-index: 1000;
            max-width: 250px;
            backdrop-filter: blur(8px);
        `;
        document.body.appendChild(this.tooltip);
    }

    showTooltip(x, y, content) {
        this.tooltip.innerHTML = content;
        this.tooltip.style.left = `${x + 15}px`;
        this.tooltip.style.top = `${y + 15}px`;
        this.tooltip.style.opacity = '1';
    }

    hideTooltip() {
        this.tooltip.style.opacity = '0';
    }

    handleMouseMove(e) {
        const rect = this.canvas.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        const size = this.options.size;
        const center = size / 2;
        const radius = size * 0.45;

        // Check planets
        if (this.data?.planets) {
            for (const [name, planet] of Object.entries(this.data.planets)) {
                const pos = this.getPlanetPosition(planet.position, radius);
                const dist = Math.sqrt((x - pos.x) ** 2 + (y - pos.y) ** 2);
                if (dist < 15) {
                    const sign = ZODIAC_SIGNS[Math.floor(planet.position / 30)];
                    this.showTooltip(e.clientX, e.clientY, `
                        <div style="color: ${PLANET_COLORS[name] || '#d4a855'}">${PLANET_SYMBOLS[name] || name}</div>
                        <div style="font-weight: 600; margin-top: 4px">${name} ${planet.retrograde ? 'R' : ''}</div>
                        <div style="color: #94a3b8; font-size: 12px; margin-top: 2px">
                            ${sign.symbol} ${sign.name} ${Math.floor(planet.position % 30)}°${String(Math.round((planet.position % 30) * 60)).padStart(2, '0')}'
                        </div>
                        <div style="color: #6366f1; font-size: 12px; margin-top: 2px">House ${planet.house || '—'}</div>
                    `);
                    return;
                }
            }
        }

        this.hideTooltip();
    }

    getPlanetPosition(degree, radius) {
        // Convert to radians (0° at AC/9th house cusp, clockwise)
        const radians = (degree - 90) * (Math.PI / 180);
        return {
            x: this.options.size / 2 + Math.cos(radians) * radius,
            y: this.options.size / 2 + Math.sin(radians) * radius
        };
    }

    /**
     * Render the complete natal chart
     */
    render(data) {
        this.data = data;

        const ctx = this.ctx;
        const size = this.options.size;

        // Clear canvas
        ctx.clearRect(0, 0, size, size);

        // Draw everything
        this.drawAll(ctx, size);
    }

    drawBackground(ctx, size) {
        // Main background
        ctx.beginPath();
        ctx.arc(size / 2, size / 2, size * 0.48, 0, Math.PI * 2);
        ctx.fillStyle = '#0d1322';
        ctx.fill();

        // Inner circle
        ctx.beginPath();
        ctx.arc(size / 2, size / 2, size * 0.15, 0, Math.PI * 2);
        ctx.fillStyle = '#0a0e1a';
        ctx.fill();
    }

    drawZodiacWheel(ctx, size, center) {
        const radius = size * 0.45;
        const houseRadius = size * 0.32;

        // Draw sign segments (outer ring)
        for (let i = 0; i < 12; i++) {
            const startAngle = ((i * 30) - 90) * (Math.PI / 180);
            const endAngle = (((i + 1) * 30) - 90) * (Math.PI / 180);

            // Sign background
            ctx.beginPath();
            ctx.arc(center, center, radius, startAngle, endAngle);
            ctx.arc(center, center, size * 0.25, endAngle, startAngle, true);
            ctx.closePath();

            // Element colors
            const elementColors = {
                'fire': 'rgba(239, 68, 68, 0.08)',
                'earth': 'rgba(245, 158, 11, 0.08)',
                'air': 'rgba(59, 130, 246, 0.08)',
                'water': 'rgba(139, 92, 246, 0.08)'
            };
            ctx.fillStyle = elementColors[ZODIAC_SIGNS[i].element];
            ctx.fill();

            // Sign border
            ctx.strokeStyle = 'rgba(99, 102, 241, 0.2)';
            ctx.lineWidth = 0.5;
            ctx.stroke();

            // Sign symbol
            if (this.options.showSignSymbols) {
                const symbolAngle = ((i * 30 + 15) - 90) * (Math.PI / 180);
                const symbolRadius = radius * 0.88;
                const sx = center + Math.cos(symbolAngle) * symbolRadius;
                const sy = center + Math.sin(symbolAngle) * symbolRadius;

                ctx.fillStyle = 'rgba(99, 102, 241, 0.4)';
                ctx.font = `${size * 0.028}px serif`;
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText(ZODIAC_SIGNS[i].symbol, sx, sy);
            }

            // Sign name on outer edge
            if (size > 400) {
                const nameAngle = ((i * 30 + 15) - 90) * (Math.PI / 180);
                const nameRadius = radius * 1.08;
                const nx = center + Math.cos(nameAngle) * nameRadius;
                const ny = center + Math.sin(nameAngle) * nameRadius;

                ctx.fillStyle = 'rgba(148, 163, 184, 0.7)';
                ctx.font = `${size * 0.016}px Inter, sans-serif`;
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText(ZODIAC_SIGNS[i].name.substring(0, 3), nx, ny);
            }
        }

        // Outer ring border
        ctx.beginPath();
        ctx.arc(center, center, radius, 0, Math.PI * 2);
        ctx.strokeStyle = 'rgba(99, 102, 241, 0.4)';
        ctx.lineWidth = 2;
        ctx.stroke();

        // Inner ring border
        ctx.beginPath();
        ctx.arc(center, center, size * 0.25, 0, Math.PI * 2);
        ctx.strokeStyle = 'rgba(99, 102, 241, 0.2)';
        ctx.lineWidth = 1;
        ctx.stroke();
    }

    drawHouses(ctx, size, center) {
        if (!this.data?.houses) return;

        const radius = size * 0.45;
        const innerRadius = size * 0.25;

        // Draw house areas
        for (let i = 0; i < 12; i++) {
            const cusp1 = this.data.houses[i];
            const cusp2 = this.data.houses[(i + 1) % 12];

            const startAngle = (cusp1 - 90) * (Math.PI / 180);
            const endAngle = (cusp2 - 90) * (Math.PI / 180);

            ctx.beginPath();
            ctx.moveTo(
                center + Math.cos(startAngle) * innerRadius,
                center + Math.sin(startAngle) * innerRadius
            );
            ctx.arc(center, center, radius, startAngle, endAngle);
            ctx.arc(center, center, innerRadius, endAngle, startAngle, true);
            ctx.closePath();

            // House number
            ctx.fillStyle = 'rgba(99, 102, 241, 0.03)';
            ctx.fill();

            // House number label
            const midAngle = ((cusp1 + cusp2) / 2 - 90) * (Math.PI / 180);
            const numRadius = (radius + innerRadius) / 2;
            const nx = center + Math.cos(midAngle) * numRadius;
            const ny = center + Math.sin(midAngle) * numRadius;

            ctx.fillStyle = 'rgba(99, 102, 241, 0.25)';
            ctx.font = `bold ${size * 0.025}px Inter, sans-serif`;
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText((i + 1).toString(), nx, ny);
        }
    }

    drawHouseCusps(ctx, size, center) {
        if (!this.data?.houses) return;

        const radius = size * 0.45;
        const innerRadius = size * 0.25;

        for (let i = 0; i < 12; i++) {
            const cusp = this.data.houses[i];
            const angle = (cusp - 90) * (Math.PI / 180);

            // Cusp line
            ctx.beginPath();
            ctx.moveTo(
                center + Math.cos(angle) * innerRadius,
                center + Math.sin(angle) * innerRadius
            );
            ctx.lineTo(
                center + Math.cos(angle) * radius,
                center + Math.sin(angle) * radius
            );

            // Different style for angular houses (1, 4, 7, 10)
            if ([1, 4, 7, 10].includes(i + 1)) {
                ctx.strokeStyle = 'rgba(212, 168, 85, 0.6)';
                ctx.lineWidth = 2;
            } else {
                ctx.strokeStyle = 'rgba(99, 102, 241, 0.3)';
                ctx.lineWidth = 1;
            }
            ctx.stroke();

            // Degree labels on cusps
            if (this.options.showDegrees) {
                const degRadius = radius * 1.02;
                const dx = center + Math.cos(angle) * degRadius;
                const dy = center + Math.sin(angle) * degRadius;

                ctx.fillStyle = 'rgba(212, 168, 85, 0.8)';
                ctx.font = `${size * 0.014}px Inter, monospace`;
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText(`${Math.round(cusp)}°`, dx, dy);
            }
        }
    }

    drawAspects(ctx, size, center) {
        if (!this.data.aspects) return;

        const radius = size * 0.32;

        for (const aspect of this.data.aspects) {
            const p1 = this.data.planets[aspect.point1];
            const p2 = this.data.planets[aspect.point2];

            if (!p1 || !p2) continue;

            const pos1 = this.getPlanetPosition(p1.position, radius);
            const pos2 = this.getPlanetPosition(p2.position, radius);

            ctx.beginPath();
            ctx.moveTo(pos1.x, pos1.y);
            ctx.lineTo(pos2.x, pos2.y);

            const aspectInfo = ASPECT_TYPES[aspect.type];
            if (aspectInfo) {
                ctx.strokeStyle = aspectInfo.color;
                ctx.globalAlpha = Math.max(0.15, 1 - (aspect.orb / aspectInfo.orb));
                ctx.lineWidth = aspect.orb < 1 ? 2 : 1;
                ctx.stroke();
            }
            ctx.globalAlpha = 1;
        }
    }

    drawPlanets(ctx, size, center) {
        if (!this.data.planets) return;

        const radius = size * 0.32;

        // Sort by distance from Sun (outer planets last)
        const planetOrder = ['Sun', 'Moon', 'Mercury', 'Venus', 'Mars', 'Jupiter', 'Saturn', 'Uranus', 'Neptune', 'Pluto', 'NNode', 'SNode', 'Lilith', 'Chiron'];

        for (const name of planetOrder) {
            const planet = this.data.planets[name];
            if (!planet) continue;

            const pos = this.getPlanetPosition(planet.position, radius);
            const color = PLANET_COLORS[name] || '#d4a855';

            // Planet circle background
            ctx.beginPath();
            ctx.arc(pos.x, pos.y, size * 0.022, 0, Math.PI * 2);
            ctx.fillStyle = '#0d1322';
            ctx.fill();
            ctx.strokeStyle = color;
            ctx.lineWidth = 2;
            ctx.stroke();

            // Planet symbol
            ctx.fillStyle = color;
            ctx.font = `${size * 0.022}px serif`;
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText(PLANET_SYMBOLS[name] || name[0], pos.x, pos.y);

            // Planet name label
            if (this.options.showPlanetLabels) {
                ctx.fillStyle = 'rgba(255, 255, 255, 0.8)';
                ctx.font = `${size * 0.013}px Inter, sans-serif`;
                ctx.fillText(name.substring(0, 3), pos.x, pos.y - size * 0.035);
            }

            // Degree within sign
            if (this.options.showDegrees) {
                const deg = Math.floor(planet.position % 30);
                const min = Math.round((planet.position % 30) * 60);
                ctx.fillStyle = 'rgba(148, 163, 184, 0.7)';
                ctx.font = `${size * 0.011}px monospace`;
                ctx.fillText(`${deg}°`, pos.x, pos.y + size * 0.04);
            }

            // Retrograde indicator
            if (planet.retrograde) {
                ctx.fillStyle = '#ef4444';
                ctx.font = `${size * 0.012}px Inter, sans-serif`;
                ctx.fillText('R', pos.x + size * 0.015, pos.y - size * 0.015);
            }
        }
    }

    drawCenterLabels(ctx, size, center) {
        if (!this.data) return;

        const asc = this.data.ascendant;
        const mc = this.data.midheaven;

        // AC label
        if (asc) {
            const ascSign = ZODIAC_SIGNS[Math.floor(asc / 30)];
            ctx.fillStyle = '#d4a855';
            ctx.font = `bold ${size * 0.018}px Inter, sans-serif`;
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText('AC', center, center + size * 0.03);

            ctx.fillStyle = 'rgba(148, 163, 184, 0.7)';
            ctx.font = `${size * 0.014}px Inter, sans-serif`;
            ctx.fillText(`${ascSign.symbol} ${Math.round(asc)}°`, center, center + size * 0.06);
        }

        // MC label
        if (mc) {
            const mcSign = ZODIAC_SIGNS[Math.floor(mc / 30)];
            ctx.fillStyle = '#d4a855';
            ctx.font = `bold ${size * 0.018}px Inter, sans-serif`;
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText('MC', center, center - size * 0.05);

            ctx.fillStyle = 'rgba(148, 163, 184, 0.7)';
            ctx.font = `${size * 0.014}px Inter, sans-serif`;
            ctx.fillText(`${mcSign.symbol} ${Math.round(mc)}°`, center, center - size * 0.02);
        }
    }
}

/**
 * Initialize and render natal chart
 */
export function initNatalChart(birthData) {
    try {
        const container = document.getElementById('natalChartContainer');
        if (!container) {
            console.error('Container not found');
            return;
        }

        container.innerHTML = '';

        // Calculate with circular-natal-horoscope-js
        if (!Origin || !Horoscope) {
            container.innerHTML = `<div style="color:#ef4444;text-align:center;padding:40px;">Astrology library not loaded</div>`;
            return;
        }

        const origin = new Origin({
            year: birthData.year,
            month: birthData.month - 1,
            date: birthData.day,
            hour: birthData.hour,
            minute: birthData.minute,
            latitude: birthData.latitude,
            longitude: birthData.longitude
        });

        const horoscope = new Horoscope({
            origin: origin,
            houseSystem: 'placidus',
            zodiac: 'tropical',
            aspectPoints: ['bodies', 'points', 'angles'],
            aspectWithPoints: ['bodies', 'points', 'angles'],
            aspectTypes: ['major', 'minor'],
            language: 'en'
        });

        // Prepare chart data
        const planets = {};
        const bodies = horoscope.CelestialBodies || {};

        // Planet positions
        for (const [key, body] of Object.entries(bodies)) {
            if (body?.ChartPosition?.Ecliptic?.DecimalDegrees !== undefined) {
                const deg = body.ChartPosition.Ecliptic.DecimalDegrees;
                const house = body.House?.id || null;
                planets[key] = {
                    position: deg,
                    house: house,
                    retrograde: body.isRetrograde || false
                };
            }
        }

        // Celestial points
        const points = horoscope.CelestialPoints || {};
        if (points.NorthNode?.ChartPosition?.Ecliptic?.DecimalDegrees !== undefined) {
            planets.NNode = {
                position: points.NorthNode.ChartPosition.Ecliptic.DecimalDegrees,
                house: points.NorthNode.House?.id || null
            };
        }
        if (points.SouthNode?.ChartPosition?.Ecliptic?.DecimalDegrees !== undefined) {
            planets.SNode = {
                position: points.SouthNode.ChartPosition.Ecliptic.DecimalDegrees,
                house: points.SouthNode.House?.id || null
            };
        }
        if (points.Lilith?.ChartPosition?.Ecliptic?.DecimalDegrees !== undefined) {
            planets.Lilith = {
                position: points.Lilith.ChartPosition.Ecliptic.DecimalDegrees,
                house: points.Lilith.House?.id || null
            };
        }

        // House cusps
        const houses = [];
        const houseData = horoscope.Houses || [];
        houseData.forEach((house, i) => {
            const deg = house?.ChartPosition?.StartPosition?.Ecliptic?.DecimalDegrees || 0;
            const sign = house?.Sign?.label || '';
            houses.push({
                cusp: deg,
                sign: sign,
                signIndex: Math.floor(deg / 30)
            });
        });

        // Aspects
        const aspects = [];
        const allAspects = horoscope.Aspects?.all || [];
        for (const aspect of allAspects) {
            if (aspect.point1Key && aspect.point2Key && aspect.key) {
                aspects.push({
                    point1: aspect.point1Key,
                    point2: aspect.point2Key,
                    type: aspect.key,
                    orb: aspect.orb || 0
                });
            }
        }

        // Sort aspects by orb (tightest first)
        aspects.sort((a, b) => a.orb - b.orb);

        // Chart data
        const chartData = {
            planets: planets,
            houses: houses.map(h => h.cusp),
            ascendant: horoscope.Ascendant?.ChartPosition?.Ecliptic?.DecimalDegrees || 0,
            midheaven: horoscope.Midheaven?.ChartPosition?.Ecliptic?.DecimalDegrees || 0,
            aspects: aspects,
            data: horoscope
        };

        // Create chart
        const chart = new NatalChart('natalChartContainer', {
            showAspects: true,
            showHouseCusps: true,
            showSignSymbols: true,
            showPlanetLabels: true,
            showDegrees: true
        });

        // Now render with data
        chart.render(chartData);

        // Render data tables
        renderKeyPoints(horoscope);
        renderPlanets(horoscope);
        renderHouses(horoscope);
        renderAspects(horoscope);
        renderMidpoints(horoscope);
        renderEssentialDignities(horoscope);

        return chart;

    } catch (error) {
        console.error('Error:', error);
        const container = document.getElementById('natalChartContainer');
        if (container) {
            container.innerHTML = `<div style="color:#ef4444;text-align:center;padding:40px;">Error: ${error.message}</div>`;
        }
    }
}

function renderKeyPoints(horoscope) {
    const container = document.getElementById('keyPointsContainer');
    if (!container) return;

    const sun = horoscope.CelestialBodies?.Sun;
    const moon = horoscope.CelestialBodies?.Moon;
    const asc = horoscope.Ascendant;
    const mc = horoscope.Midheaven;
    const risingSign = asc?.Sign?.label || '—';

    const points = [
        { label: 'Sun', data: sun, icon: '☉' },
        { label: 'Moon', data: moon, icon: '☽' },
        { label: 'Ascendant', data: asc, icon: '♈', isAngle: true },
        { label: 'Midheaven', data: mc, icon: '♓', isAngle: true },
    ];

    container.innerHTML = points.map(point => {
        const sign = point.data?.Sign?.label || '—';
        const deg = point.data?.ChartPosition?.Ecliptic?.ArcDegrees?.degrees || 0;
        const min = point.data?.ChartPosition?.Ecliptic?.ArcDegrees?.minutes || 0;
        const house = point.data?.House?.id || '—';

        const color = point.isAngle ? '#d4a855' : '#6366f1';

        return `
            <div class="key-point">
                <div class="flex items-center gap-2 mb-2">
                    <span style="color: ${color}; font-size: 18px;">${point.icon}</span>
                    <h3 class="text-white font-medium">${point.label}</h3>
                </div>
                <div class="text-indigo-300 text-sm">${sign} ${deg}°${String(min).padStart(2, '0')}'</div>
                <div class="text-indigo-500 text-xs mt-1">House ${house}</div>
            </div>
        `;
    }).join('');
}

function renderPlanets(horoscope) {
    const container = document.getElementById('planetsContainer');
    if (!container) return;

    const bodies = horoscope.CelestialBodies || {};
    const points = horoscope.CelestialPoints || {};
    const all = { ...bodies, ...points };

    const planetOrder = ['Sun', 'Moon', 'Mercury', 'Venus', 'Mars', 'Jupiter', 'Saturn', 'Uranus', 'Neptune', 'Pluto', 'NNode', 'SNode', 'Lilith'];

    const rows = planetOrder
        .filter(key => all[key]?.ChartPosition)
        .map(key => {
            const body = all[key];
            const sign = body.Sign?.label || '—';
            const deg = body.ChartPosition?.Ecliptic?.ArcDegrees?.degrees || 0;
            const min = body.ChartPosition?.Ecliptic?.ArcDegrees?.minutes || 0;
            const house = body.House?.id || '—';
            const retrograde = body.isRetrograde ? ' R' : '';
            const color = PLANET_COLORS[key] || '#d4a855';

            return `
                <div class="planet-row flex items-center py-2 px-3 rounded-lg hover:bg-indigo-500/10 transition">
                    <span class="flex items-center gap-2 flex-1">
                        <span style="color: ${color}; font-size: 14px;">${PLANET_SYMBOLS[key] || key[0]}</span>
                        <span class="text-white">${key}${retrograde}</span>
                    </span>
                    <span class="text-indigo-300 w-24">${sign}</span>
                    <span class="text-indigo-500 font-mono text-xs w-16 text-right">${deg}°${String(min).padStart(2, '0')}'</span>
                    <span class="text-indigo-600 text-xs w-8 text-center">${house}</span>
                </div>
            `;
        }).join('');

    container.innerHTML = `<div class="space-y-1">${rows}</div>`;
}

function renderHouses(horoscope) {
    const container = document.getElementById('housesContainer');
    if (!container) return;

    const houses = horoscope.Houses || [];
    const signs = ['♈', '♉', '♊', '♋', '♌', '♍', '♎', '♏', '♐', '♑', '♒', '♓'];

    container.innerHTML = houses.map((house, i) => {
        const num = i + 1;
        const sign = house.Sign?.label || '—';
        const signIndex = ZODIAC_SIGNS.findIndex(s => s.name === sign);
        const symbol = signIndex >= 0 ? signs[signIndex] : '';
        const deg = Math.round(house.ChartPosition?.StartPosition?.Ecliptic?.ArcDegrees?.degrees || 0);

        // Element colors
        const signIndexForElement = ZODIAC_SIGNS.findIndex(s => s.name === sign);
        const element = signIndexForElement >= 0 ? ZODIAC_SIGNS[signIndexForElement].element : '';
        const elementColors = { fire: '#ef4444', earth: '#f59e0b', air: '#3b82f6', water: '#8b5cf6' };
        const borderColor = element ? elementColors[element] : 'rgba(99, 102, 241, 0.3)';

        return `
            <div class="text-center py-2 rounded-lg bg-indigo-950/30 border border-indigo-500/10 hover:border-indigo-500/30 transition" style="border-color: ${borderColor}">
                <div class="text-indigo-500 font-semibold text-xs">${num}</div>
                <div class="text-lg my-1">${symbol}</div>
                <div class="text-indigo-200 text-xs">${sign.substring(0, 3)}</div>
                <div class="text-indigo-500 font-mono text-xs">${deg}°</div>
            </div>
        `;
    }).join('');
}

function renderAspects(horoscope) {
    const container = document.getElementById('aspectsContainer');
    if (!container) return;

    const aspects = horoscope.Aspects?.all || [];

    if (aspects.length === 0) {
        container.innerHTML = '<p class="text-indigo-500">No significant aspects</p>';
        return;
    }

    // Group by type
    const majorAspects = aspects.filter(a => ['conjunction', 'opposition', 'square', 'trine', 'sextile'].includes(a.key));
    const minorAspects = aspects.filter(a => !['conjunction', 'opposition', 'square', 'trine', 'sextile'].includes(a.key));

    const formatAspect = (aspect) => {
        const orb = Math.round(aspect.orb * 10) / 10;
        const color = ASPECT_TYPES[aspect.key]?.color || '#6366f1';

        return `
            <div class="flex items-center gap-2 text-sm py-1">
                <span class="text-white">${aspect.point1Key}</span>
                <span style="color: ${color}; font-weight: 600;">${aspect.key}</span>
                <span class="text-white">${aspect.point2Key}</span>
                <span class="text-indigo-500 ml-auto">${orb}°</span>
            </div>
        `;
    };

    container.innerHTML = `
        <div>
            <h4 class="text-indigo-400 text-xs uppercase tracking-wider mb-3">Major Aspects</h4>
            <div class="space-y-1">
                ${majorAspects.slice(0, 15).map(formatAspect).join('')}
            </div>
        </div>
        <div>
            <h4 class="text-indigo-400 text-xs uppercase tracking-wider mb-3">Minor Aspects</h4>
            <div class="space-y-1">
                ${minorAspects.slice(0, 10).map(formatAspect).join('')}
            </div>
        </div>
    `;
}

function renderMidpoints(horoscope) {
    const container = document.getElementById('midpointsContainer');
    if (!container) return;

    // Calculate some important midpoints
    const bodies = horoscope.CelestialBodies || {};
    const midpoints = [];

    // Sun-Moon midpoint (personal synthesis)
    if (bodies.Sun?.ChartPosition?.Ecliptic?.DecimalDegrees !== undefined &&
        bodies.Moon?.ChartPosition?.Ecliptic?.DecimalDegrees !== undefined) {
        const sunPos = bodies.Sun.ChartPosition.Ecliptic.DecimalDegrees;
        const moonPos = bodies.Moon.ChartPosition.Ecliptic.DecimalDegrees;
        let midpoint = ((sunPos + moonPos) / 2) % 360;
        if (midpoint < 0) midpoint += 360;

        const sign = ZODIAC_SIGNS[Math.floor(midpoint / 30)];
        midpoints.push({ name: 'Sun/Moon', position: midpoint, sign: sign.name });
    }

    // Asc-MC midpoint
    if (horoscope.Ascendant?.ChartPosition?.Ecliptic?.DecimalDegrees !== undefined &&
        horoscope.Midheaven?.ChartPosition?.Ecliptic?.DecimalDegrees !== undefined) {
        const asc = horoscope.Ascendant.ChartPosition.Ecliptic.DecimalDegrees;
        const mc = horoscope.Midheaven.ChartPosition.Ecliptic.DecimalDegrees;
        let midpoint = ((asc + mc) / 2) % 360;
        if (midpoint < 0) midpoint += 360;

        const sign = ZODIAC_SIGNS[Math.floor(midpoint / 30)];
        midpoints.push({ name: 'AC/MC', position: midpoint, sign: sign.name });
    }

    container.innerHTML = midpoints.map(m => {
        const deg = Math.floor(m.position % 30);
        const min = Math.round((m.position % 30) * 60);
        return `
            <div class="flex items-center gap-2 py-1 text-sm">
                <span class="text-white font-medium">${m.name}</span>
                <span class="text-indigo-400">${m.sign}</span>
                <span class="text-indigo-600 ml-auto">${deg}°${String(min).padStart(2, '0')}'</span>
            </div>
        `;
    }).join('') || '<p class="text-indigo-500 text-sm">No midpoints calculated</p>';
}

function renderEssentialDignities(horoscope) {
    const container = document.getElementById('dignitiesContainer');
    if (!container) return;

    const bodies = horoscope.CelestialBodies || {};
    const dignities = [];

    // Simplified dignities calculation
    const rulerships = {
        'Aries': 'Mars', 'Taurus': 'Venus', 'Gemini': 'Mercury', 'Cancer': 'Moon',
        'Leo': 'Sun', 'Virgo': 'Mercury', 'Libra': 'Venus', 'Scorpio': 'Pluto',
        'Sagittarius': 'Jupiter', 'Capricorn': 'Saturn', 'Aquarius': 'Uranus', 'Pisces': 'Jupiter'
    };

    const exaltations = {
        'Aries': 'Sun', 'Taurus': 'Moon', 'Leo': 'Jupiter', 'Libra': 'Saturn',
        'Pisces': 'Venus', 'Cancer': 'Jupiter', 'Capricorn': 'Mars', 'Virgo': 'Mercury'
    };

    const falls = {
        'Libra': 'Sun', 'Scorpio': 'Moon', 'Aquarius': 'Jupiter', 'Aries': 'Saturn',
        'Virgo': 'Venus', 'Capricorn': 'Jupiter', 'Pisces': 'Mercury', 'Leo': 'Pluto'
    };

    const detriments = {
        'Aries': 'Venus', 'Taurus': 'Mars', 'Cancer': 'Saturn', 'Leo': 'Saturn',
        'Virgo': 'Jupiter', 'Libra': 'Mars', 'Scorpio': 'Venus', 'Sagittarius': 'Mercury',
        'Capricorn': 'Moon', 'Aquarius': 'Sun', 'Pisces': 'Mercury'
    };

    for (const [key, body] of Object.entries(bodies)) {
        if (!body?.Sign?.label) continue;

        const sign = body.Sign.label;
        const planet = key;

        let dignity = 'neutral';

        if (rulerships[sign] === planet) dignity = 'rulership';
        if (exaltations[sign] === planet) dignity = 'exaltation';
        if (falls[sign] === planet) dignity = 'fall';
        if (detriments[sign] === planet) dignity = 'detriment';

        if (dignity !== 'neutral') {
            dignities.push({ planet, dignity, sign });
        }
    }

    const dignityColors = {
        'rulership': '#10b981',
        'exaltation': '#3b82f6',
        'fall': '#ef4444',
        'detriment': '#f59e0b'
    };

    const dignityLabels = {
        'rulership': 'Ruling',
        'exaltation': 'Exalted',
        'fall': 'Fall',
        'detriment': 'Detriment'
    };

    container.innerHTML = dignities.map(d => `
        <div class="flex items-center gap-2 py-1 text-sm">
            <span class="text-white w-20">${d.planet}</span>
            <span class="text-indigo-400">${d.sign}</span>
            <span class="px-2 py-0.5 rounded text-xs font-medium" style="background: ${dignityColors[d.dignity]}20; color: ${dignityColors[d.dignity]}">
                ${dignityLabels[d.dignity]}
            </span>
        </div>
    `).join('') || '<p class="text-indigo-500 text-sm">No essential dignities</p>';
}

// Export to window
window.initNatalChart = initNatalChart;
window.NatalChart = NatalChart;
