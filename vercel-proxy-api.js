// API Proxy pour contourner CORS - À déployer sur Vercel
// Fichier: /api/proxy.js (dans votre projet Vercel)

export default async function handler(req, res) {
    // Configuration CORS
    res.setHeader('Access-Control-Allow-Origin', '*');
    res.setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    res.setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');

    // Handle preflight
    if (req.method === 'OPTIONS') {
        res.status(200).end();
        return;
    }

    try {
        // URL de votre API backend
        const backendUrl = 'https://opendevmadaannuaire.infinityfree.me';
        
        // Construire l'URL complète
        const targetUrl = `${backendUrl}${req.url.replace('/api/proxy', '')}`;
        
        console.log('Proxying to:', targetUrl);

        // Forward the request
        const options = {
            method: req.method,
            headers: {
                'Content-Type': 'application/json',
                'User-Agent': 'Vercel-Proxy/1.0'
            }
        };

        // Add body for POST/PUT requests
        if (req.method !== 'GET' && req.method !== 'HEAD') {
            options.body = JSON.stringify(req.body);
        }

        const response = await fetch(targetUrl, options);
        const data = await response.text();

        // Try to parse JSON, fallback to text
        let result;
        try {
            result = JSON.parse(data);
        } catch {
            result = { raw_response: data };
        }

        res.status(response.status).json(result);

    } catch (error) {
        console.error('Proxy error:', error);
        res.status(500).json({ 
            error: 'Proxy request failed', 
            message: error.message 
        });
    }
}

// Configuration pour Vercel
export const config = {
    api: {
        bodyParser: {
            sizeLimit: '1mb',
        },
    },
}