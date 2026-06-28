<?php

namespace App\Managers;

class ThemeManager
{
    public function __construct(private SettingsManager $settings) {}

    public function generateCss(): string
    {
        $primary   = $this->settings->primaryColor();
        $secondary = $this->settings->secondaryColor();
        $sidebar   = $this->settings->sidebarColor();

        // Derive darker/lighter shades
        $primaryDark  = $this->darken($primary, 15);
        $primaryLight = $this->lighten($primary, 45);

        return ":root {
    --bs-primary: {$primary};
    --bs-primary-rgb: {$this->hexToRgb($primary)};
    --app-primary: {$primary};
    --app-primary-dark: {$primaryDark};
    --app-primary-light: {$primaryLight};
    --app-secondary: {$secondary};
    --app-sidebar-bg: {$sidebar};
    --app-sidebar-text: #e2e8f0;
    --app-sidebar-hover: rgba(255,255,255,0.08);
    --app-sidebar-active-bg: {$primary};
}
.btn-primary { background-color: {$primary}; border-color: {$primary}; }
.btn-primary:hover { background-color: {$primaryDark}; border-color: {$primaryDark}; }
.btn-outline-primary { color: {$primary}; border-color: {$primary}; }
.btn-outline-primary:hover { background-color: {$primary}; border-color: {$primary}; color:#fff; }
.text-primary { color: {$primary} !important; }
.bg-primary { background-color: {$primary} !important; }
.border-primary { border-color: {$primary} !important; }
.badge.bg-primary { background-color: {$primary} !important; }
.nav-link.active { background-color: {$primary} !important; }
.sidebar { background: {$sidebar}; }
.form-control:focus, .form-select:focus { border-color: {$primary}; box-shadow: 0 0 0 .2rem {$primaryLight}40; }
.stat-value { color: {$primary}; }
a { color: {$primary}; }
a:hover { color: {$primaryDark}; }";
    }

    private function darken(string $hex, int $percent): string
    {
        [$r, $g, $b] = $this->hexToRgbArray($hex);
        $factor = 1 - $percent / 100;
        return sprintf('#%02x%02x%02x',
            max(0, (int) ($r * $factor)),
            max(0, (int) ($g * $factor)),
            max(0, (int) ($b * $factor))
        );
    }

    private function lighten(string $hex, int $percent): string
    {
        [$r, $g, $b] = $this->hexToRgbArray($hex);
        $factor = $percent / 100;
        return sprintf('#%02x%02x%02x',
            min(255, (int) ($r + (255 - $r) * $factor)),
            min(255, (int) ($g + (255 - $g) * $factor)),
            min(255, (int) ($b + (255 - $b) * $factor))
        );
    }

    private function hexToRgbArray(string $hex): array
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        return [hexdec(substr($hex,0,2)), hexdec(substr($hex,2,2)), hexdec(substr($hex,4,2))];
    }

    public function hexToRgb(string $hex): string
    {
        return implode(',', $this->hexToRgbArray($hex));
    }
}
