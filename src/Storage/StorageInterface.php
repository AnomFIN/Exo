<?php
/**
 * StorageInterface – contract for all storage drivers.
 */
interface StorageInterface
{
    /** Return a page row array or null. Keys: slug, title, html, meta_description, meta_keywords */
    public function getPage(string $slug): ?array;

    /** Return all pages as array of rows. */
    public function listPages(): array;

    /** Create or update a page. Returns true on success. */
    public function savePage(array $data): bool;

    /** Delete a page by slug. Returns true on success. */
    public function deletePage(string $slug): bool;

    /** Get a site setting value. */
    public function getSetting(string $key, string $default = ''): string;

    /** Set a site setting value. */
    public function setSetting(string $key, string $value): bool;
}
