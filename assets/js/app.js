/**
 * LMS SMK Kesehatan SDM Sumedang
 * Main JavaScript File
 */

// ============================================
// THEME MANAGEMENT
// ============================================

class ThemeManager {
    constructor() {
        this.themeKey = 'lms_theme';
        this.init();
    }
    
    init() {
        // Load saved theme or default to light mode
        const savedTheme = localStorage.getItem(this.themeKey) || 'light';
        this.setTheme(savedTheme);
        
        // Setup theme toggle listener
        this.setupThemeToggle();
    }
    
    setTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem(this.themeKey, theme);
    }
    
    toggle() {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        this.setTheme(newTheme);
    }
    
    setupThemeToggle() {
        const toggle = document.querySelector('.theme-toggle');
        if (toggle) {
            toggle.addEventListener('click', () => this.toggle());
        }
    }
}

// ============================================
// SIDEBAR MANAGEMENT
// ============================================

class SidebarManager {
    constructor() {
        this.sidebar = document.querySelector('.sidebar');
        this.toggleBtn = document.querySelector('.mobile-menu-toggle');
        this.overlay = document.querySelector('.sidebar-overlay');
        this.init();
    }
    
    init() {
        if (this.toggleBtn) {
            this.toggleBtn.addEventListener('click', () => this.toggle());
        }
        
        if (this.overlay) {
            this.overlay.addEventListener('click', () => this.close());
        }
        
        // Close sidebar on route change (for SPA)
        window.addEventListener('popstate', () => this.close());
    }
    
    toggle() {
        this.sidebar?.classList.toggle('active');
        this.overlay?.classList.toggle('active');
    }
    
    close() {
        this.sidebar?.classList.remove('active');
        this.overlay?.classList.remove('active');
    }
    
    open() {
        this.sidebar?.classList.add('active');
        this.overlay?.classList.add('active');
    }
}

// ============================================
// NOTIFICATION SYSTEM
// ============================================

class NotificationManager {
    constructor() {
        this.container = document.querySelector('.notification-container') || this.createContainer();
    }
    
    createContainer() {
        const container = document.createElement('div');
        container.className = 'notification-container';
        container.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        `;
        document.body.appendChild(container);
        return container;
    }
    
    show(message, type = 'info', duration = 5000) {
        const notification = this.createNotification(message, type);
        this.container.appendChild(notification);
        
        // Auto remove after duration
        setTimeout(() => {
            this.remove(notification);
        }, duration);
        
        return notification;
    }
    
    createNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} animate-slideIn`;
        notification.innerHTML = `
            <span>${this.escapeHtml(message)}</span>
            <button onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;font-size:1.2rem;">&times;</button>
        `;
        notification.style.cssText = `
            min-width: 300px;
            max-width: 400px;
            justify-content: space-between;
        `;
        return notification;
    }
    
    remove(notification) {
        notification.style.animation = 'fadeIn 0.3s reverse forwards';
        setTimeout(() => notification.remove(), 300);
    }
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    success(message) {
        return this.show(message, 'success');
    }
    
    error(message) {
        return this.show(message, 'danger');
    }
    
    warning(message) {
        return this.show(message, 'warning');
    }
    
    info(message) {
        return this.show(message, 'info');
    }
}

// ============================================
// MODAL SYSTEM
// ============================================

class ModalManager {
    constructor() {
        this.modals = new Map();
    }
    
    create(id, options = {}) {
        const modal = document.createElement('div');
        modal.className = 'modal-overlay';
        modal.id = id;
        modal.innerHTML = `
            <div class="modal-content clay-card" style="max-width: ${options.width || '500px'}; margin: 50px auto;">
                <div class="modal-header d-flex justify-between align-center mb-3">
                    <h3 class="modal-title">${options.title || ''}</h3>
                    <button class="modal-close clay-btn clay-btn-sm">&times;</button>
                </div>
                <div class="modal-body"></div>
                ${options.footer ? `<div class="modal-footer">${options.footer}</div>` : ''}
            </div>
        `;
        modal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9998;
            overflow-y: auto;
        `;
        
        document.body.appendChild(modal);
        this.modals.set(id, modal);
        
        // Close button functionality
        modal.querySelector('.modal-close').addEventListener('click', () => this.close(id));
        modal.addEventListener('click', (e) => {
            if (e.target === modal) this.close(id);
        });
        
        return modal;
    }
    
    open(id, content = '') {
        const modal = this.modals.get(id);
        if (!modal) return;
        
        if (content) {
            modal.querySelector('.modal-body').innerHTML = content;
        }
        
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    close(id) {
        const modal = this.modals.get(id);
        if (!modal) return;
        
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
    
    setContent(id, content) {
        const modal = this.modals.get(id);
        if (modal) {
            modal.querySelector('.modal-body').innerHTML = content;
        }
    }
}

// ============================================
// FORM VALIDATION
// ============================================

class FormValidator {
    constructor(form) {
        this.form = typeof form === 'string' ? document.querySelector(form) : form;
        this.errors = new Map();
        this.init();
    }
    
    init() {
        if (!this.form) return;
        
        this.form.addEventListener('submit', (e) => {
            e.preventDefault();
            if (this.validate()) {
                this.form.dispatchEvent(new CustomEvent('valid'));
            }
        });
        
        // Real-time validation
        const inputs = this.form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('blur', () => this.validateField(input));
            input.addEventListener('input', () => this.clearError(input));
        });
    }
    
    validate() {
        this.errors.clear();
        let isValid = true;
        
        const inputs = this.form.querySelectorAll('input[required], textarea[required], select[required]');
        inputs.forEach(input => {
            if (!this.validateField(input)) {
                isValid = false;
            }
        });
        
        return isValid;
    }
    
    validateField(input) {
        const value = input.value.trim();
        const name = input.name || input.id;
        
        // Required validation
        if (input.required && !value) {
            this.setError(input, `${this.getLabel(name)} harus diisi`);
            return false;
        }
        
        // Email validation
        if (input.type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                this.setError(input, 'Format email tidak valid');
                return false;
            }
        }
        
        // Min length validation
        if (input.minLength > 0 && value.length < input.minLength) {
            this.setError(input, `Minimal ${input.minLength} karakter`);
            return false;
        }
        
        // Max length validation
        if (input.maxLength > 0 && value.length > input.maxLength) {
            this.setError(input, `Maksimal ${input.maxLength} karakter`);
            return false;
        }
        
        // Pattern validation
        if (input.pattern && value) {
            const regex = new RegExp(input.pattern);
            if (!regex.test(value)) {
                this.setError(input, input.title || 'Format tidak valid');
                return false;
            }
        }
        
        this.clearError(input);
        return true;
    }
    
    setError(input, message) {
        const errorId = `error-${input.name || input.id}`;
        let errorEl = document.getElementById(errorId);
        
        if (!errorEl) {
            errorEl = document.createElement('div');
            errorEl.id = errorId;
            errorEl.className = 'text-danger mt-1';
            errorEl.style.fontSize = '0.875rem';
            input.parentNode.appendChild(errorEl);
        }
        
        errorEl.textContent = message;
        input.classList.add('error');
        this.errors.set(input.name || input.id, message);
    }
    
    clearError(input) {
        const errorId = `error-${input.name || input.id}`;
        const errorEl = document.getElementById(errorId);
        
        if (errorEl) {
            errorEl.remove();
        }
        
        input.classList.remove('error');
        this.errors.delete(input.name || input.id);
    }
    
    getLabel(name) {
        const label = this.form.querySelector(`label[for="${name}"]`);
        return label ? label.textContent.replace(':', '').trim() : name;
    }
    
    getErrors() {
        return Object.fromEntries(this.errors);
    }
}

// ============================================
// AJAX HELPER
// ============================================

class AjaxHelper {
    static async request(url, options = {}) {
        const defaultOptions = {
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        };
        
        const config = { ...defaultOptions, ...options };
        
        try {
            const response = await fetch(url, config);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            console.error('AJAX request failed:', error);
            throw error;
        }
    }
    
    static async get(url, options = {}) {
        return this.request(url, { ...options, method: 'GET' });
    }
    
    static async post(url, data, options = {}) {
        return this.request(url, {
            ...options,
            method: 'POST',
            body: JSON.stringify(data)
        });
    }
    
    static async put(url, data, options = {}) {
        return this.request(url, {
            ...options,
            method: 'PUT',
            body: JSON.stringify(data)
        });
    }
    
    static async delete(url, options = {}) {
        return this.request(url, { ...options, method: 'DELETE' });
    }
}

// ============================================
// DATE/TIME UTILITIES
// ============================================

class DateUtils {
    static format(date, format = 'DD/MM/YYYY') {
        const d = new Date(date);
        const day = String(d.getDate()).padStart(2, '0');
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const year = d.getFullYear();
        const hours = String(d.getHours()).padStart(2, '0');
        const minutes = String(d.getMinutes()).padStart(2, '0');
        
        return format
            .replace('DD', day)
            .replace('MM', month)
            .replace('YYYY', year)
            .replace('HH', hours)
            .replace('mm', minutes);
    }
    
    static formatDateIndonesian(date) {
        const months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        
        const d = new Date(date);
        return `${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
    }
    
    static timeAgo(date) {
        const seconds = Math.floor((new Date() - new Date(date)) / 1000);
        
        const intervals = {
            tahun: 31536000,
            bulan: 2592000,
            minggu: 604800,
            hari: 86400,
            jam: 3600,
            menit: 60
        };
        
        for (const [unit, secondsInUnit] of Object.entries(intervals)) {
            const interval = Math.floor(seconds / secondsInUnit);
            if (interval >= 1) {
                return `${interval} ${unit} yang lalu`;
            }
        }
        
        return 'Baru saja';
    }
    
    static isToday(date) {
        const today = new Date();
        const d = new Date(date);
        return d.toDateString() === today.toDateString();
    }
}

// ============================================
// INITIALIZATION
// ============================================

document.addEventListener('DOMContentLoaded', () => {
    // Initialize managers
    window.themeManager = new ThemeManager();
    window.sidebarManager = new SidebarManager();
    window.notifications = new NotificationManager();
    window.modalManager = new ModalManager();
    
    // Auto-initialize form validators
    document.querySelectorAll('form[data-validate]').forEach(form => {
        new FormValidator(form);
    });
    
    // Auto-dismiss alerts
    document.querySelectorAll('.alert[data-dismiss]').forEach(alert => {
        const timeout = parseInt(alert.dataset.dismiss) || 5000;
        setTimeout(() => {
            alert.style.animation = 'fadeIn 0.3s reverse forwards';
            setTimeout(() => alert.remove(), 300);
        }, timeout);
    });
    
    // Confirm dialogs for delete buttons
    document.querySelectorAll('[data-confirm]').forEach(button => {
        button.addEventListener('click', (e) => {
            const message = button.dataset.confirm || 'Apakah Anda yakin?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
    
    // Add loaded class to body for animations
    document.body.classList.add('loaded');
    
    console.log('LMS SMK Kesehatan SDM Sumedang initialized');
});

// ============================================
// GLOBAL HELPER FUNCTIONS
// ============================================

function $(selector) {
    return document.querySelector(selector);
}

function $$(selector) {
    return document.querySelectorAll(selector);
}

function showToast(message, type = 'info') {
    window.notifications.show(message, type);
}

function showModal(id, content, title) {
    if (!window.modalManager.modals.has(id)) {
        window.modalManager.create(id, { title });
    }
    window.modalManager.open(id, content);
}

function formatDate(date, format) {
    return DateUtils.format(date, format);
}

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        ThemeManager,
        SidebarManager,
        NotificationManager,
        ModalManager,
        FormValidator,
        AjaxHelper,
        DateUtils
    };
}
