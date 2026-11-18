#!/usr/bin/env bats
#
# Integration Tests: Composer Scripts
#
# Validates that the dev tooling environment is correctly installed
# and that key scripts from laravel-dev-tools are available and executable.
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
# Vendor / Dependencies
# ============================================================================

@test "vendor/ directory exists (composer install was run)" {
  assert_dir_exists "${PROJECT_ROOT}/vendor"
}

@test "vendor/zairakai/laravel-dev-tools is installed" {
  assert_dir_exists "${PROJECT_ROOT}/vendor/zairakai/laravel-dev-tools"
}

@test "vendor/phpunit/phpunit is installed" {
  assert_dir_exists "${PROJECT_ROOT}/vendor/phpunit/phpunit"
}

@test "vendor/phpstan/phpstan is installed" {
  assert_dir_exists "${PROJECT_ROOT}/vendor/phpstan/phpstan"
}

@test "vendor/laravel/pint is installed" {
  assert_dir_exists "${PROJECT_ROOT}/vendor/laravel/pint"
}

# ============================================================================
# laravel-dev-tools Scripts
# ============================================================================

@test "setup-package.sh script exists and is executable" {
  local script="${PROJECT_ROOT}/vendor/zairakai/laravel-dev-tools/scripts/setup-package.sh"

  assert_file_exists "$script"
  [ -x "$script" ]
}

@test "phpstan.sh script exists and is executable" {
  local script="${PROJECT_ROOT}/vendor/zairakai/laravel-dev-tools/scripts/phpstan.sh"

  assert_file_exists "$script"
  [ -x "$script" ]
}

@test "cs-check.sh script exists and is executable" {
  local script="${PROJECT_ROOT}/vendor/zairakai/laravel-dev-tools/scripts/cs-check.sh"

  assert_file_exists "$script"
  [ -x "$script" ]
}

@test "test.sh script exists and is executable" {
  local script="${PROJECT_ROOT}/vendor/zairakai/laravel-dev-tools/scripts/test.sh"

  assert_file_exists "$script"
  [ -x "$script" ]
}

@test "rector-check.sh script exists and is executable" {
  local script="${PROJECT_ROOT}/vendor/zairakai/laravel-dev-tools/scripts/rector-check.sh"

  assert_file_exists "$script"
  [ -x "$script" ]
}

@test "insights.sh script exists and is executable" {
  local script="${PROJECT_ROOT}/vendor/zairakai/laravel-dev-tools/scripts/insights.sh"

  assert_file_exists "$script"
  [ -x "$script" ]
}

# ============================================================================
# PHP Environment
# ============================================================================

@test "php binary is available" {
  run command -v php

  [ "$status" -eq 0 ]
}

@test "php version is 8.4 or higher" {
  run php -r "echo PHP_VERSION_ID >= 80400 ? 'yes' : 'no';"

  [ "$output" = "yes" ]
}

@test "phpunit binary is available via vendor" {
  run command -v "${PROJECT_ROOT}/vendor/bin/phpunit"

  [ "$status" -eq 0 ] || assert_file_exists "${PROJECT_ROOT}/vendor/bin/phpunit"
}

@test "phpstan binary is available via vendor" {
  assert_file_exists "${PROJECT_ROOT}/vendor/bin/phpstan"
}

@test "pint binary is available via vendor" {
  assert_file_exists "${PROJECT_ROOT}/vendor/bin/pint"
}

# ============================================================================
# Generated Config Files (by setup-package.sh)
# ============================================================================

@test "Makefile includes dev-tools targets" {
  assert_file_contains "${PROJECT_ROOT}/Makefile" "include"
}

@test "config/dev-tools/phpstan.neon includes a config source" {
  assert_file_contains "${PROJECT_ROOT}/config/dev-tools/phpstan.neon" "includes"
}

# Published files at work
# ================

@test "make help runs and names the project" {
  # The image of the pipeline has no make: the target is checked on a machine that has it
  command -v make > /dev/null 2>&1 || skip "make is not installed"

  run make -C "${PROJECT_ROOT}" help
  [ "$status" -eq 0 ]
  [[ "$output" == *"Available Commands"* ]]
}

@test "composer.json defines every script that the package publishes" {
  run php -r '
    $published = json_decode(file_get_contents($argv[1]), true);
    $own = json_decode(file_get_contents($argv[2]), true)["scripts"] ?? [];
    $missing = array_diff(array_keys($published), array_keys($own));
    echo implode(", ", $missing);
    exit($missing === [] ? 0 : 1);
  ' "${PROJECT_ROOT}/vendor/zairakai/laravel-dev-tools/stubs/composer-scripts.json" "${PROJECT_ROOT}/composer.json"
  [ "$status" -eq 0 ]
}

@test "config-check finds every file where its tool reads it" {
  run bash "${PROJECT_ROOT}/vendor/zairakai/laravel-dev-tools/scripts/check-config-copies.sh"

  [ "$status" -eq 0 ]
}

@test ".gitlab-ci.yml follows the installed version of laravel-dev-tools" {
  local version
  version="$(php -r '
    $lock = json_decode(file_get_contents($argv[1]), true);
    foreach (array_merge($lock["packages"], $lock["packages-dev"]) as $package) {
      if ($package["name"] === "zairakai/laravel-dev-tools") {
        echo $package["version"];
      }
    }
  ' "${PROJECT_ROOT}/composer.lock")"
  [ -n "$version" ]
  assert_file_contains "${PROJECT_ROOT}/.gitlab-ci.yml" "ref: ${version}"
}
