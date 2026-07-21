// Theme Toggling Logic
window.toggleTheme = function() {
    const isDark = document.documentElement.classList.contains('dark');
    if (isDark) {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    } else {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    }
    updateThemeToggleIcons();
};

function updateThemeToggleIcons() {
    const sunIcon = document.getElementById('theme-sun');
    const moonIcon = document.getElementById('theme-moon');
    
    if (sunIcon && moonIcon) {
        const isDark = document.documentElement.classList.contains('dark');
        if (isDark) {
            sunIcon.classList.remove('hidden');
            moonIcon.classList.add('hidden');
        } else {
            sunIcon.classList.add('hidden');
            moonIcon.classList.remove('hidden');
        }
    }
}

// Mobile Sidebar Navigation Toggle
window.toggleSidebar = function() {
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');
    
    if (sidebar && backdrop) {
        const isHidden = sidebar.classList.contains('-translate-x-full');
        if (isHidden) {
            sidebar.classList.remove('-translate-x-full');
            backdrop.classList.remove('hidden');
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                backdrop.classList.add('opacity-100');
            }, 10);
        } else {
            sidebar.classList.add('-translate-x-full');
            backdrop.classList.remove('opacity-100');
            backdrop.classList.add('opacity-0');
            setTimeout(() => {
                backdrop.classList.add('hidden');
            }, 300);
        }
    }
};

// Module Tab Switching Logic
window.switchTab = function(tabId) {
    // Hide all tabs
    const contents = document.querySelectorAll('.tab-content');
    contents.forEach(el => {
        el.classList.add('hidden');
    });

    // Show selected tab
    const activeContent = document.getElementById(`tab-content-${tabId}`);
    if (activeContent) {
        activeContent.classList.remove('hidden');
        activeContent.classList.add('animate-fade-in');
    }

    // Reset navbar button styles
    const navButtons = document.querySelectorAll('.nav-btn');
    navButtons.forEach(btn => {
        btn.className = "w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-zinc-400 dark:hover:text-zinc-200 dark:hover:bg-zinc-800/60 nav-btn";
        
        // Overview button doesn't have badges, so we keep its layout simple
        if (btn.id === 'tab-btn-dashboard-overview') {
            btn.className = "w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-zinc-400 dark:hover:text-zinc-200 dark:hover:bg-zinc-800/60 nav-btn";
        }
    });

    // Apply active class styles to selected button
    const activeBtn = document.getElementById(`tab-btn-${tabId}`);
    if (activeBtn) {
        if (tabId === 'dashboard-overview') {
            activeBtn.className = "w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 bg-brand-soft dark:bg-brand/10 text-brand dark:text-brand-light nav-btn";
        } else {
            activeBtn.className = "w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 bg-brand-soft dark:bg-brand/10 text-brand dark:text-brand-light nav-btn";
        }
    }

    // Update Topbar Breadcrumb text
    const breadcrumb = document.getElementById('breadcrumb-active');
    if (breadcrumb) {
        const tabNames = {
            'dashboard-overview': 'Overview Dashboard',
            'performance': 'Performance Management',
            'competency': 'Competency Management',
            'learning': 'Learning Management (LMS)',
            'training': 'Training Management',
            'succession': 'Succession Planning',
            'recognition': 'Social Recognition Wall'
        };
        breadcrumb.innerText = tabNames[tabId] || 'Dashboard';
    }

    // Scroll to top of content
    window.scrollTo({ top: 0, behavior: 'smooth' });

    // On mobile, collapse sidebar automatically
    const sidebar = document.getElementById('sidebar');
    if (sidebar && !sidebar.classList.contains('-translate-x-full') && window.innerWidth < 1024) {
        toggleSidebar();
    }
};

// Social Recognition Shout-out Modal Drawer Handling
window.openRecognitionModal = function() {
    const modal = document.getElementById('recognition-modal');
    const backdrop = document.getElementById('modal-backdrop');
    const panel = document.getElementById('modal-panel');
    
    if (modal && backdrop && panel) {
        modal.classList.remove('hidden');
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');
            panel.classList.remove('translate-x-full');
            panel.classList.add('translate-x-0');
        }, 10);
    }
};

window.closeRecognitionModal = function() {
    const modal = document.getElementById('recognition-modal');
    const backdrop = document.getElementById('modal-backdrop');
    const panel = document.getElementById('modal-panel');
    
    if (modal && backdrop && panel) {
        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
        panel.classList.remove('translate-x-0');
        panel.classList.add('translate-x-full');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
};

// Increments claps inside wall/feed items
window.incrementClap = function(btn) {
    const countSpan = btn.querySelector('.clap-count');
    if (countSpan) {
        let count = parseInt(countSpan.innerText, 10) || 0;
        countSpan.innerText = count + 1;
        
        // Simple tactile pulse effect
        btn.classList.add('scale-110');
        setTimeout(() => {
            btn.classList.remove('scale-110');
        }, 150);
    }
};

// Simulated Shout-out creation and DOM injection
window.submitShoutout = function(e) {
    e.preventDefault();
    
    const recipient = document.getElementById('recipient').value;
    const badgeChecked = document.querySelector('input[name="badge"]:checked');
    const message = document.getElementById('message').value;
    
    if (!recipient || !badgeChecked || !message) {
        showToast('Please fill out all fields.', 'error');
        return;
    }
    
    const badge = badgeChecked.value;
    const badgeEmoji = {
        'Customer Focus': '🏆',
        'Collaboration': '💡',
        'Innovation': '⚡',
        'Integrity': '🛡️'
    }[badge] || '⭐';

    // Mock data user profiles for author avatars
    const avatars = [
        'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&h=100&q=80', // Sarah
        'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=100&h=100&q=80', // Emily
        'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=100&h=100&q=80', // David
        'https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?auto=format&fit=crop&w=100&h=100&q=80'  // Marcus
    ];
    
    const newPostHTML = `
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl p-6 space-y-4 hover:shadow-md transition-shadow animate-fade-in">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img class="w-10 h-10 rounded-full object-cover" src="${avatars[0]}" alt="Sarah Connor">
                    <div>
                        <h4 class="font-bold text-slate-900 dark:text-white text-sm">Sarah Connor</h4>
                        <p class="text-xs text-slate-400">HR Director</p>
                    </div>
                </div>
                <span class="text-[10px] text-slate-400">Just now</span>
            </div>
            
            <div class="bg-slate-50 dark:bg-zinc-800/40 p-4 rounded-xl border border-slate-100 dark:border-zinc-800/50">
                <p class="text-xs text-slate-400 uppercase tracking-widest font-bold mb-1 flex items-center gap-1.5 text-brand dark:text-brand-light">
                    <span>${badgeEmoji} ${badge}</span>
                </p>
                <p class="text-sm text-slate-700 dark:text-zinc-300 italic">"${message}"</p>
            </div>

            <div class="flex items-center justify-between border-t border-slate-100 dark:border-zinc-800 pt-3 text-xs">
                <span class="text-slate-400">Received by: <strong class="text-slate-700 dark:text-zinc-200">${recipient}</strong></span>
                <button onclick="incrementClap(this)" class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 rounded-full font-semibold hover:bg-rose-100 dark:hover:bg-rose-900/50 transition-colors">
                    <span>👏 Claps</span>
                    <span class="clap-count">0</span>
                </button>
            </div>
        </div>
    `;

    // Prepend to Wall posts
    const container = document.getElementById('wall-posts-container');
    if (container) {
        container.insertAdjacentHTML('afterbegin', newPostHTML);
    }
    
    // Also prepend simplified view to Overview Feed list
    const overviewList = document.getElementById('overview-recognition-list');
    if (overviewList) {
        const miniHTML = `
            <div class="p-4 bg-slate-50 dark:bg-zinc-800/40 rounded-xl border border-slate-100 dark:border-zinc-800/50 flex items-start gap-4 animate-fade-in">
                <div class="shrink-0 flex -space-x-2">
                    <img class="w-8 h-8 rounded-full ring-2 ring-white dark:ring-zinc-900 object-cover" src="${avatars[0]}" alt="Sarah">
                    <img class="w-8 h-8 rounded-full ring-2 ring-white dark:ring-zinc-900 object-cover" src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=80&h=80&q=80" alt="Receiver">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-slate-800 dark:text-zinc-200">
                        Sarah Connor <span class="font-normal text-slate-500">recognized</span> ${recipient}
                    </p>
                    <p class="text-xs text-slate-600 dark:text-zinc-400 mt-1 italic">"${message.substring(0, 100)}${message.length > 100 ? '...' : ''}"</p>
                    <div class="mt-2.5 flex items-center gap-3">
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded bg-brand-soft dark:bg-brand/10 text-brand dark:text-brand-light">
                            ${badgeEmoji} ${badge}
                        </span>
                        <span class="text-[10px] text-slate-400">Just now</span>
                    </div>
                </div>
            </div>
        `;
        overviewList.insertAdjacentHTML('afterbegin', miniHTML);
    }

    // Reset Form
    document.getElementById('recognition-form').reset();
    
    // Reset selected card outline states in modal
    document.querySelectorAll('.select-badge-card').forEach(card => {
        card.classList.remove('border-brand', 'ring-2', 'ring-brand-soft', 'dark:ring-brand/10');
    });

    closeRecognitionModal();
    showToast(`Successfully recognized ${recipient}!`, 'success');
};

// Toast Alerts Notification Helper
window.showToast = function(msg, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return;
    
    const toast = document.createElement('div');
    toast.className = `p-4 rounded-xl shadow-lg border text-sm font-semibold flex items-center gap-3 transition-all duration-300 transform translate-y-4 opacity-0 pointer-events-auto max-w-sm bg-white dark:bg-zinc-900 ${
        type === 'success' 
            ? 'border-emerald-200 dark:border-emerald-900 text-emerald-800 dark:text-emerald-400' 
            : 'border-rose-200 dark:border-rose-900 text-rose-800 dark:text-rose-400'
    }`;
    
    const icon = type === 'success' 
        ? `<svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`
        : `<svg class="w-5 h-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`;
        
    toast.innerHTML = `${icon}<span>${msg}</span>`;
    container.appendChild(toast);
    
    // Animate In
    setTimeout(() => {
        toast.classList.remove('translate-y-4', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');
    }, 10);
    
    // Animate Out & Remove
    setTimeout(() => {
        toast.classList.remove('translate-y-0', 'opacity-100');
        toast.classList.add('translate-y-4', 'opacity-0');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 3500);
};

// Auto initialize styles and click events on page load
document.addEventListener('DOMContentLoaded', () => {
    updateThemeToggleIcons();

    // Visual selection outline indicator for badge choices inside slide-over modal
    const selectBadgeCards = document.querySelectorAll('.select-badge-card');
    selectBadgeCards.forEach(card => {
        const radio = card.querySelector('input[type="radio"]');
        if (radio) {
            card.addEventListener('click', () => {
                // Clear all selected card outline styles
                selectBadgeCards.forEach(c => {
                    c.classList.remove('border-brand', 'ring-2', 'ring-brand-soft', 'dark:ring-brand/10', 'bg-brand-soft/50', 'dark:bg-brand/10');
                });
                
                // Highlight clicked card
                card.classList.add('border-brand', 'ring-2', 'ring-brand-soft', 'dark:ring-brand/10', 'bg-brand-soft/50', 'dark:bg-brand/10');
                radio.checked = true;
            });
        }
    });
});
