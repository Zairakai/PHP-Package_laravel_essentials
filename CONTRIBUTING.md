# Contributing

> This project follows the [Zairakai Global Contributing Guide][handbook-contributing].  
> Please read it before contributing. The sections below document project-specific workflow.

---

## Development Workflow

| Step | Command / Action | Description |
| :--- | :--- | :--- |
| **1. Install** | `composer install` | Install dependencies and set up git hooks. |
| **2. Branch** | `git checkout -b feature/#TICKET-name` | Create a feature branch from `main`. |
| **3. Code** | *(your IDE)* | Implement your changes following quality standards. |
| **4. Quality** | `make quality` | Run the full quality gate. |
| **5. Test** | `make test` | Ensure all tests are passing. |
| **6. Commit** | `git commit -m "type(scope): #TICKET subject"` | Use [Conventional Commits][git-rules] format. |
| **7. Push** | `git push origin feature/#TICKET-name` | Push and open a Merge Request to `main`. |

---

## Types of Contributions

| Type | Guidelines |
| :--- | :--- |
| **🐛 Bug Reports** | Use the issue template. Include minimal reproduction steps, expected vs actual behavior, and environment details. |
| **✨ Feature Requests** | Describe the use case and problem solved. Helpers must not duplicate Laravel core. Macros must not duplicate `Str` class. |
| **🔧 Helpers** | For `src/helpers/`. Wrap in `if (! function_exists())`. Follow existing category files (array, boolean, format, math, string, validation). |
| **🧩 Str Macros** | For `src/Macros/StrMacros.php`. Register both `Str::` and `Stringable::` versions. Use underlying helper functions. |

---

## Quality Targets

| Command | Tool | Description |
| :--- | :--- | :--- |
| `make quality` | All | Full static analysis and formatting gate. |
| `make analyse` | PHPStan | PHP static analysis (Level Max). |
| `make cs` | Pint | Check code style. |
| `make cs:fix` | Pint | Fix code style automatically. |
| `make test` | PHPUnit | Run unit tests with coverage. |
| `make markdownlint` | Markdownlint | Validate Markdown documentation. |

---

[handbook-contributing]: https://gitlab.com/zairakai/handbook/-/blob/main/CONTRIBUTING.md
[git-rules]: https://gitlab.com/zairakai/handbook/-/blob/main/policies/git-rules.md
