import knex from 'knex';
import path from 'path';
import env from '../config/env.js';
import { ensureDirectory } from '../utils/file-system.js';

let db;

const getConfig = () => {
  const { client, connection } = env.database;

  const baseConfig = {
    client,
    connection,
    pool: { min: 0, max: 10 },
    migrations: {
      tableName: 'knex_migrations'
    }
  };

  if (client === 'sqlite3') {
    return {
      ...baseConfig,
      useNullAsDefault: true
    };
  }

  return baseConfig;
};

export const initialiseDatabase = async () => {
  if (!db) {
    db = knex(getConfig());
  }

  if (env.database.client === 'sqlite3') {
    const filename = env.database.sqlite?.filename;
    if (filename) {
      ensureDirectory(path.dirname(filename));
    }
  }

  const hasTable = await db.schema.hasTable('membres');

  if (!hasTable) {
    await db.schema.createTable('membres', (table) => {
      table.increments('id').primary();
      table.string('nom', 100).notNullable();
      table.string('prenom', 100).notNullable();
      table.string('email', 150).unique();
      table.text('mot_de_passe');
      table.date('date_naissance');
      table.string('sexe', 10);
      table.text('adresse');
      table.string('ville', 100);
      table.string('pays', 100);
      table.string('telephone', 20);
      table.text('photo_profil');
      table.timestamp('date_inscription').defaultTo(db.fn.now());
      table.timestamp('dernier_connexion');
      table.string('role', 50).defaultTo('membre');
      table.string('statut', 50).defaultTo('actif');
    });
  }

  return db;
};

export const getDB = () => {
  if (!db) {
    throw new Error('Database has not been initialised. Call initialiseDatabase first.');
  }
  return db;
};

export const destroyDatabase = async () => {
  if (db) {
    await db.destroy();
    db = null;
  }
};
