import express from 'express';
import cors from 'cors';
import helmet from 'helmet';
import path from 'path';
import { fileURLToPath } from 'url';
import membreRouter from './routes/membre.routes.js';
import { notFoundHandler, errorHandler } from './middleware/error.middleware.js';
import { attachDB } from './middleware/db.middleware.js';
import { configureUploadDir } from './utils/file-system.js';
import env from './config/env.js';

const app = express();

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const allowAllOrigins = env.cors.allowedOrigins.length === 1 && env.cors.allowedOrigins[0] === '*';

// Ensure required directories exist before serving static files
configureUploadDir();

app.use(helmet());
app.use(cors({
  origin: allowAllOrigins ? true : env.cors.allowedOrigins,
  methods: ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
  allowedHeaders: ['Content-Type', 'Authorization', 'X-Requested-With'],
  credentials: true
}));
app.use(express.json({ limit: '10mb' }));
app.use(express.urlencoded({ extended: true }));

// Attach database instance (MySQL or SQLite) to requests
app.use(attachDB);

// Serve static images similar to PHP version
app.use('/images', express.static(path.join(__dirname, '../public/images')));

app.get('/health', (req, res) => res.json({ status: 'ok', environment: env.nodeEnv }));

app.use('/api/opendevmada', membreRouter);

app.use(notFoundHandler);
app.use(errorHandler);

export default app;
