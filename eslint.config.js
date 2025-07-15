import js from '@eslint/js'
import vuePlugin from 'eslint-plugin-vue'
import vueParser from 'vue-eslint-parser'
import babelParser from '@babel/eslint-parser'
import globals from 'globals'

export default [
  js.configs.recommended,

  {
    files: ['resources/js/**/*.vue'],
    languageOptions: {
      parser: vueParser,
      parserOptions: {
        parser: babelParser,
        requireConfigFile: false,
        ecmaVersion: 'latest',
        sourceType: 'module',
      },
      globals: {
        ...globals.browser,
        ...globals.node,
      },
    },
    plugins: { vue: vuePlugin },
    rules: {
      'vue/multi-word-component-names': 'off',
      // add other Vue rules here
    },
  },

  {
    files: ['resources/js/**/*.js'],
    languageOptions: {
      ecmaVersion: 'latest',
      sourceType: 'module',
      globals: {
        ...globals.browser,
        ...globals.node,
      },
    },
  },
]
