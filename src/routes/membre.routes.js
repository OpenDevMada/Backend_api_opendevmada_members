import { Router } from 'express';
import multer from 'multer';
import { body, param } from 'express-validator';
import {
  list,
  detail,
  login,
  logout,
  create,
  update,
  remove
} from '../controllers/membre.controller.js';

const router = Router();
const upload = multer({
  storage: multer.memoryStorage(),
  limits: { fileSize: 5 * 1024 * 1024 }
});

const loginValidators = [
  body('email').isEmail().withMessage('email manquant'),
  body('password').notEmpty().withMessage('mot de passe manquant')
];

const idParamValidator = [
  param('id').isInt().withMessage('Identifiant invalide')
];

const createValidators = [
  body('nom').notEmpty().withMessage('Nom requis'),
  body('prenom').notEmpty().withMessage('Prénom requis'),
  body('phone').notEmpty().withMessage('Téléphone requis')
];

router.get('/membres', list);
router.get('/membre/:id', idParamValidator, detail);
router.post('/membre-login', loginValidators, login);
router.post('/membre-logout/:id', idParamValidator, logout);
router.delete('/membre-delete/:id', idParamValidator, remove);
router.post('/membre-create', upload.single('image'), createValidators, create);
router.post('/membre-update/:id', idParamValidator, upload.single('image'), update);

export default router;
