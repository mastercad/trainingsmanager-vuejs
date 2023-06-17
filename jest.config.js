module.exports = {
    verbose: true,
//    preset: "ts-jest",
    testEnvironment: "node",
    moduleFileExtensions: [
        "js",
        "json",
        "vue"
    ],moduleNameMapper: {
      '^@/(.*)$': '<rootDir>/src/$1'
    },
    transform: {
      '^.+\\.vue$': 'vue-jest',
      '.+\\.(css|styl|less|sass|scss|png|jpg|ttf|woff|woff2)$':
        'jest-transform-stub',
      '^.+\\.(js|jsx)?$': 'babel-jest'
    },
    collectCoverage: true,
    collectCoverageFrom: [
        "src/components/*.{js,vue}",
        "!**/node_modules/**"
    ],
    coverageReporters: [
        "html",
        "text-summary"
    ],
    snapshotSerializers: ['jest-serializer-vue'],
    testMatch: [
      '<rootDir>/assets/(tests/unit/**/*.spec.(js|jsx|ts|tsx)|**/__tests__/*.(js|jsx|ts|tsx)|tests/**/*.test.(js|jsx|ts|tsx))'
    ],
    transformIgnorePatterns: ['<rootDir>/node_modules/']
};
