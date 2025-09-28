module.exports = {
  root: true,
  env: {
    es2022: true,
    node: true
  },
  extends: [
    'standard'
  ],
  parserOptions: {
    ecmaVersion: 'latest',
    sourceType: 'module'
  },
  rules: {
    semi: ['error', 'always'],
    'comma-dangle': ['error', 'never'],
    'quote-props': ['error', 'as-needed'],
    'space-before-function-paren': 'off'
  }
};
