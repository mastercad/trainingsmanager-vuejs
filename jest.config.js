const jestConfig = {
  setupFilesAfterEnv: ['<rootDir>jest.setup.js'],
  testEnvironment: "jsdom",
  verbose: true,
  'transform': {
//    '^.+\\.js?$': 'babel-jest',
    "^[^.]+.vue$": "vue-jest",
    "^.+\\.js$": "babel-jest"
  },
  testMatch: [
    '<rootDir>/assets/js/tests/*.test.js?',
    '<rootDir>/assets/**/*.test.js'
  ],
};

module.exports = jestConfig;
