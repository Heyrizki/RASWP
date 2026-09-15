/**
 * Tempo News Theme JavaScript
 *
 * @package Tempo_News
 */

(function($) {
    'use strict';

    // Document Ready
    $(document).ready(function() {
        
        // ============================================
        // DARK MODE TOGGLE
        // ============================================
        const darkModeToggle = document.getElementById('darkModeToggle');
        const darkModeToggleMobile = document.querySelector('.dark-mode-toggle-mobile');
        
        // Check for saved theme preference or default to system preference
        const currentTheme = localStorage.getItem('theme') || 
                            (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        
        if (currentTheme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
            document.body.classList.add('dark-mode');
            updateDarkModeIcon(true);
        }
        
        function updateDarkModeIcon(isDark) {
            const icon = darkModeToggle ? darkModeToggle.querySelector('i') : null;
            if (icon) {
                icon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
            }
        }
        
        function toggleDarkMode() {
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            
            if (isDark) {
                document.documentElement.setAttribute('data-theme', 'light');
                document.body.classList.remove('dark-mode');
                localStorage.setItem('theme', 'light');
                updateDarkModeIcon(false);
            } else {
                document.documentElement.setAttribute('data-theme', 'dark');
                document.body.classList.add('dark-mode');
                localStorage.setItem('theme', 'dark');
                updateDarkModeIcon(true);
            }
        }
        
        if (darkModeToggle) {
            darkModeToggle.addEventListener('click', toggleDarkMode);
        }
        
        if (darkModeToggleMobile) {
            darkModeToggleMobile.addEventListener('click', function(e) {
                e.preventDefault();
                toggleDarkMode();
            });
        }
        
        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
            if (!localStorage.getItem('theme')) {
                if (e.matches) {
                    document.documentElement.setAttribute('data-theme', 'dark');
                    document.body.classList.add('dark-mode');
                    updateDarkModeIcon(true);
                } else {
                    document.documentElement.setAttribute('data-theme', 'light');
                    document.body.classList.remove('dark-mode');
                    updateDarkModeIcon(false);
                }
            }
        });

        // ============================================
        // MOBILE MENU TOGGLE
        // ============================================
        const menuToggle = document.querySelector('.menu-toggle');
        const mobileMenu = document.querySelector('.mobile-menu-overlay');
        
        if (menuToggle) {
            menuToggle.addEventListener('click', function(e) {
                e.preventDefault();
                // Toggle mobile menu
                $('.main-navigation').slideToggle();
            });
        }

        // ============================================
        // SEARCH TOGGLE
        // ============================================
        const searchToggle = document.querySelector('.search-toggle');
        
        if (searchToggle) {
            searchToggle.addEventListener('click', function(e) {
                e.preventDefault();
                // Open search modal or overlay
                openSearchModal();
            });
        }
        
        function openSearchModal() {
            let searchModal = document.querySelector('.search-modal');
            
            if (!searchModal) {
                searchModal = document.createElement('div');
                searchModal.className = 'search-modal';
                searchModal.innerHTML = `
                    <div class="search-modal-content">
                        <button class="search-close">&times;</button>
                        <form role="search" method="get" action="${tempoNews.ajaxUrl.replace('admin-ajax.php', '')}">
                            <input type="search" name="s" placeholder="${tempoNews.i18n.loading}..." />
                            <button type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                `;
                document.body.appendChild(searchModal);
                
                // Close button
                searchModal.querySelector('.search-close').addEventListener('click', function() {
                    searchModal.classList.remove('active');
                    setTimeout(() => searchModal.remove(), 300);
                });
                
                // Close on outside click
                searchModal.addEventListener('click', function(e) {
                    if (e.target === searchModal) {
                        searchModal.classList.remove('active');
                        setTimeout(() => searchModal.remove(), 300);
                    }
                });
            }
            
            searchModal.classList.add('active');
            searchModal.querySelector('input').focus();
        }

        // ============================================
        // COMMENT NOTIFICATION
        // ============================================
        function showCommentNotification(author, postTitle) {
            const notification = document.getElementById('commentNotification');
            const notificationText = document.getElementById('commentNotificationText');
            
            if (notification && notificationText) {
                notificationText.textContent = `${author} commented on "${postTitle}"`;
                notification.classList.add('show');
                
                // Auto hide after 5 seconds
                setTimeout(function() {
                    notification.classList.remove('show');
                }, 5000);
            }
        }
        
        // Poll for new comments (every 30 seconds)
        function pollForNewComments() {
            if (typeof tempoNews !== 'undefined' && typeof tempoNws.postId !== 'undefined') {
                $.ajax({
                    url: tempoNews.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'tempo_news_get_new_comments',
                        post_id: tempoNews.postId,
                        nonce: tempoNews.nonce,
                        last_comment_time: localStorage.getItem('lastCommentTime') || 0
                    },
                    success: function(response) {
                        if (response.success && response.data.new_comment) {
                            showCommentNotification(response.data.author, response.data.post_title);
                            localStorage.setItem('lastCommentTime', response.data.comment_time);
                        }
                    }
                });
            }
        }
        
        // Only poll on single post pages
        if ($('.single-article').length > 0) {
            setInterval(pollForNewComments, 30000);
        }

        // ============================================
        // STICKY HEADER
        // ============================================
        let lastScroll = 0;
        const header = document.querySelector('.site-header');
        
        if (header) {
            window.addEventListener('scroll', function() {
                const currentScroll = window.pageYOffset;
                
                if (currentScroll > 100) {
                    header.classList.add('sticky');
                } else {
                    header.classList.remove('sticky');
                }
                
                lastScroll = currentScroll;
            });
        }

        // ============================================
        // LAZY LOADING IMAGES
        // ============================================
        if ('loading' in HTMLImageElement.prototype) {
            const images = document.querySelectorAll('img[loading="lazy"]');
            images.forEach(function(img) {
                img.src = img.dataset.src;
            });
        } else {
            // Fallback for browsers that don't support lazy loading
            const script = document.createElement('script');
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js';
            document.body.appendChild(script);
        }

        // ============================================
        // SMOOTH SCROLL FOR ANCHOR LINKS
        // ============================================
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href !== '#' && href.length > 1) {
                    const target = document.querySelector(href);
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            });
        });

        // ============================================
        // PARALLAX AD EFFECT
        // ============================================
        const parallaxAds = document.querySelectorAll('.parallax-ad');
        
        if (parallaxAds.length > 0 && window.innerWidth > 768) {
            window.addEventListener('scroll', function() {
                const scrolled = window.pageYOffset;
                
                parallaxAds.forEach(function(ad) {
                    const container = ad.parentElement;
                    const containerTop = container.offsetTop;
                    const containerHeight = container.offsetHeight;
                    
                    if (scrolled > containerTop - window.innerHeight && 
                        scrolled < containerTop + containerHeight) {
                        const yPos = (scrolled - containerTop) * 0.3;
                        ad.style.transform = 'translateY(' + yPos + 'px)';
                    }
                });
            });
        }

        // ============================================
        // TRENDING POSTS WIDGET AUTO-REFRESH
        // ============================================
        function refreshTrendingPosts() {
            if (typeof tempoNews !== 'undefined') {
                $.ajax({
                    url: tempoNews.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'tempo_news_get_trending_posts',
                        nonce: tempoNews.nonce,
                        count: 5
                    },
                    success: function(response) {
                        if (response.success) {
                            $('.trending-posts').html(response.data.html);
                        }
                    }
                });
            }
        }
        
        // Refresh trending posts every 5 minutes
        setInterval(refreshTrendingPosts, 300000);

        // ============================================
        // READING PROGRESS BAR
        // ============================================
        function createReadingProgressBar() {
            const progressBar = document.createElement('div');
            progressBar.className = 'reading-progress-bar';
            progressBar.innerHTML = '<div class="reading-progress"></div>';
            document.body.insertBefore(progressBar, document.body.firstChild);
            
            window.addEventListener('scroll', function() {
                const article = document.querySelector('.article-content');
                if (!article) return;
                
                const articleTop = article.offsetTop;
                const articleHeight = article.offsetHeight;
                const windowHeight = window.innerHeight;
                const scrolled = window.pageYOffset;
                
                const progress = Math.max(0, Math.min(100, 
                    ((scrolled - articleTop + windowHeight) / articleHeight) * 100
                ));
                
                const progressEl = progressBar.querySelector('.reading-progress');
                if (progressEl) {
                    progressEl.style.width = progress + '%';
                }
            });
        }
        
        if ($('.single-article').length > 0) {
            createReadingProgressBar();
        }

        // ============================================
        // SOCIAL SHARE COUNTER (Optional)
        // ============================================
        // Can be extended to fetch actual share counts from social networks

    }); // End Document Ready

})(jQuery);

// ============================================
// AMP COMPATIBILITY
// ============================================
if (typeof AMP !== 'undefined') {
    AMP.push(function(AMP) {
        // AMP specific JavaScript can go here
        console.log('AMP mode active');
    });
}
