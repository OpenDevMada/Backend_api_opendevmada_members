import app from './app.js';
import env from './config/env.js';
import { initialiseDatabase } from './db/index.js';

(async () => {
  try {
    await initialiseDatabase();

    app.listen(env.port, () => {
      console.log(`🚀 OpenDevMada API running on port ${env.port} in ${env.nodeEnv} mode`);
    });
  } catch (error) {
    console.error('Failed to start server:', error);
    process.exit(1);
  }
})();
