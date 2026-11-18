#!/usr/bin/env bats
#
# Unit Tests: Package Structure
#
# Validates that all required files and directories of laravel-essentials
# are present and correctly configured.
#

# Load test helpers
load '../helpers/test_helper'

setup() {
  setup_test_env
}

teardown() {
  teardown_test_env
}

# ============================================================================
# composer.json
# ============================================================================

@test "composer.json exists" {
  assert_file_exists "${PROJECT_ROOT}/composer.json"
}

@test "composer.json has correct package name" {
  run grep -q '"name": "zairakai/laravel-essentials"' "${PROJECT_ROOT}/composer.json"

  [ "$status" -eq 0 ]
}

@test "composer.json has library type" {
  run grep -q '"type": "library"' "${PROJECT_ROOT}/composer.json"

  [ "$status" -eq 0 ]
}

@test "composer.json requires php ^8.4" {
  run grep -F '"php": "^8.4"' "${PROJECT_ROOT}/composer.json"

  [ "$status" -eq 0 ]
}

@test "composer.json autoloads all helper files" {
  local helpers=("array.php" "boolean.php" "filesystem.php" "format.php" "math.php" "string.php" "validation.php")

  for helper in "${helpers[@]}"; do
    run grep -q "$helper" "${PROJECT_ROOT}/composer.json"
    [ "$status" -eq 0 ]
  done
}

@test "composer.json defines quality scripts" {
  local scripts=("analyse" "cs" "quality" "test" "test:coverage" "rector")

  for script in "${scripts[@]}"; do
    run grep -q "\"${script}\"" "${PROJECT_ROOT}/composer.json"
    [ "$status" -eq 0 ]
  done
}

@test "composer.json requires laravel-dev-tools" {
  run grep -q '"zairakai/laravel-dev-tools"' "${PROJECT_ROOT}/composer.json"

  [ "$status" -eq 0 ]
}

# ============================================================================
# Source Files
# ============================================================================

@test "src/ directory exists" {
  assert_dir_exists "${PROJECT_ROOT}/src"
}

@test "src/helpers/ directory exists" {
  assert_dir_exists "${PROJECT_ROOT}/src/helpers"
}

@test "helper file array.php exists" {
  assert_file_exists "${PROJECT_ROOT}/src/helpers/array.php"
}

@test "helper file boolean.php exists" {
  assert_file_exists "${PROJECT_ROOT}/src/helpers/boolean.php"
}

@test "helper file filesystem.php exists" {
  assert_file_exists "${PROJECT_ROOT}/src/helpers/filesystem.php"
}

@test "helper file format.php exists" {
  assert_file_exists "${PROJECT_ROOT}/src/helpers/format.php"
}

@test "helper file math.php exists" {
  assert_file_exists "${PROJECT_ROOT}/src/helpers/math.php"
}

@test "helper file string.php exists" {
  assert_file_exists "${PROJECT_ROOT}/src/helpers/string.php"
}

@test "helper file validation.php exists" {
  assert_file_exists "${PROJECT_ROOT}/src/helpers/validation.php"
}

@test "EssentialsServiceProvider.php exists" {
  assert_file_exists "${PROJECT_ROOT}/src/EssentialsServiceProvider.php"
}

@test "all helper files declare strict_types" {
  local helpers=("array.php" "boolean.php" "filesystem.php" "format.php" "math.php" "string.php" "validation.php")

  for helper in "${helpers[@]}"; do
    run grep -q "declare(strict_types=1)" "${PROJECT_ROOT}/src/helpers/${helper}"
    [ "$status" -eq 0 ]
  done
}

@test "all helper files use function_exists guards" {
  local helpers=("array.php" "boolean.php" "filesystem.php" "format.php" "math.php" "string.php" "validation.php")

  for helper in "${helpers[@]}"; do
    run grep -q "function_exists" "${PROJECT_ROOT}/src/helpers/${helper}"
    [ "$status" -eq 0 ]
  done
}

# ============================================================================
# Tests Directory
# ============================================================================

@test "tests/ directory exists" {
  assert_dir_exists "${PROJECT_ROOT}/tests"
}

@test "tests/Unit/ directory exists" {
  assert_dir_exists "${PROJECT_ROOT}/tests/Unit"
}

@test "tests/Unit/HelpersTest.php exists" {
  assert_file_exists "${PROJECT_ROOT}/tests/Unit/HelpersTest.php"
}

@test "tests/Unit/StrMacrosTest.php exists" {
  assert_file_exists "${PROJECT_ROOT}/tests/Unit/StrMacrosTest.php"
}

# ============================================================================
# CI / Tooling
# ============================================================================

@test ".gitlab-ci.yml exists" {
  assert_file_exists "${PROJECT_ROOT}/.gitlab-ci.yml"
}

@test ".gitlab-ci.yml references laravel-dev-tools pipeline" {
  assert_file_contains "${PROJECT_ROOT}/.gitlab-ci.yml" "pipeline-php-package.yml"
}

@test ".gitlab-ci.yml has correct CACHE_KEY" {
  assert_file_contains "${PROJECT_ROOT}/.gitlab-ci.yml" "CACHE_KEY.*laravel-essentials"
}

@test ".gitlab-ci.yml has correct PACKAGIST_PACKAGE" {
  assert_file_contains "${PROJECT_ROOT}/.gitlab-ci.yml" "zairakai/laravel-essentials"
}

@test "Makefile exists" {
  assert_file_exists "${PROJECT_ROOT}/Makefile"
}

@test "phpstan.neon is in config/dev-tools only" {
  assert_file_exists "${PROJECT_ROOT}/config/dev-tools/phpstan.neon"
  [ ! -e "${PROJECT_ROOT}/phpstan.neon" ]
}

@test "config/dev-tools/ directory exists" {
  assert_dir_exists "${PROJECT_ROOT}/config/dev-tools"
}

@test "config/dev-tools/baseline.neon exists" {
  assert_file_exists "${PROJECT_ROOT}/config/dev-tools/baseline.neon"
}

@test "config/dev-tools/ holds the configuration of each tool" {
  local file
  for file in phpinsights.php phpstan.neon phpunit.xml pint.json rector.php .markdownlint.json; do
    assert_file_exists "${PROJECT_ROOT}/config/dev-tools/${file}"
  done
}

@test "config/dev-tools/phpunit.xml writes the junit log and the coverage reports" {
  local element
  for element in 'build/logs/junit.xml' 'build/logs/clover.xml' 'build/logs/cobertura.xml' 'build/coverage'; do
    assert_file_contains "${PROJECT_ROOT}/config/dev-tools/phpunit.xml" "${element}"
  done
}

@test "config/dev-tools/phpinsights.php allows the global helpers of the package" {
  run grep -F "ForbiddenDefineFunctions::class" "${PROJECT_ROOT}/config/dev-tools/phpinsights.php"
  [ "$status" -eq 0 ]
}

@test "the root holds only what the editor or the tool reads: the thin file and the ignore file" {
  local file
  for file in .markdownlint.json .markdownlintignore; do
    assert_file_exists "${PROJECT_ROOT}/${file}"
  done
  [ ! -e "${PROJECT_ROOT}/rector.php" ]
  [ ! -e "${PROJECT_ROOT}/phpinsights.php" ]
  [ ! -e "${PROJECT_ROOT}/phpunit.xml" ]
  [ ! -e "${PROJECT_ROOT}/phpstan.neon" ]
  [ ! -e "${PROJECT_ROOT}/pint.json" ]
  [ ! -e "${PROJECT_ROOT}/config/dev-tools/.markdownlintignore" ]
}

@test "the logo and the banner of the package are in assets/" {
  local file
  for file in icon.png icon.svg banner.png banner.svg; do
    assert_file_exists "${PROJECT_ROOT}/assets/${file}"
  done
}

@test "the git hooks of laravel-dev-tools are active" {
  # The hooks are a setting of the clone of a developer: a checkout of the pipeline has none
  [ -z "${CI:-}" ] || skip "the hooks are a local setting"

  run git -C "${PROJECT_ROOT}" config core.hooksPath
  [ "$status" -eq 0 ]
  [ "$output" = "vendor/zairakai/laravel-dev-tools/stubs/githooks" ]
}
