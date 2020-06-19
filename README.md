# CI

![CI](https://github.com/piotrkreft/ci/workflows/CI/badge.svg)
[![Coverage Status](https://coveralls.io/repos/github/piotrkreft/ci/badge.svg)](https://coveralls.io/github/piotrkreft/ci)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fpiotrkreft%2Fci%2Fmaster)](https://infection.github.io)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/piotrkreft/ci/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/piotrkreft/ci/?branch=master)

A simple package containing tests and dependencies to maintain quality across projects.

## Usage

`bin/pk-tests run` - triggers tests

`bin/pk-tests fix` - triggers fixers for static checks

By using `--project-dir` option it's possible to manipulate the directory taken as a main one.

## License
The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.
