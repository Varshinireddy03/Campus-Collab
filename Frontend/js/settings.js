// GLOBAL SETTINGS FUNCTIONS ========================

// Load settings from localStorage or return defaults
function loadSettings() {
    return JSON.parse(localStorage.getItem('settings')) || {
        theme: 'light',
        accentColor: 'blue',
        fontSize: 'medium',
        animationsEnabled: true,
        // other default settings...
    };
}

// Save settings to localStorage
function saveSettings(settings) {
    localStorage.setItem('settings', JSON.stringify(settings));
}

// Apply theme settings
function applyTheme(theme) {
    if (theme === 'dark') {
        document.documentElement.style.setProperty('--light', '#212529');
        document.documentElement.style.setProperty('--dark', '#f8f9fa');
        // ... other dark mode variables
    } else {
        document.documentElement.style.setProperty('--light', '#f8f9fa');
        document.documentElement.style.setProperty('--dark', '#212529');
        // ... other light mode variables
    }
}

// Apply accent color
function applyAccentColor(color) {
    // ... color application logic
}

// Apply font size
function applyFontSize(size) {
    // ... font size logic
}

// Apply all settings when page loads
function initializeSettings() {
    const settings = loadSettings();
    applyTheme(settings.theme);
    applyAccentColor(settings.accentColor);
    applyFontSize(settings.fontSize);
    // ... apply other settings
}

// Initialize settings when DOM is loaded
document.addEventListener('DOMContentLoaded', initializeSettings);
window.addEventListener('storage', function(e) {
    if (e.key === 'settings') {
        initializeSettings(); // Re-apply settings when changed in another tab
    }
});