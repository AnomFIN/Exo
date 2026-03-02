<?php
/**
 * TxtDriver – demo-only flat-file storage.
 *
 * LIMITATIONS:
 *   - Stores pages as JSON files under storage/pages/.
 *   - Settings stored in storage/settings.json.
 *   - Admin authentication is NOT supported with this driver.
 *     Admin login requires MySQL.
 */
class TxtDriver implements StorageInterface
{
    private string $pagesDir;
    private string $settingsFile;

    public function __construct(string $storageDir = null)
    {
        $base              = $storageDir ?? dirname(__DIR__, 2) . '/storage';
        $this->pagesDir    = rtrim($base, '/') . '/pages';
        $this->settingsFile = rtrim($base, '/') . '/settings.json';
    }

    // -------------------------------------------------------------------------
    // Pages
    // -------------------------------------------------------------------------

    public function getPage(string $slug): ?array
    {
        $file = $this->pageFile($slug);
        if (!is_file($file)) {
            return null;
        }
        $data = json_decode(file_get_contents($file), true);
        return is_array($data) ? $data : null;
    }

    public function listPages(): array
    {
        $pages = [];
        if (!is_dir($this->pagesDir)) {
            return $pages;
        }
        foreach (glob($this->pagesDir . '/*.json') as $file) {
            $data = json_decode(file_get_contents($file), true);
            if (is_array($data)) {
                $pages[] = $data;
            }
        }
        usort($pages, fn($a, $b) => strcmp($a['title'] ?? '', $b['title'] ?? ''));
        return $pages;
    }

    public function savePage(array $data): bool
    {
        if (!is_dir($this->pagesDir)) {
            if (!mkdir($this->pagesDir, 0755, true) && !is_dir($this->pagesDir)) {
                return false;
            }
        }
        $slug = $data['slug'] ?? '';
        if ($slug === '') {
            return false;
        }
        $file = $this->pageFile($slug);
        return (bool) file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function deletePage(string $slug): bool
    {
        $file = $this->pageFile($slug);
        if (is_file($file)) {
            return unlink($file);
        }
        return false;
    }

    // -------------------------------------------------------------------------
    // Settings
    // -------------------------------------------------------------------------

    public function getSetting(string $key, string $default = ''): string
    {
        $all = $this->loadSettings();
        return $all[$key] ?? $default;
    }

    public function setSetting(string $key, string $value): bool
    {
        $all       = $this->loadSettings();
        $all[$key] = $value;
        return (bool) file_put_contents(
            $this->settingsFile,
            json_encode($all, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function pageFile(string $slug): string
    {
        // Allow only safe slug characters to prevent path traversal
        $safe = preg_replace('/[^a-zA-Z0-9\-_]/', '', $slug);
        return $this->pagesDir . '/' . $safe . '.json';
    }

    private function loadSettings(): array
    {
        if (!is_file($this->settingsFile)) {
            return [];
        }
        $data = json_decode(file_get_contents($this->settingsFile), true);
        return is_array($data) ? $data : [];
    }
}
