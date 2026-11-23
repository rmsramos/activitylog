<?php

use Rmsramos\Activitylog\Infolists\Components\TimeLinePropertiesEntry;
use Rmsramos\Activitylog\Infolists\Components\TimeLineTitleEntry;
use Rmsramos\Activitylog\Tests\Fixtures\User;

/*
 * The timeline entries return an HtmlString, which Blade's {{ }} prints as-is.
 * e() is what {{ }} compiles to, so these assertions see exactly what reaches the page.
 */

const HOSTILE_NAME = 'Mallory <i data-probe="causer"></i>';
const ESCAPED_NAME = 'Mallory &lt;i data-probe=&quot;causer&quot;&gt;&lt;/i&gt;';

function propertiesSentence(array $state): string
{
    return e(callProtected(TimeLinePropertiesEntry::make('activityData'), 'modifiedProperties', $state));
}

function titleSentence(array $state): string
{
    return e(callProtected(TimeLineTitleEntry::make('activityData'), 'modifiedTitle', $state));
}

it('escapes the old and new attribute values in the properties sentence', function () {
    $html = propertiesSentence([
        'event'      => 'updated',
        'causer'     => new User(['name' => 'Alice']),
        'properties' => [
            'old'        => ['bio' => '<i data-probe="old"></i>'],
            'attributes' => ['bio' => '<i data-probe="new"></i>'],
        ],
    ]);

    expect($html)
        ->not->toContain('<i data-probe')
        ->toContain('&lt;i data-probe=&quot;old&quot;&gt;')
        ->toContain('&lt;i data-probe=&quot;new&quot;&gt;');
});

it('escapes the causer name in the properties sentence', function () {
    $html = propertiesSentence([
        'event'      => 'updated',
        'causer'     => new User(['name' => HOSTILE_NAME]),
        'properties' => [
            'old'        => ['email' => 'old@example.com'],
            'attributes' => ['email' => 'new@example.com'],
        ],
    ]);

    expect($html)
        ->not->toContain('<i data-probe')
        ->toContain(ESCAPED_NAME);
});

it('escapes the causer name in the title sentence', function () {
    $html = titleSentence([
        'event'       => 'updated',
        'description' => 'updated',
        'subject'     => new User(['name' => 'Alice']),
        'causer'      => new User(['name' => HOSTILE_NAME]),
        'update'      => '2026-01-01 12:00:00',
    ]);

    expect($html)
        ->not->toContain('<i data-probe')
        ->toContain(ESCAPED_NAME);
});

it('escapes the causer name whichever attribute it comes from', function (string $attribute) {
    $html = propertiesSentence([
        'event'      => 'updated',
        'causer'     => new User([$attribute => HOSTILE_NAME]),
        'properties' => [
            'attributes' => ['email' => 'new@example.com'],
        ],
    ]);

    expect($html)
        ->not->toContain('<i data-probe')
        ->toContain(ESCAPED_NAME);
})->with(['name', 'first_name', 'last_name', 'username']);

it('escapes the attribute keys in the properties sentence', function (array $properties) {
    $html = propertiesSentence([
        'event'      => 'updated',
        'causer'     => new User(['name' => 'Alice']),
        'properties' => $properties,
    ]);

    expect($html)
        ->not->toContain('<i data-probe')
        ->toContain('&lt;i data-probe=&quot;key&quot;&gt;');
})->with([
    'changed value'   => [['old' => ['<i data-probe="key"></i>' => 'a'], 'attributes' => ['<i data-probe="key"></i>' => 'b']]],
    'unchanged value' => [['old' => ['<i data-probe="key"></i>' => 'a'], 'attributes' => ['<i data-probe="key"></i>' => 'a']]],
    'only new values' => [['attributes' => ['<i data-probe="key"></i>' => 'b']]],
    'only old values' => [['old' => ['<i data-probe="key"></i>' => 'a']]],
]);

it('escapes an event that has no translation in the properties sentence', function () {
    $html = propertiesSentence([
        'event'      => '<i data-probe="event"></i>',
        'causer'     => new User(['name' => 'Alice']),
        'properties' => [
            'attributes' => ['email' => 'new@example.com'],
        ],
    ]);

    expect($html)
        ->not->toContain('<i data-probe')
        ->toContain('&lt;i data-probe=&quot;event&quot;&gt;');
});

it('escapes an event that has no translation in the title sentence', function () {
    $html = titleSentence([
        'event'       => '<i data-probe="event"></i>',
        'description' => '<i data-probe="event"></i>',
        'subject'     => new User(['name' => 'Alice']),
        'causer'      => new User(['name' => 'Alice']),
        'update'      => '2026-01-01 12:00:00',
    ]);

    expect($html)
        ->not->toContain('<i data-probe')
        ->toContain('&lt;i data-probe=&quot;event&quot;&gt;');
});

it('keeps the markup that comes from the translation strings', function () {
    $html = titleSentence([
        'event'       => 'updated',
        'description' => 'updated',
        'subject'     => new User(['name' => 'Alice']),
        'causer'      => new User(['name' => 'Alice & Bob']),
        'update'      => '2026-01-01 12:00:00',
    ]);

    expect($html)->toContain('<strong>Alice &amp; Bob</strong>');
});
