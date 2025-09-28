export const notFoundHandler = (req, res, next) => {
  res.status(404).json({
    status: 'error',
    message: `Route ${req.originalUrl} not found`
  });
};

export const errorHandler = (err, req, res, next) => { // eslint-disable-line no-unused-vars
  console.error(err);

  const status = err.status || 500;
  const message = err.message || 'Une erreur inattendue est survenue';

  res.status(status).json({
    status: 'error',
    message
  });
};
