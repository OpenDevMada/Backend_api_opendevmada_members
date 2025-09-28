import dotenv from 'dotenv';
import path from 'path';
import { fileURLToPath } from 'url';

dotenv.config();

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const resolvePath = (...segments) => path.join(__dirname, '../../', ...segments);

const env = {
  nodeEnv: process.env.NODE_ENV || 'development',
  port: parseInt(process.env.PORT || '8000', 10),
  database: {
    client: process.env.DB_CLIENT || 'sqlite3',
    mysql: {
      host: process.env.DB_HOST || process.env.MYSQLHOST || '127.0.0.1',
      port: parseInt(process.env.DB_PORT || process.env.MYSQLPORT || '3306', 10),
      user: process.env.DB_USER || process.env.MYSQLUSER || 'root',
      password: process.env.DB_PASSWORD || process.env.MYSQLPASSWORD || '',
      database: process.env.DB_NAME || process.env.MYSQLDATABASE || 'railway'
    },
    sqlite: {
      filename: process.env.SQLITE_PATH || resolvePath('storage', 'database.sqlite')
    }
  },
  uploads: {
    baseDir: resolvePath('public', 'images')
  },
  cors: {
    allowedOrigins: (process.env.CORS_ORIGINS || '*').split(',').map(origin => origin.trim())
  }
};

if (env.database.client === 'sqlite3') {
  env.database.connection = {
    filename: env.database.sqlite.filename
  };
} else {
  env.database.connection = {
    host: env.database.mysql.host,
    port: env.database.mysql.port,
    user: env.database.mysql.user,
    password: env.database.mysql.password,
    database: env.database.mysql.database
  };
}

export default env;
