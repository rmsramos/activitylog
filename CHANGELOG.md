# Changelog

All notable changes to `rmsramos/activitylog` are documented here. Format based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), versioning follows [SemVer](https://semver.org/).

This branch (`3.x`) tracks the Filament v5 compatibility line.

## [Unreleased]

## [4.0.4] - 2026-08-01

### Fixed
- `formatDateValues()` now detects date values via the subject model's actual attribute casts instead of a digit-count heuristic, fully resolving #75 (previously only mitigated) — verified locally against a seeded `decimal(15,2)` price field
- Code style badge/link in README pointed to the deleted `main` branch, now points to `3.x`

## [4.0.3] - 2026-07-31

### Fixed
- README screenshots pointed to the deleted `main` branch (`raw.githubusercontent.com/.../main/arts/...`), now point to `3.x`

## [4.0.2] - 2026-07-31

### Fixed
- Ambiguous SQL `id` column when using `hasManyThrough`/similar relations with `withRelations()` (#99)
- Subject model failing to load in the timeline when it has a restrictive global scope (#111)
- `formatDateValues()` heuristic misfiring on large decimal/numeric values, rendering them as dates (#75, partial mitigation — narrowed to exact 10/13-digit unix timestamp lengths)
- Grid layout and empty-state placeholder in the Timeline modal not rendering (removed Filament v3 Blade components) (#116)
- Missing German `infolists.php` translation, plus gaps found across 10 other languages (#123)
- `ActivitylogPlugin` getters returning `null` against a non-nullable type when the published config is stale/missing a key (#124)
- Incorrect boolean-to-date type coercion in `formatDateValues()` (#120)

## [4.0.1] - 2026-07-31

### Chore
- Add PHPStan (larastan) config with baseline for existing errors
- Pin GitHub Actions to commit SHA, add `SECURITY.md` and Dependabot config

### Docs
- Add branch/Filament version support table to README

### i18n
- Complete Latvian translations (#128) — @denissceluiko
- Fill translation gaps across ar, de, fa, fr, he, id, it, nl, pl, pt_PT, tr (missing `notifications.php`/`infolists.php` and several keys, parity with the English base)

## [4.0.0] - 2026-07-31

### Breaking
- Filament v5 compatibility, verified against the real installed `filament/filament` v5.7.5
- Same v3→v4 API migration as `v3.0.0` (Schemas unification, Actions unification, `StaticAction` removed, `->recordActions()`, `Split` → `Flex`) — nothing further changed between Filament v4 and v5 for this package's code
- Renamed `Resources/ActivitylogResource/` to `Resources/Activitylog/` to match the Filament v4/v5 convention
- Restored the "changes" section (property diff view + restore/edit actions), which had been silently dropped
- Fixed several broken bare `use` imports left over from the incomplete migration
- Replaced removed Filament v3 Blade components (`x-filament::grid`, `x-filament-infolists::entries.placeholder`) with v4/v5-native equivalents
- `IconEntrySize` moved to `Filament\Support\Enums\IconSize`
- `filament/filament` moved to `require` (was `require-dev`), `illuminate/contracts` requirement removed

### Docs
- Document the `@source` directive required in your panel's `theme.css` for Tailwind v4 to pick up this package's Blade views

## [2.0.0] - 2025-08-16

### Breaking
- Classes made more extendable: private properties/methods changed to protected (#113) — @Muffinman

### Added
- 'log name' filter on ActivitylogResource (#100) — @morris14
- Handle BelongsToMany relations in ActionContent (#114) — @paulohenriquesg
- Support for translating activity log key names (#115) — @paulohenriquesg

## [1.0.13] - 2025-07-06
- Update Spanish translations, add new keys (#109) — @edeoliv
- Fix README (#110) — @rmsramos

## [1.0.12] - 2025-07-04
- Soft delete support (#108) — @rmsramos

## [1.0.11] - 2025-07-03
- Restore action + date/datetime/icon customization (#71) — @phpust
- Fix and code style (#106) — @rmsramos
- Fix dark mode classes (#94) — @webard
- Activity model configuration (#96) — @othyn
- Fix `modifyTitleUsing()` incorrect state (#105) — @Muffinman
- Fix Arabic timeline (#98) — @patrickwebsdev
- Custom activity title name in model (#86) — @patrickwebsdev
- Dutch translations (#91) — @makkinga
- Fix code style (#107) — @rmsramos

## [1.0.10] - 2025-05-02
- Latvian translations (#90) — @HungryBus
- German translation, bugfix (#92) — @CyberLine

## [1.0.9] - 2025-03-31
- Support for Laravel 12.* (#87) — @milon

## [1.0.8] - 2025-03-14
- Minor fixes.

## [1.0.7] - 2024-09-30
- Fix CSS not loading correctly (#54) — @Orphail
- Portuguese language support (#53) — @kidiatoliny
- Italian translations (#55) — @marcogermani87

## [1.0.6] - 2024-09-22
- Fix date format (#52) — @rmsramos

## [1.0.5] - 2024-09-22
- Fix documentation (#39) — @rmsramos
- Fix Simple action extension class (#42) — @GeoSot
- Fix dates format, issue #46 (#47) — @Orphail
- Enable/disable navigation item (#45) — @Orphail
- Indonesian language (#49) — @adereksisusanto
- Remove unnecessary/typo single quote (#43) — @alexpgates
- Lazy loading activitylog.css to prevent conflicts/overload (#48) — @abdulmejid-assistentry
- Fix code style (#51) — @rmsramos

## [1.0.4] - 2024-07-23
- Persian language (#36) — @alisalehi1380
- Arabic translation (#34) — @KaramNassar
- Fix typo (#32) — @GeoSot
- `defaultSort` feature (#37) — @sugin223pl
- Introduce simple-action (#33) — @GeoSot

## [1.0.3] - 2024-06-19
- Updated issue templates (#26, #27) — @rmsramos
- French language (#28) — @tbcy
- Customization closures (#29) — @ainesophaur
- Fixes and documentation (#30) — @rmsramos

## [1.0.2] - 2024-06-12
- Fix documentation (#19) — @rmsramos
- Fix configuration file collision and `getEventColumnComponent` (#23) — @rmsramos

## [1.0.1] - 2024-06-04
- Update README (#16, #17) — @rmsramos
- Spanish translations (#14) — @edeoliv
- Fix code styling (#18) — @rmsramos

## [1.0.0] - 2024-06-04
- Add Timeline Action.

## [0.2.0] - 2024-05-30
## [0.1.5] - 2024-05-15
## [0.1.4] - 2024-04-06
## [0.1.3] - 2023-09-09
## [0.1.2] - 2023-09-09
## [0.1.1] - 2023-09-09
## [0.1.0] - 2023-09-08
- Initial releases.
