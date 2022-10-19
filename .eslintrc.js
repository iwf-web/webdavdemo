module.exports = {
  env: {
    browser: true,
    es2021: true,
  },
  extends: [
    'plugin:react/recommended',
    'google',
  ],
  parserOptions: {
    ecmaFeatures: {
      jsx: true,
    },
    ecmaVersion: 12,
    sourceType: 'module',
  },
  plugins: [
    'react',
  ],
  rules: {
    'indent': [
      'error', 2, {
        'CallExpression': {
          'arguments': 1,
        },
        'FunctionDeclaration': {
          'body': 1,
          'parameters': 1,
        },
        'FunctionExpression': {
          'body': 1,
          'parameters': 1,
        },
        'MemberExpression': 1,
        'ObjectExpression': 1,
        'SwitchCase': 1,
        'ignoredNodes': [
          'ConditionalExpression',
        ],
      },
    ],
    'max-len': ['error', {'code': 120}],
  },
};