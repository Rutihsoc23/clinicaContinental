const { defineConfig } = require("cypress");

module.exports = defineConfig({
  e2e: {
    baseUrl: 'http://127.0.0.1:8000', // La URL de php artisan serve
    setupNodeEvents(on, config) {
      // listeners
    },
  },
});