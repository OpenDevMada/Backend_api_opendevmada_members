// Configuration pour contourner les limitations CORS d'InfinityFree

// SOLUTION TEMPORAIRE : Utiliser un proxy CORS public
const CORS_PROXY = 'https://cors-anywhere.herokuapp.com/';
const API_BASE = 'https://opendevmadaannuaire.infinityfree.me';

// Fonction pour faire des requêtes avec proxy CORS
async function apiRequest(endpoint, options = {}) {
    const url = `${CORS_PROXY}${API_BASE}${endpoint}`;
    
    const defaultOptions = {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        }
    };
    
    const finalOptions = { ...defaultOptions, ...options };
    
    try {
        const response = await fetch(url, finalOptions);
        return await response.json();
    } catch (error) {
        console.error('API Request failed:', error);
        throw error;
    }
}

// SOLUTION PERMANENTE : Déployer sur un autre service
const ALTERNATIVE_SERVICES = {
    'Railway': 'https://railway.app - Support CORS complet',
    'Render': 'https://render.com - Free tier avec CORS',
    'Vercel Functions': 'Créer des API endpoints directement sur Vercel',
    'Netlify Functions': 'Fonctions serverless avec CORS',
    'Heroku': 'Free tier disponible avec contrôle CORS complet'
};

// Test avec proxy CORS
console.log('🔧 Pour tester avec proxy CORS :');
console.log('apiRequest("/api/opendevmada/membres").then(console.log)');

// Export pour usage
if (typeof module !== 'undefined') {
    module.exports = { apiRequest, ALTERNATIVE_SERVICES };
}