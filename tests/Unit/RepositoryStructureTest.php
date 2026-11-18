<?php

declare(strict_types=1);

namespace Zairakai\LaravelEssentials\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Zairakai\LaravelDevTools\Services\ConfigCopies;

/**
 * The files and the contents that must stay, or be corrected with the test that names them. They run against the
 * source tree, not against an installed package. This is the copy of the generic checks of laravel-dev-tools, with the
 * files of this repository.
 */
final class RepositoryStructureTest extends TestCase
{
    /**
     * @return iterable<string, array{string}>
     */
    public static function filesAtTheRoot(): iterable
    {
        foreach ([
            '.editorconfig',
            '.gitattributes',
            '.gitignore',
            '.gitlab-ci.yml',
            '.markdownlint.json',
            '.markdownlintignore',
            'CODE_OF_CONDUCT.md',
            'CONTRIBUTING.md',
            'LICENSE',
            'Makefile',
            'README.md',
            'SECURITY.md',
            'composer.json',
        ] as $file) {
            yield $file => [
                $file,
            ];
        }
    }

    #[Test]
    public function it_allows_the_global_helpers_that_the_package_defines(): void
    {
        $insights = (string) file_get_contents($this->root('config/dev-tools/phpinsights.php'));

        self::assertStringContainsString('ForbiddenDefineFunctions::class', $insights);
    }

    // README, CONTRIBUTING and assets
    // ================

    #[Test]
    public function it_folds_the_examples_of_the_readme_in_details(): void
    {
        $readme = (string) file_get_contents($this->root('README.md'));

        self::assertStringContainsString('<details>', $readme);
        self::assertStringContainsString('<summary>', $readme);
    }

    #[Test]
    public function it_has_no_file_at_the_root_for_rector_and_php_insights(): void
    {
        self::assertFileDoesNotExist($this->root('rector.php'));
        self::assertFileDoesNotExist($this->root('phpinsights.php'));
    }

    // Writing rules
    // ================

    #[Test]
    public function it_has_no_long_dash_in_the_files_of_the_repository(): void
    {
        // The long dashes are written with their bytes: this file does not hold them.
        $em = "\xe2\x80\x94";
        $en = "\xe2\x80\x93";

        $git   = 'git ls-files --cached --others --exclude-standard 2>/dev/null';
        $files = [];

        foreach (explode("\n", (string) shell_exec('cd ' . escapeshellarg($this->root()) . ' && ' . $git)) as $file) {
            if ('' !== $file && is_file($this->root($file)) && 'composer.lock' !== $file) {
                $files[] = $file;
            }
        }

        if ([] === $files) {
            self::markTestSkipped('The list of the files needs git.');
        }

        $found = [];

        foreach ($files as $file) {
            $content = (string) file_get_contents($this->root($file));

            if (str_contains($content, "\0")) {
                continue;
            }

            if (str_contains($content, $em) || str_contains($content, $en)) {
                $found[] = $file;
            }
        }

        self::assertSame([], $found);
    }

    #[Test]
    public function it_has_no_placeholder_left_in_the_governance_files(): void
    {
        foreach ([
            'CONTRIBUTING.md',
            'SECURITY.md',
            'CODE_OF_CONDUCT.md',
        ] as $file) {
            $content = (string) file_get_contents($this->root($file));

            self::assertStringNotContainsString(
                'PACKAGE_',
                $content,
                $file,
            );
            self::assertStringNotContainsString(
                'TODO',
                $content,
                $file,
            );
        }
    }

    #[Test]
    public function it_has_no_template_of_merge_request_or_issue(): void
    {
        self::assertDirectoryDoesNotExist($this->root('.gitlab/merge_request_templates'));
        self::assertDirectoryDoesNotExist($this->root('.gitlab/issue_templates'));
        self::assertDirectoryDoesNotExist($this->root('.github'));
    }

    // Root
    // ================

    #[Test]
    #[DataProvider('filesAtTheRoot')]
    public function it_has_the_files_of_the_repository_at_the_root(string $file): void
    {
        self::assertFileExists($this->root($file));
    }

    #[Test]
    public function it_holds_the_configuration_of_each_tool_in_config_dev_tools(): void
    {
        foreach ([
            'baseline.neon',
            'phpinsights.php',
            'phpstan.neon',
            'phpunit.xml',
            'pint.json',
            'rector.php',
            '.markdownlint.json',
        ] as $file) {
            self::assertFileExists($this->root('config/dev-tools/' . $file));
        }
    }

    #[Test]
    public function it_holds_the_icon_and_the_banner_in_both_formats(): void
    {
        foreach ([
            'icon.png',
            'icon.svg',
            'banner.png',
            'banner.svg',
        ] as $file) {
            self::assertFileExists($this->root('assets/' . $file));
        }
    }

    // Composer
    // ================

    #[Test]
    public function it_holds_the_settings_of_composer_that_the_other_repositories_have(): void
    {
        $composer = json_decode(
            (string) file_get_contents($this->root('composer.json')),
            true,
            512,
            JSON_THROW_ON_ERROR,
        );

        self::assertIsArray($composer);
        self::assertSame(
            [
                'low',
                'medium',
            ],
            $this->setting(
                $composer,
                'config',
                'audit',
                'ignore-severity',
            ),
        );
        self::assertSame(
            '8.4.0',
            $this->setting(
                $composer,
                'config',
                'platform',
                'php',
            ),
        );
        self::assertSame(
            0,
            $this->setting(
                $composer,
                'config',
                'process-timeout',
            ),
        );
        $security = $this->setting(
            $composer,
            'support',
            'security',
        );

        self::assertIsString($security);
        self::assertStringEndsWith('/SECURITY.md', $security);
    }

    #[Test]
    public function it_indents_composer_json_with_two_spaces(): void
    {
        $json = (string) file_get_contents($this->root('composer.json'));

        self::assertStringStartsWith("{\n  \"", $json);
        self::assertStringNotContainsString("\n    \"name\"", $json);
    }

    #[Test]
    public function it_keeps_one_file_only_for_what_no_editor_reads_or_what_the_tool_reads_from_the_root(): void
    {
        foreach (['phpunit.xml', 'phpstan.neon', 'pint.json'] as $file) {
            self::assertFileDoesNotExist($this->root($file), $file . ': no extension of an editor reads it, config/dev-tools/ only');
        }

        self::assertFileDoesNotExist($this->root('config/dev-tools/.markdownlintignore'), 'the ignore file is the one of the root');
    }

    #[Test]
    public function it_keeps_the_baseline_of_the_project_in_phpstan(): void
    {
        $phpstan = (string) file_get_contents($this->root('config/dev-tools/phpstan.neon'));

        self::assertStringContainsString('baseline.neon', $phpstan);
        self::assertFileExists($this->root('config/dev-tools/baseline.neon'));
    }

    #[Test]
    public function it_keeps_the_code_and_the_manifest_in_the_archive(): void
    {
        $attributes = (string) file_get_contents($this->root('.gitattributes'));

        foreach ([
            '/src',
            '/composer.json',
            '/LICENSE',
            '/README.md',
        ] as $entry) {
            self::assertStringNotContainsString($entry . ' export-ignore', $attributes);
        }
    }

    #[Test]
    public function it_keeps_the_published_configuration_out_of_the_gitignore(): void
    {
        $gitignore = (string) file_get_contents($this->root('.gitignore'));

        self::assertStringContainsString('.dev-tools-backup/', $gitignore);
        self::assertStringNotContainsString("\nconfig/dev-tools/\n", $gitignore);
    }

    #[Test]
    public function it_keeps_the_root_and_config_dev_tools_in_agreement(): void
    {
        self::assertSame(
            [],
            ConfigCopies::problems($this->root()),
        );
    }

    // Git
    // ================

    #[Test]
    public function it_leaves_the_repository_files_out_of_the_archive(): void
    {
        $attributes = (string) file_get_contents($this->root('.gitattributes'));

        foreach ([
            '/.gitattributes',
            '/.gitignore',
            '/.gitlab-ci.yml',
            '/.markdownlint.json',
            '/SECURITY.md',
            '/assets',
            '/docs',
            '/tests',
        ] as $entry) {
            self::assertStringContainsString($entry . ' export-ignore', $attributes);
        }
    }

    #[Test]
    public function it_normalizes_the_line_endings_and_keeps_the_markdown_diffs(): void
    {
        $attributes = (string) file_get_contents($this->root('.gitattributes'));

        foreach ([
            '* text=auto eol=lf',
            '*.md diff=markdown',
            '*.png binary',
        ] as $line) {
            self::assertStringContainsString($line, $attributes);
        }
    }

    // Settings that must stay
    // ================

    #[Test]
    public function it_sets_four_spaces_for_php_and_no_line_length_for_markdown(): void
    {
        $editorconfig = (string) file_get_contents($this->root('.editorconfig'));

        self::assertStringContainsString("[*.php]\nindent_size = 4", $editorconfig);

        preg_match(
            '/\[\*\.md\]\n(?<rules>[^\[]*)/',
            $editorconfig,
            $markdown,
        );

        self::assertArrayHasKey('rules', $markdown);
        self::assertStringNotContainsString('max_line_length', $markdown['rules']);
    }

    #[Test]
    public function it_shows_the_icon_in_the_readme_and_not_the_banner(): void
    {
        $readme = (string) file_get_contents($this->root('README.md'));

        self::assertStringContainsString('assets/icon.png', $readme);
        self::assertStringNotContainsString('banner', $readme);
    }

    #[Test]
    public function it_starts_the_gitignore_with_the_standard_block_of_the_handbook(): void
    {
        $gitignore = (string) file_get_contents($this->root('.gitignore'));

        foreach ([
            '# JetBrains (PhpStorm, WebStorm)',
            '# Visual Studio Code and derivatives',
            '# Other editors',
            '# AI assistants',
            'AGENTS.md',
            'CLAUDE.md',
            'GEMINI.md',
            '*.local.md',
        ] as $text) {
            self::assertStringContainsString($text, $gitignore);
        }
    }

    private function root(string $path = ''): string
    {
        return rtrim(__DIR__ . '/../../' . $path, '/');
    }

    /**
     * A value of a decoded file, by its path of keys, or null when a key is missing.
     *
     * @param array<mixed> $data
     */
    private function setting(
        array $data,
        string ...$path,
    ): mixed {
        $value = $data;

        foreach ($path as $key) {
            if (! is_array($value) || ! array_key_exists($key, $value)) {
                return null;
            }

            $value = $value[$key];
        }

        return $value;
    }
}
