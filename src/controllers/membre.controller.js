import { validationResult } from 'express-validator';
import {
  listMembers,
  getMemberById,
  getMemberByEmail,
  verifyPassword,
  createMember,
  updateMember,
  deleteMember,
  touchDerniereConnexion,
  deleteMemberPhoto
} from '../services/membre.service.js';
import { saveImageBuffer } from '../utils/file-system.js';

const parseBirthday = (value) => {
  if (!value) return null;

  // Accept both dd/mm/yyyy and yyyy-mm-dd
  const slashMatch = value.match(/^(\d{2})\/(\d{2})\/(\d{4})$/);
  if (slashMatch) {
    const [, day, month, year] = slashMatch;
    return `${year}-${month}-${day}`;
  }

  const isoMatch = value.match(/^(\d{4})-(\d{2})-(\d{2})$/);
  if (isoMatch) {
    return value;
  }

  return null;
};

const mapMemberPayload = (body) => ({
  nom: body.nom,
  prenom: body.prenom,
  email: body.email || null,
  password: body.password || null,
  date_naissance: parseBirthday(body.birthday),
  sexe: body.sexe || body.gender || null,
  adresse: body.address || null,
  ville: body.city || null,
  pays: body.contry || body.pays || null,
  telephone: body.phone || body.telephone || null,
  role: body.role || 'admin',
  statut: body.statut || body.status || 'active'
});

export const list = async (req, res, next) => {
  try {
    const members = await listMembers(req.db);

    if (!members.length) {
      return res.json({
        status: 'success',
        message: 'Aucun membre trouvé',
        data: []
      });
    }

    return res.json({ status: 'success', data: members });
  } catch (error) {
    return next(error);
  }
};

export const detail = async (req, res, next) => {
  try {
    const errors = validationResult(req);
    if (!errors.isEmpty()) {
      return res.status(400).json({ status: 'error', message: errors.array()[0].msg });
    }

    const member = await getMemberById(req.db, req.params.id);

    if (!member) {
      return res.json({
        status: 'success',
        message: 'Aucun membre trouvé',
        data: []
      });
    }

    return res.json({ status: 'success', data: member });
  } catch (error) {
    return next(error);
  }
};

export const login = async (req, res, next) => {
  try {
    const errors = validationResult(req);
    if (!errors.isEmpty()) {
      return res.status(400).json({ status: 'error', message: errors.array()[0].msg });
    }

    const { email, password } = req.body;

    const member = await getMemberByEmail(req.db, email);
    if (!member) {
      return res.status(401).json({ status: 'error', message: 'Email ou mot de passe incorrect' });
    }

    const isValidPassword = await verifyPassword(password, member.mot_de_passe);
    if (!isValidPassword) {
      return res.status(401).json({ status: 'error', message: 'Email ou mot de passe incorrect' });
    }

    return res.json({
      status: 'success',
      message: 'Connexion réussie !',
      membre: {
        id: member.id,
        nom: member.nom,
        prenom: member.prenom,
        role: member.role,
        photo_profil: member.photo_profil
      }
    });
  } catch (error) {
    return next(error);
  }
};

export const logout = async (req, res, next) => {
  try {
    const errors = validationResult(req);
    if (!errors.isEmpty()) {
      return res.status(400).json({ status: 'error', message: errors.array()[0].msg });
    }

    await touchDerniereConnexion(req.db, req.params.id);
    return res.json({ status: 'Succès', message: 'Déconnexion réussi !' });
  } catch (error) {
    return next(error);
  }
};

export const create = async (req, res, next) => {
  let savedImage = null;
  try {
    const errors = validationResult(req);
    if (!errors.isEmpty()) {
      return res.status(400).json({ status: 'error', message: errors.array()[0].msg });
    }

    if (!req.file) {
      return res.status(400).json({ status: 'error', message: 'Image requise pour créer un membre.' });
    }

    const payload = mapMemberPayload(req.body);

    if (!payload.nom || !payload.prenom || !payload.telephone) {
      return res.status(400).json({ status: 'error', message: 'Nom, prénom et téléphone sont requis.' });
    }

    savedImage = saveImageBuffer(payload.nom, req.file);
    const member = await createMember(req.db, { ...payload, photo_profil: savedImage?.relativePath });

    return res.status(201).json({ status: 'success', message: 'Membre créé avec succès !', data: member });
  } catch (error) {
    if (savedImage?.relativePath) {
      deleteMemberPhoto(savedImage.relativePath);
    }
    return next(error);
  }
};

export const update = async (req, res, next) => {
  try {
    const errors = validationResult(req);
    if (!errors.isEmpty()) {
      return res.status(400).json({ status: 'error', message: errors.array()[0].msg });
    }

    const member = await getMemberById(req.db, req.params.id);
    if (!member) {
      return res.status(404).json({ status: 'error', message: 'Membre non trouvé.' });
    }

    const payload = mapMemberPayload({ ...member, ...req.body });
    payload.nom = member.nom; // name is immutable per original behaviour
    payload.prenom = member.prenom;
    payload.photo_profil = member.photo_profil;

    let newPhotoPath = null;
    if (req.file) {
      const newImage = saveImageBuffer(member.nom, req.file);
      newPhotoPath = newImage?.relativePath || null;
      if (newPhotoPath) {
        payload.photo_profil = newPhotoPath;
      }
    }

    const updatedMember = await updateMember(req.db, req.params.id, payload);

    if (newPhotoPath && member.photo_profil && member.photo_profil !== newPhotoPath) {
      deleteMemberPhoto(member.photo_profil);
    }

    return res.json({ status: 'success', message: 'Le membre a été mis à jour !', data: updatedMember });
  } catch (error) {
    return next(error);
  }
};

export const remove = async (req, res, next) => {
  try {
    const errors = validationResult(req);
    if (!errors.isEmpty()) {
      return res.status(400).json({ status: 'error', message: errors.array()[0].msg });
    }

    const member = await getMemberById(req.db, req.params.id);
    if (!member) {
      return res.status(404).json({ status: 'error', message: 'Membre non trouvé.' });
    }

    await deleteMember(req.db, req.params.id);
    if (member.photo_profil) {
      deleteMemberPhoto(member.photo_profil);
    }

    return res.json({ status: 'Succès', message: "Suppression d'un membre réussi !" });
  } catch (error) {
    return next(error);
  }
};
