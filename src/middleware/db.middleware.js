import { getDB } from '../db/index.js';

export const attachDB = (req, res, next) => {
  try {
    req.db = getDB();
    next();
  } catch (error) {
    next(error);
  }
};
