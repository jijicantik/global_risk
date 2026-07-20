import './bootstrap';

import Alpine from 'alpinejs';
import L from 'leaflet';

window.Alpine = Alpine;
window.L = L;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {

    const mapElement = document.getElementById('map');

    if (mapElement) {

        const map = L.map('map').setView([20, 0], 2);

        L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            {
                attribution: '© OpenStreetMap'
            }
        ).addTo(map);

    }

});