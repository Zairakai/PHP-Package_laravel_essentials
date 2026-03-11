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

@test "composer.json requires php ^8.3 or ^8.4" {
    run grep -q '"php":' "${PROJECT_ROOT}/composer.json"

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

@test "phpstan.neon exists" {
    assert_file_exists "${PROJECT_ROOT}/phpstan.neon"
}

@test "config/dev-tools/ directory exists" {
    assert_dir_exists "${PROJECT_ROOT}/config/dev-tools"
}

@test "config/dev-tools/insights.php exists" {
    assert_file_exists "${PROJECT_ROOT}/config/dev-tools/insights.php"
}
