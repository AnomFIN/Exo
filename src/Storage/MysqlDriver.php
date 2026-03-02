<?php
/**
 * MysqlDriver – full-featured storage backed by MySQL.
 * Admin authentication is ONLY supported with this driver.
 */
class MysqlDriver implements StorageInterface
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // -------------------------------------------------------------------------
    // Pages
    // -------------------------------------------------------------------------

    public function getPage(string $slug): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT slug, title, html, meta_description, meta_keywords
               FROM pages WHERE slug = ? LIMIT 1'
        );
        $stmt->execute([$slug]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function listPages(): array
    {
        $stmt = $this->db->query(
            'SELECT slug, title, meta_description, meta_keywords FROM pages ORDER BY title ASC'
        );
        return $stmt->fetchAll();
    }

    public function savePage(array $data): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO pages (slug, title, html, meta_description, meta_keywords)
             VALUES (:slug, :title, :html, :meta_description, :meta_keywords)
             ON DUPLICATE KEY UPDATE
               title            = VALUES(title),
               html             = VALUES(html),
               meta_description = VALUES(meta_description),
               meta_keywords    = VALUES(meta_keywords)'
        );
        return $stmt->execute([
            'slug'             => $data['slug']             ?? '',
            'title'            => $data['title']            ?? '',
            'html'             => $data['html']             ?? '',
            'meta_description' => $data['meta_description'] ?? '',
            'meta_keywords'    => $data['meta_keywords']    ?? '',
        ]);
    }

    public function deletePage(string $slug): bool
    {
        $stmt = $this->db->prepare('DELETE FROM pages WHERE slug = ?');
        return $stmt->execute([$slug]);
    }

    // -------------------------------------------------------------------------
    // Settings
    // -------------------------------------------------------------------------

    public function getSetting(string $key, string $default = ''): string
    {
        $stmt = $this->db->prepare(
            'SELECT setting_value FROM settings WHERE setting_key = ? LIMIT 1'
        );
        $stmt->execute([$key]);
        $row = $stmt->fetch();
        return $row ? $row['setting_value'] : $default;
    }

    public function setSetting(string $key, string $value): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO settings (setting_key, setting_value) VALUES (:k, :v)
             ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)'
        );
        return $stmt->execute(['k' => $key, 'v' => $value]);
    }
}
