/**
 * Main JavaScript - LMS SMK Kesehatan SDM Sumedang
 * Theme Toggle, Sidebar, and Common Functions
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    initThemeToggle();
    initSidebar();
    initNotifications();
    initFormValidation();
    initFileUpload();
    initAutoSave();
});

/**
 * Theme Toggle (Light/Dark Mode)
 */
function initThemeToggle() {
    const themeToggle = document.querySelector('.theme-toggle');
    const htmlElement = document.documentElement;
    
    // Check for saved theme preference or default to light mode
    const savedTheme = localStorage.getItem('theme') || 'light';
    htmlElement.setAttribute('data-theme', savedTheme);
    
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            const currentTheme = htmlElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            htmlElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            // Add animation class
            document.body.classList.add('theme-changing');
            setTimeout(() => {
                document.body.classList.remove('theme-changing');
            }, 300);
        });
    }
}

/**
 * Sidebar Toggle (Mobile)
 */
function initSidebar() {
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    const mainContent = document.querySelector('.main-content');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            
            if (overlay) {
                overlay.classList.toggle('active');
            }
        });
    }
    
    if (overlay) {
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });
    }
    
    // Close sidebar on window resize (desktop)
    window.addEventListener('resize', function() {
        if (window.innerWidth > 1024) {
            sidebar?.classList.remove('active');
            overlay?.classList.remove('active');
        }
    });
}

/**
 * Notifications System
 */
function initNotifications() {
    // Auto-hide notifications after 5 seconds
    const alerts = document.querySelectorAll('.alert-auto-dismiss');
    
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            
            setTimeout(function() {
                alert.remove();
            }, 300);
        }, 5000);
    });
}

/**
 * Form Validation
 */
function initFormValidation() {
    const forms = document.querySelectorAll('.needs-validation');
    
    forms.forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        });
    });
}

/**
 * File Upload Preview
 */
function initFileUpload() {
    const fileInputs = document.querySelectorAll('.file-input-preview');
    
    fileInputs.forEach(function(input) {
        input.addEventListener('change', function() {
            const file = this.files[0];
            const previewContainer = this.parentElement.querySelector('.file-preview');
            
            if (file && previewContainer) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    if (file.type.startsWith('image/')) {
                        previewContainer.innerHTML = `
                            <img src="${e.target.result}" alt="Preview" class="preview-image">
                            <p class="file-name">${file.name}</p>
                            <p class="file-size">${formatFileSize(file.size)}</p>
                        `;
                    } else if (file.type === 'application/pdf') {
                        previewContainer.innerHTML = `
                            <div class="file-icon pdf-icon">📄</div>
                            <p class="file-name">${file.name}</p>
                            <p class="file-size">${formatFileSize(file.size)}</p>
                        `;
                    } else {
                        previewContainer.innerHTML = `
                            <div class="file-icon generic-icon">📁</div>
                            <p class="file-name">${file.name}</p>
                            <p class="file-size">${formatFileSize(file.size)}</p>
                        `;
                    }
                };
                
                reader.readAsDataURL(file);
            }
        });
    });
}

/**
 * Format File Size
 */
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return Math.round((bytes / Math.pow(k, i)) * 100) / 100 + ' ' + sizes[i];
}

/**
 * Auto-save Form Data
 */
function initAutoSave() {
    const autoSaveForms = document.querySelectorAll('.auto-save');
    
    autoSaveForms.forEach(function(form) {
        const formId = form.id || 'form_' + Date.now();
        const inputs = form.querySelectorAll('input, textarea, select');
        
        // Load saved data
        inputs.forEach(function(input) {
            const savedValue = localStorage.getItem(formId + '_' + input.name);
            if (savedValue !== null) {
                if (input.type === 'checkbox') {
                    input.checked = savedValue === 'true';
                } else {
                    input.value = savedValue;
                }
            }
        });
        
        // Save on change
        inputs.forEach(function(input) {
            input.addEventListener('input', function() {
                const value = input.type === 'checkbox' ? input.checked : input.value;
                localStorage.setItem(formId + '_' + input.name, value);
                
                // Show save indicator
                showSaveIndicator();
            });
        });
        
        // Clear on submit
        form.addEventListener('submit', function() {
            inputs.forEach(function(input) {
                localStorage.removeItem(formId + '_' + input.name);
            });
        });
    });
}

/**
 * Show Save Indicator
 */
function showSaveIndicator() {
    let indicator = document.querySelector('.save-indicator');
    
    if (!indicator) {
        indicator = document.createElement('div');
        indicator.className = 'save-indicator clay-card';
        indicator.innerHTML = '💾 Menyimpan...';
        indicator.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 10px 20px;
            z-index: 9999;
            animation: slideUp 0.3s ease;
        `;
        document.body.appendChild(indicator);
    }
    
    setTimeout(function() {
        indicator.innerHTML = '✓ Tersimpan';
        setTimeout(function() {
            indicator.remove();
        }, 1000);
    }, 500);
}

/**
 * Confirm Dialog
 */
function confirmAction(message = 'Apakah Anda yakin ingin melanjutkan?') {
    return new Promise(function(resolve) {
        if (confirm(message)) {
            resolve(true);
        } else {
            resolve(false);
        }
    });
}

/**
 * AJAX Helper
 */
async function ajax(url, method = 'GET', data = null) {
    const options = {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    };
    
    if (data && (method === 'POST' || method === 'PUT' || method === 'DELETE')) {
        options.body = JSON.stringify(data);
    }
    
    try {
        const response = await fetch(url, options);
        const result = await response.json();
        
        return {
            success: response.ok,
            status: response.status,
            data: result
        };
    } catch (error) {
        return {
            success: false,
            status: 0,
            error: error.message
        };
    }
}

/**
 * Toast Notification
 */
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type} clay-card`;
    toast.innerHTML = `
        <span class="toast-message">${message}</span>
        <button class="toast-close" onclick="this.parentElement.remove()">×</button>
    `;
    
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 10px;
        animation: slideUp 0.3s ease;
        background: var(--bg-secondary);
        border-radius: var(--radius-lg);
        box-shadow: var(--clay-shadow);
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(function() {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-20px)';
        setTimeout(function() {
            toast.remove();
        }, 300);
    }, 3000);
}

/**
 * Copy to Clipboard
 */
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        showToast('Berhasil disalin ke clipboard!', 'success');
    }).catch(function() {
        showToast('Gagal menyalin ke clipboard', 'error');
    });
}

/**
 * Print Function
 */
function printSection(elementId) {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Cetak</title>');
    printWindow.document.write('</head><body>');
    printWindow.document.write(element.innerHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}

/**
 * Debounce Function (for search inputs)
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Modal Functions
 */
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

// Export functions for global use
window.LMS = {
    showToast,
    ajax,
    confirmAction,
    copyToClipboard,
    openModal,
    closeModal,
    debounce,
    formatFileSize
};
