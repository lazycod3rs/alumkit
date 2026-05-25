---
name: skeleton-development
description: "Use this skill when changing this Laravel package skeleton repository itself: placeholders, configure flow, starter defaults, root guidance, local skills, temporary phase tests, dist hygiene, or scaffold-wide conventions. Do not use for ordinary package feature work after a package has been configured."
license: MIT
metadata:
  author: laravel
---

# Skeleton Development

## Primary Goal

Evolve the starter kit without making it less useful for future package authors.

## Workflow

1. Preserve generic placeholders such as `:author_name`, `:package_name`, `:vendor_slug`, and `:package_slug` until the configure flow replaces them.
2. Keep starter guidance minimal and split skeleton-maintenance rules from package-author rules.
3. Use temporary phase scaffold tests when proving repository shape, file parity, or generated scaffolding; delete those tests before final validation.
4. Maintain package guidance only in `AGENTS.md` and local skills only in `.agents/skills`; `configure.php` copies them to `CLAUDE.md` and `.claude/skills` during package configuration.
5. Keep `configure.php` feature and tool pruning maps aligned with the service provider, Composer metadata, README sections, docs, AI guidance, skills, and publishable files.
6. Keep development-time authoring files out of Composer dist archives with `.gitattributes` when they are not runtime package files.

## References

- `AGENTS.md`
- Generated `CLAUDE.md`
- `.agents/skills/`
- Generated `.claude/skills/`
- `configure.php`
- `.gitattributes`
- `PLAN.md`, `REQS.md`, and `phases/` when present

## Examples

- Add or rename local guidance under `AGENTS.md` or `.agents/skills`, then update front matter names, root guidance, and any scaffold consistency checks.
- Add temporary Phase tests to lock down generated file shape, run them to prove the change, then delete them before the final `composer test`.
- Update placeholder-sensitive docs by preserving delete-fence content that should disappear after configuration.
- When adding a selectable package feature, add both the starter files and the matching configure pruning behavior so package authors can opt out cleanly.

## Anti-Patterns

- Leaving temporary phase tests in the shipped starter suite.
- Putting skeleton-maintenance rules into package-facing skills.
- Replacing placeholders with one real package name in starter files.
- Adding runtime dependencies for repository-maintenance convenience.
