<style>
    /* Dark Theme Styles - Applied when .dark class is present */
    .dark body {
        background: #0f1419 !important;
        color: rgba(255, 255, 255, 0.9) !important;
    }
    
    /* Stats Cards with Gradients - Dark Theme */
    .dark .stat-gradient-blue {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%) !important;
        box-shadow: 0 8px 32px rgba(99, 102, 241, 0.35) !important;
    }
    .dark .stat-gradient-blue * { color: white !important; }
    
    .dark .stat-gradient-green {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        box-shadow: 0 8px 32px rgba(16, 185, 129, 0.35) !important;
    }
    .dark .stat-gradient-green * { color: white !important; }
    
    .dark .stat-gradient-orange {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
        box-shadow: 0 8px 32px rgba(245, 158, 11, 0.35) !important;
    }
    .dark .stat-gradient-orange * { color: white !important; }
    
    .dark .stat-gradient-purple {
        background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%) !important;
        box-shadow: 0 8px 32px rgba(168, 85, 247, 0.35) !important;
    }
    .dark .stat-gradient-purple * { color: white !important; }
    
    .dark .stat-gradient-red {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        box-shadow: 0 8px 32px rgba(239, 68, 68, 0.35) !important;
    }
    .dark .stat-gradient-red * { color: white !important; }
    
    .dark .stat-gradient-cyan {
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%) !important;
        box-shadow: 0 8px 32px rgba(6, 182, 212, 0.35) !important;
    }
    .dark .stat-gradient-cyan * { color: white !important; }
    
    /* Light Theme Stats Cards - Softer Gradients */
    .stat-gradient-blue {
        background: linear-gradient(135deg, #818cf8 0%, #a78bfa 100%) !important;
        box-shadow: 0 4px 20px rgba(99, 102, 241, 0.2) !important;
    }
    .stat-gradient-blue * { color: white !important; }
    
    .stat-gradient-green {
        background: linear-gradient(135deg, #34d399 0%, #10b981 100%) !important;
        box-shadow: 0 4px 20px rgba(16, 185, 129, 0.2) !important;
    }
    .stat-gradient-green * { color: white !important; }
    
    .stat-gradient-orange {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%) !important;
        box-shadow: 0 4px 20px rgba(245, 158, 11, 0.2) !important;
    }
    .stat-gradient-orange * { color: white !important; }
    
    .stat-gradient-purple {
        background: linear-gradient(135deg, #c084fc 0%, #a855f7 100%) !important;
        box-shadow: 0 4px 20px rgba(168, 85, 247, 0.2) !important;
    }
    .stat-gradient-purple * { color: white !important; }
    
    .stat-gradient-red {
        background: linear-gradient(135deg, #f87171 0%, #ef4444 100%) !important;
        box-shadow: 0 4px 20px rgba(239, 68, 68, 0.2) !important;
    }
    .stat-gradient-red * { color: white !important; }
    
    .stat-gradient-cyan {
        background: linear-gradient(135deg, #22d3ee 0%, #06b6d4 100%) !important;
        box-shadow: 0 4px 20px rgba(6, 182, 212, 0.2) !important;
    }
    .stat-gradient-cyan * { color: white !important; }
</style>

<script>
    // Set default to dark mode on first load if no preference exists
    document.addEventListener('DOMContentLoaded', function() {
        const darkMode = localStorage.getItem('theme');
        if (!darkMode) {
            // First time visitor - default to dark mode
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
    });
</script>

