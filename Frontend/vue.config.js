const LARAVEL_BACKEND_URL = 'http://localhost:8000';

module.exports = {
  runtimeCompiler: true,

  devServer: {
    proxy: {
      '^/api': {
        target: LARAVEL_BACKEND_URL,
        changeOrigin: true,
        logLevel: 'debug',
      },
    },
  },
};