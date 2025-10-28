<!-- WhatsApp Floating Chat Button -->
<style>
/* Base styles */
#whatsapp-widget {
    position: fixed;
    bottom: 20px;
    left: 20px;          /* ⬅️ Changed from right:20px to left:20px */
    z-index: 1000;
    font-family: Arial, sans-serif;
}

/* Responsive size */
#whatsapp-widget a {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #25D366;
    color: #fff;
    font-weight: 600;
    text-decoration: none;
    padding: 12px 18px;
    border-radius: 50px;
    box-shadow: 0 4px 12px rgba(0,0,0,.2);
    transition: transform .2s ease, box-shadow .2s ease;
    font-size: 16px;
    line-height: 1.2;
}

/* Icon sizing */
#whatsapp-widget svg {
    width: 24px;
    height: 24px;
    fill: currentColor;
    flex-shrink: 0;
}

/* Hover effect */
#whatsapp-widget a:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 16px rgba(0,0,0,.25);
}

/* 📱 Small screens adjustments */
@media (max-width: 480px) {
    #whatsapp-widget a {
        padding: 14px 16px;
        font-size: 0; /* hide text for smaller tap target */
    }
    #whatsapp-widget svg {
        width: 32px;
        height: 32px;
    }
}

@media (max-width: 576px) {
    #whatsapp-widget {
        bottom: 15px;
        left: 15px;      /* ⬅️ Adjusted here as well */
        transform: scale(0.9);
    }
}

</style>
 
<div id="whatsapp-widget">
            <a href="https://wa.me/96566534053?text={{ urlencode('Hi! I’d like to know more about your services.') }}" target="_blank">

        <!-- WhatsApp SVG Icon -->
        <svg viewBox="0 0 32 32" aria-hidden="true">
            <path d="M16 .5C7.44.5.5 7.44.5 16c0 2.78.72 5.48 2.1 7.88L0 31.5l7.9-2.06A15.4 15.4 0 0 0 16 31.5c8.56 0 15.5-6.94 15.5-15.5S24.56.5 16 .5zm0 28.2c-2.55 0-5.03-.68-7.2-1.98l-.52-.31-4.69 1.22 1.25-4.58-.34-.54A12.67 12.67 0 0 1 3.3 16C3.3 9.04 9.04 3.3 16 3.3S28.7 9.04 28.7 16 22.96 28.7 16 28.7zm7.35-9.02c-.4-.2-2.36-1.17-2.73-1.3-.37-.13-.64-.2-.9.2s-1.03 1.3-1.27 1.56c-.23.27-.47.3-.87.1-.4-.2-1.7-.63-3.24-2-1.2-1.07-2-2.4-2.24-2.8-.23-.4-.02-.61.17-.81.18-.18.4-.47.6-.7.2-.23.27-.4.4-.67.13-.27.07-.5-.03-.7-.1-.2-.9-2.17-1.23-2.98-.32-.8-.65-.7-.9-.7-.23 0-.5-.03-.77-.03-.27 0-.7.1-1.07.5-.37.4-1.4 1.37-1.4 3.34 0 1.96 1.43 3.85 1.63 4.12.2.27 2.83 4.32 6.85 6.05.96.42 1.7.67 2.27.86.95.3 1.8.26 2.47.16.76-.12 2.36-.96 2.7-1.88.33-.93.33-1.73.23-1.9-.1-.16-.37-.27-.77-.47z"/>
        </svg>
        <span class="whatsapp-text d-none d-md-inline"> Chat with us</span>
    </a>
</div>

<!-- Optional fade-in animation -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('whatsapp-widget');
    btn.style.opacity = 0;
    btn.style.transform = 'translateY(30px)';
    setTimeout(() => {
        btn.style.transition = 'all 0.5s ease';
        btn.style.opacity = 1;
        btn.style.transform = 'translateY(0)';
    }, 400);
});
</script>
