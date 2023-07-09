const jestConfig = {
  setupFilesAfterEnv: ['<rootDir>jest.setup.js'],
  testEnvironment: "jsdom",
  moduleFileExtensions: [
    "js",
    "json",
    "vue"
  ],
  coverageProvider: 'v8',
  collectCoverageFrom: ["assets/js/**/*.(vue|ts|js)"],
  verbose: true,
  transform: {
    "^.+\\.vue$": "<rootDir>/node_modules/vue-jest",
    "^.+\\.js$": "<rootDir>/node_modules/babel-jest"
  },
  testMatch: [
    '<rootDir>/assets/**/*.test.js'
  ],
};

module.exports = jestConfig;
