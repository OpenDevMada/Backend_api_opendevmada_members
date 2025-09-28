import fs from 'fs';
import path from 'path';
import crypto from 'crypto';
import env from '../config/env.js';

export const ensureDirectory = (dirPath) => {
  if (!fs.existsSync(dirPath)) {
    fs.mkdirSync(dirPath, { recursive: true });
  }
};

export const configureUploadDir = () => {
  ensureDirectory(env.uploads.baseDir);
};

export const buildMemberImageDir = (memberName) => {
  const safeName = (memberName || 'default').replace(/[^a-z0-9_-]/gi, '_');
  const dir = path.join(env.uploads.baseDir, safeName);
  ensureDirectory(dir);
  return dir;
};

export const relativeImagePath = (memberName, filename) => {
  const safeName = (memberName || 'default').replace(/[^a-z0-9_-]/gi, '_');
  return `images/${safeName}/${filename}`;
};

export const saveImageBuffer = (memberName, file) => {
  if (!file || !file.buffer) return null;

  const dir = buildMemberImageDir(memberName);
  const ext = path.extname(file.originalname) || '.png';
  const unique = `${Date.now()}_${crypto.randomBytes(4).toString('hex')}${ext}`;
  const targetPath = path.join(dir, unique);

  fs.writeFileSync(targetPath, file.buffer);

  return {
    absolutePath: targetPath,
    relativePath: relativeImagePath(memberName, unique)
  };
};

export const removeImage = (relativePath) => {
  if (!relativePath) return;

  const absolutePath = path.join(env.uploads.baseDir, '..', relativePath);
  if (fs.existsSync(absolutePath)) {
    fs.unlinkSync(absolutePath);
  }
};
