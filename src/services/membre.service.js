import bcrypt from 'bcryptjs';
import { removeImage } from '../utils/file-system.js';

const TABLE = 'membres';
const SALT_ROUNDS = 10;

const cleanObject = (obj) => Object.fromEntries(
  Object.entries(obj).filter(([, value]) => value !== undefined)
);

export const listMembers = async (db) => {
  return db(TABLE).select('*').orderBy('id', 'desc');
};

export const getMemberById = async (db, id) => {
  return db(TABLE).where({ id }).first();
};

export const getMemberByEmail = async (db, email) => {
  return db(TABLE).where({ email }).first();
};

export const verifyPassword = async (plain, hashed) => {
  if (!hashed) return false;
  return bcrypt.compare(plain, hashed);
};

export const hashPassword = async (password) => {
  if (!password) return null;
  return bcrypt.hash(password, SALT_ROUNDS);
};

export const createMember = async (db, payload) => {
  const passwordHash = payload.password ? await hashPassword(payload.password) : null;

  const data = cleanObject({
    nom: payload.nom,
    prenom: payload.prenom,
    email: payload.email || null,
    mot_de_passe: passwordHash,
    date_naissance: payload.date_naissance || null,
    sexe: payload.sexe || null,
    adresse: payload.adresse || null,
    ville: payload.ville || null,
    pays: payload.pays || null,
    telephone: payload.telephone || null,
    photo_profil: payload.photo_profil || null,
    role: payload.role || 'admin',
    statut: payload.statut || 'active'
  });

  const [id] = await db(TABLE).insert(data);
  return getMemberById(db, id);
};

export const updateMember = async (db, id, payload) => {
  const updates = cleanObject({ ...payload });

  if (payload.password) {
    updates.mot_de_passe = await hashPassword(payload.password);
  }

  delete updates.password;
  delete updates.previousPhoto;

  await db(TABLE).where({ id }).update(updates);
  return getMemberById(db, id);
};

export const deleteMember = async (db, id) => {
  return db(TABLE).where({ id }).del();
};

export const touchDerniereConnexion = async (db, id) => {
  return db(TABLE).where({ id }).update({ dernier_connexion: db.fn.now() });
};

export const deleteMemberPhoto = (relativePath) => {
  removeImage(relativePath);
};
